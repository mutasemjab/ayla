<?php


namespace App\Http\Controllers\Admin;

use App\Helpers\AppSetting;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {

        $data = Transfer::paginate(PAGINATION_COUNT);

        return view('admin.transfers.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('transfer-add')) {
            $users=User::where('user_type',1)->get();
            $dealers=User::where('user_type',2)->get();
            return view('admin.transfers.create',compact('users','dealers'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }


    public function store(Request $request)
    {
        try {
            $transfer = new Transfer();

            $transfer->withdrawal = $request->get('withdrawal');
            $transfer->deposit = $request->get('deposit');
            $transfer->note = $request->get('note');

            // Determine if user or dealer is selected
            $userId = $request->get('user') ?? $request->get('dealer');
            if (!$userId) {
                return redirect()->back()->with(['error' => 'You must select either a user or a dealer.'])->withInput();
            }

            $transfer->user_id = $userId;

            if ($transfer->save()) {
                // Create a record in wallet_transactions table
                $walletTransaction = new WalletTransaction();
                $walletTransaction->wallet_id = $transfer->user->wallets->first()->id;
                $walletTransaction->note = $request->get('note');
                $walletTransaction->deposit = $request->get('deposit');
                $walletTransaction->withdrawal = abs($request->get('withdrawal'));
                $walletTransaction->admin_id = 1;
                $walletTransaction->save();

                // Update the total in the wallets table
                $wallet = Wallet::find($transfer->user->wallets->first()->id);
                if ($request->get('deposit')) {
                    $wallet->total += $request->get('deposit');
                }

                if ($request->get('withdrawal')) {
                    $wallet->total -= abs($request->get('withdrawal'));
                }
                $wallet->save();

                // Subtract the amount from the admin's wallet and create a transaction for the admin
                $adminWallet = Wallet::where('admin_id', 1)->first();
                if ($adminWallet) {
                    if ($request->get('deposit')) {
                        $adminWallet->total -= abs($request->get('deposit'));
                        $adminWallet->save();

                        // Create a record in wallet_transactions table for the admin
                        $adminWalletTransaction = new WalletTransaction();
                        $adminWalletTransaction->wallet_id = $adminWallet->id;
                        $adminWalletTransaction->note = "Admin transfer to user ID: {$userId}";
                        $adminWalletTransaction->deposit = 0;
                        $adminWalletTransaction->withdrawal = abs($request->get('deposit'));
                        $adminWalletTransaction->save();
                    }
                }

                $user = User::find($transfer->user->id); // Assuming User is the correct model for users
                if ($user) {
                    $title = 'The Admin Transfer to you';
                    $body = 'Check your Wallet.';
                    $type = $transfer->user->id; // You may need to adjust this based on your logic
                    $order_id = $transfer->user->id; // Keep the order_id same as user_id

                    // Send push notification
                    AppSetting::push_notification($user->fcm_token, $title, $body, $type, $order_id);

                    // Save the notification
                    $noti = new Notification([
                        'user_id' => $user->id,
                        'title' => $title,
                        'body' => $body,
                        'type' => $type,
                        'order_id' => $transfer->user->id,
                    ]);
                    $noti->save();
                }

                return redirect()->route('transfers.index')->with(['success' => 'Transfer created']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }


    public function edit($id)
    {
        if (auth()->user()->can('wallet-edit')) {
            $data = Transfer::findorFail($id);
            $users=User::where('user_type',1)->get();
            $dealers=User::where('user_type',2)->get();
            return view('admin.transfers.edit', compact('data','users','dealers'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
{
    $transfer = Transfer::findOrFail($id);
    try {
        $oldDeposit = $transfer->deposit;
        $oldWithdrawal = $transfer->withdrawal;

        $transfer->withdrawal = $request->get('withdrawal');
        $transfer->deposit = $request->get('deposit');
        $transfer->note = $request->get('note');

        // Determine if user or dealer is selected
        $userId = $request->get('user') ?? $request->get('dealer');
        if (!$userId) {
            return redirect()->back()->with(['error' => 'You must select either a user or a dealer.'])->withInput();
        }

        $transfer->user_id = $userId;

        if ($transfer->save()) {
            // Update the associated wallet transaction
            $walletTransaction = WalletTransaction::where('wallet_id', $transfer->user->wallets->first()->id)->firstOrFail();
            $walletTransaction->note = $request->get('note');
            $walletTransaction->deposit = $request->get('deposit');
            $walletTransaction->withdrawal = abs($request->get('withdrawal'));
            $walletTransaction->save();

            // Update the total in the wallets table
            $wallet = Wallet::find($transfer->user->wallets->first()->id);

            // Adjust the wallet total based on the difference between old and new values
            $wallet->total += ($request->get('deposit') - $oldDeposit);
            $wallet->total -= (abs($request->get('withdrawal')) - $oldWithdrawal);
            $wallet->save();

            // Adjust the admin's wallet and create a transaction for the admin
            $adminWallet = Wallet::where('admin_id', 1)->first();
            if ($adminWallet) {
                // Calculate the difference in withdrawal amounts
                $withdrawalDifference = abs($request->get('deposit')) - abs($oldDeposit);
                if ($withdrawalDifference != 0) {
                    $adminWallet->total -= $withdrawalDifference;
                    $adminWallet->save();

                    // Create a record in wallet_transactions table for the admin
                    $adminWalletTransaction = new WalletTransaction();
                    $adminWalletTransaction->wallet_id = $adminWallet->id;
                    $adminWalletTransaction->note = "Admin adjustment for transfer ID: {$id}";
                    $adminWalletTransaction->deposit = 0;
                    $adminWalletTransaction->withdrawal = $withdrawalDifference;
                    $adminWalletTransaction->save();
                }
            }

            return redirect()->route('transfers.index')->with(['success' => 'Transfer updated']);
        } else {
            return redirect()->back()->with(['error' => 'Something went wrong']);
        }
    } catch (\Exception $ex) {
        return redirect()->back()
            ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
            ->withInput();
    }
}



    public function destroy($id)
    {
        try {

            $item_row = Transfer::select("id")->where('id', '=', $id)->first();

            if (!empty($item_row)) {

                $flag = Transfer::where('id', '=', $id)->delete();;

                if ($flag) {
                    return redirect()->back()
                        ->with(['success' => '   Delete Succefully   ']);
                } else {
                    return redirect()->back()
                        ->with(['error' => '   Something Wrong']);
                }
            } else {
                return redirect()->back()
                    ->with(['error' => '   cant reach fo this data   ']);
            }
        } catch (\Exception $ex) {

            return redirect()->back()
                ->with(['error' => ' Something Wrong   ' . $ex->getMessage()]);
        }
    }
}
