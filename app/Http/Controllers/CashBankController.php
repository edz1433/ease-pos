<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashBankTransaction;
use Auth;

class CashBankController extends Controller
{
    public function cashbankCreate(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'transaction_type' => 'required|in:Sales Deposit,Petty Cash,Transfer In,Cash Withdrawal,Operating Expense,Salary & Wages,Petty Cash Expense,Transfer Out',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|in:1,2',
        ]);
        
        CashBankTransaction::create([
            'transaction_date' => $request->transaction_date,
            'transaction_type' => $request->transaction_type,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('cashbankRead')->with('success', 'Transaction added successfully.');
    }

    public function cashbankEdit($id)
    {
        $transaction = CashBankTransaction::findOrFail($id);
        $transactions = CashBankTransaction::orderByDesc('transaction_date')->get();

        return view('cashbank.index', compact('transaction', 'transactions'));
    }

    public function cashbankUpdate(Request $request, $id)
    {
        $transaction = CashBankTransaction::findOrFail($id);

        $request->validate([
            'transaction_date' => 'required|date',
            'transaction_type' => 'required|in:Sales Deposit,Petty Cash,Transfer In,Cash Withdrawal,Operating Expense,Salary & Wages,Petty Cash Expense,Transfer Out',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|in:1,2',
        ]);

        $transaction->update([
            'transaction_date' => $request->transaction_date,
            'category' => $request->category,
            'transaction_type' => $request->transaction_type,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return redirect()->route('cashbankRead')->with('success', 'Transaction updated successfully.');
    }

    public function cashbankDelete($id)
    {
        $transaction = CashBankTransaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('cashbankRead')->with('success', 'Transaction deleted successfully.');
    }
}
