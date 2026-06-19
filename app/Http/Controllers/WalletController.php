<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(Request $request): View
    {
        $wallet = $request->user()->wallet;

        return view('wallet.index', [
            'wallet' => $wallet,
            'transactions' => $wallet->transactions()->paginate(15),
        ]);
    }

    public function storeTopup(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:10000'],
        ]);

        $request->user()->wallet->transactions()->create([
            'type' => 'topup',
            'amount_cents' => (int) round($data['amount'] * 100),
            'status' => 'pending',
            'description' => 'Top-up request awaiting admin approval',
        ]);

        return back()->with('status', 'topup-requested');
    }
}
