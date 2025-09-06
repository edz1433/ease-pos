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
            'account_type' => 'required|in:Cash,Bank',
            'account_name' => 'required|string|max:100',
            'transaction_type' => 'required|in:Deposit,Withdrawal,Transfer,Expense,Salary,Petty Cash',
            'amount' => 'required|numeric|min:0',
        ]);
        
        CashBankTransaction::create([
            'transaction_date' => $request->transaction_date,
            'account_type' => $request->account_type,
            'account_name' => $request->account_name,
            'transaction_type' => $request->transaction_type,
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
            'account_type' => 'required|in:Cash,Bank',
            'account_name' => 'required|string|max:100',
            'transaction_type' => 'required|in:Deposit,Withdrawal,Transfer,Expense,Salary,Petty Cash',
            'amount' => 'required|numeric|min:0',
        ]);

        $transaction->update([
            'transaction_date' => $request->transaction_date,
            'account_type' => $request->account_type,
            'account_name' => $request->account_name,
            'transaction_type' => $request->transaction_type,
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
