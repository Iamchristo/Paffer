<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\WritesEnvFile;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use PDO;
use PDOException;
use Throwable;

class InstallController extends Controller
{
    use WritesEnvFile;

    public function welcome(): View
    {
        $checks = [
            'PHP 8.2 or newer' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'pdo_mysql extension enabled' => extension_loaded('pdo_mysql'),
            'mbstring extension enabled' => extension_loaded('mbstring'),
            'openssl extension enabled' => extension_loaded('openssl'),
            'storage/ is writable' => is_writable(storage_path()),
            'bootstrap/cache/ is writable' => is_writable(base_path('bootstrap/cache')),
            '.env is writable' => is_writable(base_path('.env')),
        ];

        return view('install.welcome', [
            'checks' => $checks,
            'allPassed' => ! in_array(false, $checks, true),
        ]);
    }

    public function database(): View
    {
        return view('install.database', [
            'values' => [
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => env('DB_DATABASE', ''),
                'username' => env('DB_USERNAME', ''),
            ],
        ]);
    }

    public function storeDatabase(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'host' => ['required', 'string', 'max:255'],
            'port' => ['required', 'numeric'],
            'database' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            new PDO(
                "mysql:host={$data['host']};port={$data['port']};dbname={$data['database']};charset=utf8mb4",
                $data['username'],
                $data['password'] ?? '',
                [PDO::ATTR_TIMEOUT => 5]
            );
        } catch (PDOException $e) {
            return back()->withInput()->withErrors([
                'database' => 'Could not connect to MySQL with these details: '.$e->getMessage(),
            ]);
        }

        $this->writeEnv([
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => $data['host'],
            'DB_PORT' => $data['port'],
            'DB_DATABASE' => $data['database'],
            'DB_USERNAME' => $data['username'],
            'DB_PASSWORD' => $data['password'] ?? '',
        ]);

        config([
            'database.default' => 'mysql',
            'database.connections.mysql.host' => $data['host'],
            'database.connections.mysql.port' => $data['port'],
            'database.connections.mysql.database' => $data['database'],
            'database.connections.mysql.username' => $data['username'],
            'database.connections.mysql.password' => $data['password'] ?? '',
        ]);
        DB::purge('mysql');

        try {
            Artisan::call('migrate:fresh', ['--force' => true]);
        } catch (Throwable $e) {
            return back()->withInput()->withErrors([
                'database' => 'Connected, but migrations failed: '.$e->getMessage(),
            ]);
        }

        $request->session()->put('install.db_ready', true);

        return redirect()->route('install.admin');
    }

    public function admin(Request $request): View|RedirectResponse
    {
        if (! $request->session()->get('install.db_ready')) {
            return redirect()->route('install.database');
        }

        return view('install.admin');
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        if (! $request->session()->get('install.db_ready')) {
            return redirect()->route('install.database');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $admin = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // role/email_verified_at aren't mass-assignable on User by design
        // (only admin-controlled), so set them directly.
        $admin->role = 'admin';
        $admin->email_verified_at = now();
        $admin->save();

        Auth::login($admin);

        $request->session()->put('install.admin_ready', true);

        return redirect()->route('install.demo-data');
    }

    public function demoData(Request $request): View|RedirectResponse
    {
        if (! $request->session()->get('install.admin_ready')) {
            return redirect()->route('install.admin');
        }

        return view('install.demo-data');
    }

    public function storeDemoData(Request $request): RedirectResponse
    {
        if (! $request->session()->get('install.admin_ready')) {
            return redirect()->route('install.admin');
        }

        $request->validate([
            'choice' => ['required', 'in:yes,no'],
        ]);

        $imported = $request->input('choice') === 'yes';

        if ($imported) {
            Artisan::call('db:seed', [
                '--class' => DemoDataSeeder::class,
                '--force' => true,
            ]);
        }

        File::put(storage_path('installed'), now()->toDateTimeString());

        $request->session()->forget(['install.db_ready', 'install.admin_ready']);
        $request->session()->flash('install.demo_imported', $imported);

        return redirect()->route('install.finish');
    }

    public function finish(Request $request): View|RedirectResponse
    {
        if (! is_file(storage_path('installed'))) {
            return redirect()->route('install.welcome');
        }

        return view('install.finish', [
            'demoDataImported' => $request->session()->get('install.demo_imported', false),
        ]);
    }
}
