<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\CategorySubscription;
use App\Models\PayInvoice;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\AppSetting;
use App\Models\Admin;
use App\Models\Notification;


class PayInvoiceController extends Controller
{

    public function displayCategoriesSubscription()
    {
        $category_subscriptions = CategorySubscription::get();
        return response()->json(['data' => $category_subscriptions], 200);
    }


    public function submitSubscription(Request $request)
    {
        $user = auth()->user();
        // Validate the request
        $request->validate([
            'category_subscription_id' => 'required|exists:category_subscriptions,id',
            'number_of_subscription' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        // Check if the user has enough balance in their wallet
        $wallet = Wallet::where('user_id', $request->user()->id)->first();

        if ($wallet && $wallet->total >= $request->amount) {
            // Create a new PayInvoice record
            $payInvoice = new PayInvoice();
            $payInvoice->category_subscription_id = $request->category_subscription_id;
            $payInvoice->number_of_subscription = $request->number_of_subscription;
            $payInvoice->amount = $request->amount;
            $payInvoice->user_id = $request->user()->id;
            $payInvoice->status = 2; // Initially not approved
            $payInvoice->save();

            // Notification logic
            if ($user) {  // Check if authenticated user exists
                $title = 'You have a new Pay Invoice';
                $body =  $payInvoice->amount . ' from ' . $user->name . 'to' . $payInvoice->categorySubscription->name . $payInvoice->number_of_subscription ; // Access current user's name
                $type = 'Pay Invoice';
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

            return response()->json(['message' => 'Subscription submitted successfully.'], 200);
        }

        return response()->json(['message' => 'Insufficient wallet balance.'], 400);
    }

}
