<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayInvoice;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayInvoiceController extends Controller
{

       public function index()
    {

        $data = PayInvoice::with(['user', 'categorySubscription'])->paginate(PAGINATION_COUNT);

        return view('admin.payInvoices.index', ['data' => $data]);
    }

    public function approveRequest($id)
    {
        $payInvoice = PayInvoice::findOrFail($id);
        $payInvoice->status = 1; // Approve
        $payInvoice->save();

        // Transfer amount from admin to user wallet
        $adminWallet = Wallet::where('admin_id', 1)->first();
        $userWallet = Wallet::where('user_id', $payInvoice->user_id)->first();

        if ($adminWallet && $adminWallet->total >= $payInvoice->amount && $userWallet->total >= $payInvoice->amount) {
            // Decrease amount from both wallets
            $adminWallet->total -= $payInvoice->amount;
            $userWallet->total -= $payInvoice->amount;
            $adminWallet->save();
            $userWallet->save();

            // Record the transaction in wallet_transactions
           DB::transaction(function() use ($adminWallet, $userWallet, $payInvoice) {
                // Admin transaction
               DB::table('wallet_transactions')->insert([
                    'deposit' => 0,
                    'withdrawal' => $payInvoice->amount,
                    'note' => 'Approved PayInvoice ID: ' . $payInvoice->id,
                    'wallet_id' => $adminWallet->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // User transaction
                DB::table('wallet_transactions')->insert([
                    'deposit' => 0,
                    'withdrawal' => $payInvoice->amount,
                    'note' => 'Approved PayInvoice ID: ' . $payInvoice->id,
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
        $payInvoice = PayInvoice::findOrFail($id);
        $payInvoice->status = 3; // Reject
        $payInvoice->save();

        return redirect()->back()->with('success', 'Request rejected.');
    }
}
