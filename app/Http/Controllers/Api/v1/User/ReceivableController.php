<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Receivable;
use App\Models\ReceivableTransaction;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
     public function index()
    {
        $dealerId = auth()->user()->id;
        $receivables = Receivable::where('dealer_id',$dealerId)->with('user','receivableTransactions')->get();
        return response()->json(['data'=>$receivables]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
        ]);

        $receivable = Receivable::findOrFail($id);
        $paymentAmount = $request->input('payment_amount');
        $paymentDate = $request->input('payment_date');

        if ($paymentAmount > $receivable->remaining_amount) {
            return response()->json(['message' => 'Payment amount exceeds remaining amount'], 400);
        }

        // Save the payment details to receivable_transactions table
        ReceivableTransaction::create([
            'receivable_id' => $receivable->id,
            'payment_amount' => $paymentAmount,
            'payment_date' => $paymentDate,
        ]);

        // Update the remaining amount in the receivables table
        $receivable->remaining_amount -= $paymentAmount;
        $receivable->save();

        return response()->json(['message' => 'Pay submitted successfully.'], 200);
    }


}
