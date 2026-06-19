<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $wallets = User::query()
            ->with('wallet')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->whereHas('wallet')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $pendingTopups = WalletTransaction::with('wallet.user')
            ->where('type', 'topup')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.wallets.index', [
            'wallets' => $wallets,
            'search' => $search,
            'pendingTopups' => $pendingTopups,
        ]);
    }

    public function approveTopup(WalletTransaction $transaction): RedirectResponse
    {
        abort_unless($transaction->type === 'topup' && $transaction->isPending(), 404);

        $transaction->update(['status' => 'completed']);
        $transaction->wallet->credit($transaction->amount_cents);

        return back()->with('status', 'topup-approved');
    }

    public function rejectTopup(WalletTransaction $transaction): RedirectResponse
    {
        abort_unless($transaction->type === 'topup' && $transaction->isPending(), 404);

        $transaction->update(['status' => 'rejected']);

        return back()->with('status', 'topup-rejected');
    }

    public function adjust(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:-10000', 'max:10000'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $cents = (int) round($data['amount'] * 100);

        $user->wallet->transactions()->create([
            'type' => 'adjustment',
            'amount_cents' => $cents,
            'status' => 'completed',
            'description' => $data['description'] ?? 'Manual admin adjustment',
        ]);

        $user->wallet->increment('balance_cents', $cents);

        return back()->with('status', 'wallet-adjusted');
    }
}
