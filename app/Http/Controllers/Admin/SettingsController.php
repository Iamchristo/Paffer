<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\WritesEnvFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class SettingsController extends Controller
{
    use WritesEnvFile;

    public function edit(): View
    {
        return view('admin.settings', [
            'values' => [
                'mailer' => env('MAIL_MAILER', 'log'),
                'host' => env('MAIL_HOST', ''),
                'port' => env('MAIL_PORT', ''),
                'username' => env('MAIL_USERNAME', ''),
                'encryption' => env('MAIL_ENCRYPTION', ''),
                'from_address' => env('MAIL_FROM_ADDRESS', ''),
                'from_name' => env('MAIL_FROM_NAME', ''),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'mailer' => ['required', 'in:smtp,log,sendmail'],
            'host' => ['nullable', 'string', 'max:255'],
            'port' => ['nullable', 'numeric'],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
            'encryption' => ['nullable', 'in:tls,ssl,'],
            'from_address' => ['required', 'email', 'max:255'],
            'from_name' => ['required', 'string', 'max:255'],
        ]);

        $values = [
            'MAIL_MAILER' => $data['mailer'],
            'MAIL_HOST' => $data['host'] ?? '',
            'MAIL_PORT' => (string) ($data['port'] ?? ''),
            'MAIL_USERNAME' => $data['username'] ?? '',
            'MAIL_ENCRYPTION' => $data['encryption'] ?? '',
            'MAIL_FROM_ADDRESS' => $data['from_address'],
            'MAIL_FROM_NAME' => $data['from_name'],
        ];

        if (filled($data['password'] ?? null)) {
            $values['MAIL_PASSWORD'] = $data['password'];
        }

        $this->writeEnv($values);

        Artisan::call('config:clear');

        return back()->with('status', 'settings-updated');
    }
}
