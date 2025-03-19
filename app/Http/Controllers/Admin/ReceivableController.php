<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Receivable;
use App\Models\ReceivableTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
    public function index()
    {
        $receivables = Receivable::with('user','dealer')->get();
        return view('admin.receivables.index', compact('receivables'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $receivables = Receivable::where('user_id', $id)->with('receivableTransactions')->get();
        return view('admin.receivables.show', compact('user', 'receivables'));
    }

    public function edit($id)
    {
        $receivable = Receivable::findOrFail($id);
        return view('admin.receivables.edit', compact('receivable'));
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
            return back()->withErrors(['payment_amount' => 'Payment amount exceeds remaining amount']);
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

        return redirect()->route('receivables.index')->with('success', 'Payment recorded successfully');
    }
}

