<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\AppSetting;


class OrderController extends Controller
{

   // For Game Order Only
      public function indexOfGame()
    {
        // Fetch orders with their related VoucherProductDetails
        $data = Order::whereNotNull('number_of_game')->with('binNumber')->paginate(PAGINATION_COUNT);

        return view('admin.orders.games', ['data' => $data]);
    }

    public function charge($id)
    {
        $order = Order::findOrFail($id);
        if ($order->order_status == 3) {
            $order->order_status = 1;
            $order->save();

            return redirect()->back()->with('message', 'Order status updated to 1 (charged)');
        }

        return redirect()->back()->with('error', 'Order status could not be updated');
    }



    public function sendNotificationToUser(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id); // Throws exception if user not found

        // Notification logic
        $title = $request->title;
        $body = $request->body;
        $type = 'FAILED';  // Assuming 'FAILED' as default notification type
        $order_id = 1;     // Assuming order_id = 1, modify this as per your logic

        // Send push notification and capture the response
        $response = AppSetting::push_notification($user->fcm_token, $title, $body, $type, $order_id);

        // Save the notification to the database
        $notification = new Notification([
            'title' => $title,
            'body' => $body,
            'user_id' => $user->id,  // Associate notification with the user
        ]);
        $notification->save();

        // Check if the notification was sent successfully
        if ($response) {
            return redirect()->back()->with('message', 'Notification sent to user');
        } else {
            return redirect()->back()->with('error', 'Notification was not sent');
        }
    }





  /// For Itesalat
      public function index()
    {
        // Fetch orders with their related VoucherProductDetails
        $data = Order::whereNull('number_of_game')->with(['voucherProductDetails','binNumber'])->paginate(PAGINATION_COUNT);

        return view('admin.orders.index', ['data' => $data]);
    }

    public function edit($id)
    {
        if (auth()->user()->can('order-edit')) {
            $data = Order::findorFail($id);
            return view('admin.orders.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $order = Order::findorFail($id);
        try {

            $order->status = $request->get('status');

            if ($order->save()) {
                return redirect()->route('orders.index')->with(['success' => 'Order update']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function delete($id)
    {
        try {

            $item_row = Order::select("id")->where('id', '=', $id)->first();

            if (!empty($item_row)) {

                $flag = Order::where('id', '=', $id)->delete();;

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

