<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerPayment;

class CustomerController extends Controller
{
    public function customerCreate(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255|unique:customers,email',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'amount_credited' => 'required|numeric|min:0',
        ]);

        $validated['branch_id'] = env('BRANCH_ID');

        $customer = Customer::create($validated);

        return redirect()->back()->with('success', 'Customer created successfully!');
    }

    public function customerList()
    {
        $customers = Customer::with('payments')->get();

        return view('admin.customers.index', compact('customers'));
    }

    public function customerUpdate(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255|unique:customers,email,' . $id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'amount_credited' => 'required|numeric|min:0',
        ]);

        $customer->update($validated);

        return redirect()->back()->with('success', 'Customer updated successfully!');
    }

    public function customerPayment(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_amount' => [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) use ($request) {
                    $customer = Customer::findOrFail($request->input('customer_id'));
                    if ($value > $customer->amount_credited) {
                        $fail('The payment amount cannot exceed the customer\'s credited amount of ₱' . number_format($customer->amount_credited, 2));
                    }
                },
            ],
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);

        // Create payment record
        CustomerPayment::create([
            'customer_id' => $validated['customer_id'],
            'amount' => $validated['payment_amount'],
            'branch_id' => env('BRANCH_ID'),
        ]);

        // Update customer's total payments and balance
        $customer->amount_credited = round($customer->amount_credited - $validated['payment_amount'], 2);
        $customer->save();
        

        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }

    public function customerPaymentsHistory($customerId)
    {
        $payments = CustomerPayment::where('customer_id', $customerId)
            ->select('created_at', 'amount')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['payments' => $payments]);
    }
}