<?php


namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Receivable;
use App\Models\ReceivableTransaction;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class TransferController extends Controller
{

     public function search(Request $request)
    {
        $dealerId = auth()->user()->id;
        $query = $request->input('query');

        $customers = User::where('user_type', 1)->where('user_id',$dealerId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->get();

        return response()->json(['data' => $customers]);
    }
    
   public function store(Request $request)
    {
        $dealerId = auth()->user()->id;
    
        $transfer = new Transfer();
        $transfer->withdrawal = $request->get('withdrawal');
        $transfer->deposit = $request->get('deposit');
        $transfer->note = $request->get('note');
    
        // Determine if user or dealer is selected
        $userId = $request->get('user_id');
        if (!$userId) {
            return response()->json(['error' => 'You must select either a user.'], 200);
        }
    
        $transfer->user_id = $userId;
    
        // Check dealer's wallet balance before proceeding
        $dealerWallet = Wallet::where('user_id', $dealerId)->first();
        if ($dealerWallet) {
            // Calculate the required amount to transfer
            $requiredAmount = abs($request->get('deposit'));
    
            // Check if the dealer has sufficient balance
            if ($dealerWallet->total < $requiredAmount) {
                return response()->json(['error' => 'Insufficient balance in dealer wallet.'], 400);
            }
    
            if ($transfer->save()) {
                // Create a record in wallet_transactions table for the user
                $walletTransaction = new WalletTransaction();
                $walletTransaction->wallet_id = $transfer->user->wallets->first()->id;
                $walletTransaction->note = $request->get('note');
                $walletTransaction->deposit = $request->get('deposit');
                $walletTransaction->withdrawal = abs($request->get('withdrawal'));
                $walletTransaction->save();
    
                // Update the total in the user's wallet
                $wallet = Wallet::find($transfer->user->wallets->first()->id);
                if ($request->get('deposit')) {
                    $wallet->total += $request->get('deposit');
                }
    
                if ($request->get('withdrawal')) {
                    $wallet->total -= abs($request->get('withdrawal'));
                }
                $wallet->save();
    
                // Subtract the amount from the dealer's wallet and create a transaction for the dealer
                $dealerWallet->total -= $requiredAmount;
                $dealerWallet->save();
    
                // Create a record in wallet_transactions table for the dealer
                $adminWalletTransaction = new WalletTransaction();
                $adminWalletTransaction->wallet_id = $dealerWallet->id;
                $adminWalletTransaction->note = "Dealer transfer to user ID: {$userId}";
                $adminWalletTransaction->deposit = 0;
                $adminWalletTransaction->withdrawal = $requiredAmount;
                $adminWalletTransaction->save();

                Receivable::create([
                    'user_id' => $userId,
                    'dealer_id' => $dealerId,
                    'total_amount' => $request->get('deposit'),
                    'remaining_amount' => $request->get('deposit'),
                ]);
    
                return response()->json(['success' => 'Transfer created']);
            } else {
                return response()->json(['error' => 'Something went wrong'], 400);
            }
        }
    
        return response()->json(['error' => 'Dealer wallet not found'], 400);
    }



}
