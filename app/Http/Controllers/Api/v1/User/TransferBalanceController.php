<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\TransferBalance;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class TransferBalanceController extends Controller
{
    public function initiateTransfer(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        // Find the recipient user by phone number
        $recipient = User::where('phone', $request->phone)->first();

        if (!$recipient) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Return the recipient's name
        return response()->json(['name' => $recipient->name]);
    }

     // Function to handle the balance transfer
     public function transfer(Request $request)
     {
         $request->validate([
             'phone' => 'required|string',
             'amount' => 'required|numeric|min:0.01',
         ]);

         $fromUser = $request->user(); // Assuming the authenticated user is the one making the transfer
         $recipient = User::where('phone', $request->phone)->first();

         if (!$recipient) {
             return response()->json(['error' => 'Recipient not found'], 404);
         }

         // Check if the sender has enough balance
         $fromWallet = Wallet::where('user_id', $fromUser->id)->first();

         if ($fromWallet->total < $request->amount) {
             return response()->json(['error' => 'Insufficient balance'], 400);
         }

         // Deduct the amount from the sender's wallet
         $fromWallet->total -= $request->amount;
         $fromWallet->save();

         // Add the amount to the recipient's wallet
         $toWallet = Wallet::where('user_id', $recipient->id)->first();
         $toWallet->total += $request->amount;
         $toWallet->save();

         // Record the transaction in wallet_transactions
         WalletTransaction::create([
             'wallet_id' => $fromWallet->id,
             'withdrawal' => $request->amount,
             'note' => 'Transfer to ' . $recipient->name,
         ]);

         WalletTransaction::create([
             'wallet_id' => $toWallet->id,
             'deposit' => $request->amount,
             'note' => 'Transfer from ' . $fromUser->name,
         ]);

         // Record the transfer in transfer_balances table
         TransferBalance::create([
             'amount' => $request->amount,
             'from_user_id' => $fromUser->id,
             'to_user_id' => $recipient->id,
         ]);

         return response()->json(['message' => 'Transfer successful'], 200);
     }

}
