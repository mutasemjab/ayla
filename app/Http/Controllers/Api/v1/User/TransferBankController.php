<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\TransferBank;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Helpers\AppSetting;
use App\Models\Admin;
use App\Models\Notification;

class TransferBankController extends Controller
{
    public function submitTransfer(Request $request)
    {
        $user = auth()->user();
        // Validate the request
        $request->validate([
            'name_of_wallet' => 'required|string',
            'number_of_wallet' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        // Check if the user has enough balance in their wallet
        $wallet = Wallet::where('user_id', $request->user()->id)->first();

        if ($wallet && $wallet->total >= $request->amount) {
            // Create a new PayInvoice record
            $transferBank = new TransferBank();
            $transferBank->name_of_wallet = $request->name_of_wallet;
            $transferBank->number_of_wallet = $request->number_of_wallet;
            $transferBank->amount = $request->amount;
            $transferBank->user_id = $request->user()->id;
            $transferBank->status = 2; // Initially not approved
            $transferBank->save();

             // Notification logic
             if ($user) {  // Check if authenticated user exists
                $title = 'You have a new Transfer to wallet';
                $body =  $transferBank->amount . ' from ' . $user->name . 'to' . $transferBank->name_of_wallet . $transferBank->number_of_wallet ; // Access current user's name
                $type = 'Transfer to wallet';
                $order_id = 1;

                // Fetch admin to send notification (assuming you're notifying a specific admin)
                $admin = Admin::first(); // Fetch the first admin as an example, or adjust to your logic

                if ($admin && $admin->fcm_token) {
                    // Send push notification
                    AppSetting::push_notification($admin->fcm_token, $title, $body, $type, $order_id);

                    // Save the notification
                    $notification = new Notification([
                        'title' => $title,
                        'body' => $body,
                        'admin_id' => $admin->id, // Associate notification with the admin
                    ]);
                    $notification->save();
                }
            }

            return response()->json(['message' => 'Transfer To Wallet submitted successfully.'], 200);
        }

        return response()->json(['message' => 'Insufficient wallet balance.'], 400);
    }
}
