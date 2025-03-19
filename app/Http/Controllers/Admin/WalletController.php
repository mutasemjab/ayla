<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Wallet::with('user')->paginate(PAGINATION_COUNT);
        return view('admin.wallets.index', compact('wallets'));
    }

    public function create()
    {
        if (auth()->user()->can('wallet-add')) {
            return view('admin.wallets.create');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }


    public function store(Request $request)
    {
        try {
                // Create a record in wallet_transactions table
                $walletTransaction = new WalletTransaction();
                $walletTransaction->wallet_id = 1;
                $walletTransaction->note = $request->get('note');
                $walletTransaction->deposit = $request->get('deposit');
                $walletTransaction->withdrawal = abs($request->get('withdrawal'));
                $walletTransaction->save();

                // Update the total in the wallets table
                $wallet = Wallet::find(1);
                if ($request->get('deposit')) {
                    $wallet->total += $request->get('deposit');
                }

                if ($request->get('withdrawal')) {
                    $wallet->total -= abs($request->get('withdrawal'));
                }
                $wallet->save();

                return redirect()->back()->with(['success' => 'Add Wallet to Admin Successfully']);

        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function show(Wallet $wallet)
    {
        $transactions = $wallet->walletTransactions()->get();
        return view('admin.wallets.show', compact('wallet', 'transactions'));
    }
}
