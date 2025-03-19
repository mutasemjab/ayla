<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransferBank;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TransferBankController extends Controller
{
    public function index()
    {

        $data = TransferBank::with('user')->paginate(PAGINATION_COUNT);

        return view('admin.transferBanks.index', ['data' => $data]);
    }

    public function approveRequest($id)
    {
        $transferBank = TransferBank::findOrFail($id);
        $transferBank->status = 1; // Approve
        $transferBank->save();

        // Transfer amount from admin to user wallet
        $adminWallet = Wallet::where('admin_id', 1)->first();
        $userWallet = Wallet::where('user_id', $transferBank->user_id)->first();

        if ($adminWallet && $adminWallet->total >= $transferBank->amount && $userWallet->total >= $transferBank->amount) {
            // Decrease amount from both wallets
            $adminWallet->total -= $transferBank->amount;
            $userWallet->total -= $transferBank->amount;
            $adminWallet->save();
            $userWallet->save();

            // Record the transaction in wallet_transactions
           DB::transaction(function() use ($adminWallet, $userWallet, $transferBank) {
                // Admin transaction
               DB::table('wallet_transactions')->insert([
                    'deposit' => 0,
                    'withdrawal' => $transferBank->amount,
                    'note' => 'Approved Transfer ID: ' . $transferBank->id,
                    'wallet_id' => $adminWallet->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // User transaction
                DB::table('wallet_transactions')->insert([
                    'deposit' => 0,
                    'withdrawal' => $transferBank->amount,
                    'note' => 'Approved Transfer ID: ' . $transferBank->id,
                    'wallet_id' => $userWallet->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
        } else {
            return redirect()->back()->with('error', 'Insufficient balance in one or both wallets.');
        }

        return redirect()->back()->with('success', 'Request approved.');
    }

    public function rejectRequest($id)
    {
        $transferBank = TransferBank::findOrFail($id);
        $transferBank->status = 3; // Reject
        $transferBank->save();

        return redirect()->back()->with('success', 'Request rejected.');
    }
}
