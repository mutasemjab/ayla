<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestBalance;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestBalanceController extends Controller
{

    public function index()
    {

        $data = RequestBalance::paginate(PAGINATION_COUNT);

        return view('admin.requestBalances.index', ['data' => $data]);
    }

    public function approve($id)
{
    $requestBalance = RequestBalance::findOrFail($id);
    $adminWallet = Wallet::where('admin_id', 1)->first(); // Assuming there's only one admin
    $userWallet = Wallet::where('user_id', $requestBalance->user_id)->first();

    if (!$userWallet) {
        // Create a wallet for the user if it doesn't exist
        $userWallet = Wallet::create([
            'total' => 0.0,
            'user_id' => $requestBalance->user_id,
            'admin_id' => null,
        ]);
    }

    if ($adminWallet->total < $requestBalance->amount) {
        return redirect()->back()->with('error', 'Not enough balance to approve this request.');
    }

    DB::transaction(function () use ($requestBalance, $adminWallet, $userWallet) {
        // Deduct the amount from the admin's wallet
        $adminWallet->total -= $requestBalance->amount;
        $adminWallet->save();

        // Record the transaction in wallet_transactions for admin
        WalletTransaction::create([
            'withdrawal' => $requestBalance->amount,
            'note' => 'Transfer to user ID ' . $requestBalance->user_id,
            'wallet_id' => $adminWallet->id,
        ]);

        // Add the amount to the user's wallet
        $userWallet->total += $requestBalance->amount;
        $userWallet->save();

        // Record the transaction in wallet_transactions for user
        WalletTransaction::create([
            'deposit' => $requestBalance->amount,
            'note' => 'Received from admin',
            'wallet_id' => $userWallet->id,
        ]);

        // Update the request balance status
        $requestBalance->status = 1; // Approved
        $requestBalance->save();
    });

    return redirect()->back()->with('success', 'Request approved successfully.');
}

public function reject($id)
{
    $requestBalance = RequestBalance::findOrFail($id);
    $requestBalance->status = 3; // Rejected
    $requestBalance->save();

    // Optionally, you could record the rejection in a wallet transaction, though it might not be necessary.

    return redirect()->back()->with('success', 'Request rejected successfully.');
}


}

