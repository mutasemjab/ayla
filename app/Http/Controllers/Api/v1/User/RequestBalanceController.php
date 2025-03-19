<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Receivable;
use App\Models\RequestBalance;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;


class RequestBalanceController extends Controller
{

    public function store(Request $request)
    {

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'user_id' => 'required|exists:users,id',
            'photo_of_request' => 'required',
        ]);

        $requestBalance = new RequestBalance();
        $requestBalance->amount = $validated['amount'];
        $requestBalance->user_id = $validated['user_id'];
        if ($request->has('photo_of_request')) {
            $the_file_path = uploadImage('assets/admin/uploads', $request->photo_of_request);
            $requestBalance->photo_of_request = $the_file_path;
        }
        $requestBalance->status = 2; // Not approved yet
        $requestBalance->save();


        return response()->json(['message' => 'Request submitted successfully'], 200);
    }

    // get the reuest balance  to delear

    public function index()
    {
        $users_under_delear = User::where('user_id', auth()->user()->id)->pluck('id')->toArray();

        $data = RequestBalance::where('status',2)->with('user')->whereIn('user_id', $users_under_delear)->get();

        return response()->json(['data' => $data]);
    }

    public function approve($id)
    {

        $requestBalance = RequestBalance::findOrFail($id);
        $delearWallet = Wallet::where('user_id', auth()->user()->id)->first(); // Assuming there's only one delear
        $userWallet = Wallet::where('user_id', $requestBalance->user_id)->first();

        if (!$userWallet) {
            // Create a wallet for the user if it doesn't exist
            $userWallet = Wallet::create([
                'total' => 0.0,
                'user_id' => $requestBalance->user_id,
                'admin_id' => null,
            ]);
        }

        if ($delearWallet->total < $requestBalance->amount) {
          return response()->json(['message' => 'Not enough balance to approve this request.'], 200);
        }

        DB::transaction(function () use ($requestBalance, $delearWallet, $userWallet) {
            // Deduct the amount from the delear's wallet
            $delearWallet->total -= $requestBalance->amount;
            $delearWallet->save();

            // Record the transaction in wallet_transactions for delear
            WalletTransaction::create([
                'withdrawal' => $requestBalance->amount,
                'note' => 'Transfer to user ID ' . $requestBalance->user_id,
                'wallet_id' => $delearWallet->id,
            ]);

            // Add the amount to the user's wallet
            $userWallet->total += $requestBalance->amount;
            $userWallet->save();

            // Record the transaction in wallet_transactions for user
            WalletTransaction::create([
                'deposit' => $requestBalance->amount,
                'note' => 'Received from delear',
                'wallet_id' => $userWallet->id,
            ]);

            // Update the request balance status
            $requestBalance->status = 1; // Approved
            $requestBalance->save();
        });

            Receivable::create([
                'user_id' => $requestBalance->user_id,
                'dealer_id' => auth()->user()->id,
                'total_amount' => $requestBalance->amount,
                'remaining_amount' =>$requestBalance->amount,
            ]);

         return response()->json(['message' => 'Request approved successfully.'], 200);
    }

    public function reject($id)
    {
        $requestBalance = RequestBalance::findOrFail($id);
        $requestBalance->status = 3; // Rejected
        $requestBalance->save();

        // Optionally, you could record the rejection in a wallet transaction, though it might not be necessary.

        return response()->json(['message' => 'Request Reject successfully.'], 200);

    }



    public function reportForDelear(Request $request)
    {
        // Validate the request to ensure from_date and to_date are provided
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'status' => 'nullable|in:1,2,3' // Optional status filter
        ]);

        // Get all users under the current dealer
        $users_under_delear = User::where('user_id', auth()->user()->id)->pluck('id')->toArray();

        // Query to filter RequestBalance records
        $query = RequestBalance::with('user')->whereIn('user_id', $users_under_delear)
            ->whereBetween('created_at', [$request->from_date, $request->to_date]);

        // Apply status filter if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Get the filtered data
        $data = $query->get();

        // Return the data as JSON
        return response()->json(['data' => $data]);
    }


}
