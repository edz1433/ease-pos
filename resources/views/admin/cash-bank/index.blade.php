@extends('layouts.master')

@section('body')
<style>
    .bg-form {
        background-color: #e9ecef;
    }
    .form-control:disabled, .form-control[readonly] {
        background-color: #ffffff;
        opacity: 1;
    }
    .form-control-sm {
        height: calc(1.5125rem + 2px);
        padding: .15rem .5rem;
        font-size: .750rem;
        line-height: 1.5;
        border-radius: .2rem;
        background-color: #ffffff !important;
    }
    .btn-sm {
        font-size: 10px !important;
        height: 25px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .bb {
        border-bottom: 1px solid rgb(145, 138, 138);
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Transaction Form Column -->
        <div class="col-lg-4 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header text-dark text-light">
                    <b>Add / Edit Transaction</b>
                </div>
                <div class="card-body bg-form">
                    <form action="{{ route('cashbankCreate') }}" method="POST" id="cashBankForm">
                        @csrf
                        <input type="hidden" name="id" id="transaction_id">

                        <div class="mb-2">
                            <label>Transaction Date</label>
                            <input type="datetime-local" name="transaction_date" id="transaction_date" 
                                class="form-control form-control-sm" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>

                        <div class="mb-2">
                            <label>Account Type</label>
                            <select name="account_type" id="account_type" class="form-control form-control-sm" required>
                                <option value="">-- Select Account Type --</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label>Account Name</label>
                            <input type="text" name="account_name" id="account_name" class="form-control form-control-sm" required>
                        </div>

                        <div class="mb-2">
                            <label>Transaction Type</label>
                            <select name="transaction_type" id="transaction_type" class="form-control form-control-sm" required>
                                <option value="">-- Select Transaction Type --</option>
                                <option value="Deposit">Deposit</option>
                                <option value="Withdrawal">Withdrawal</option>
                                <option value="Transfer">Transfer</option>
                                <option value="Expense">Expense</option>
                                <option value="Salary">Salary</option>
                                <option value="Petty Cash">Petty Cash</option>  
                            </select>
                        </div>

                        <div class="mb-2">
                            <label>Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control form-control-sm" step="0.01" min="0" required>
                        </div>

                        <div class="mb-2">
                            <label>Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control form-control-sm"></textarea>
                        </div>

                        <button type="submit" class="btn bg-main-7 text-light w-100 btn-sm" id="saveBtn">
                            <i class="fas fa-save"></i> Save Transaction
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Transaction Table Column -->
        <div class="col-lg-8 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header text-dark"><b>Transaction History</b></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example3" class="table table-bordered table-hover" id="transactionsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Account</th>
                                    <th>Transaction</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $tx)
                                    <tr>
                                        <td>{{ $tx->transaction_date->format('Y-m-d H:i') }}</td>
                                        <td>{{ $tx->account_type }}</td>
                                        <td>{{ $tx->account_name }}</td>
                                        <td>{{ $tx->transaction_type }}</td>
                                        <td class="text-end">₱{{ number_format($tx->amount, 2) }}</td>
                                        <td>{{ $tx->description }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-info btn-sm editBtn" data-id="{{ $tx->id }}"
                                                data-date="{{ $tx->transaction_date->format('Y-m-d\TH:i') }}"
                                                data-type="{{ $tx->account_type }}"
                                                data-account="{{ $tx->account_name }}"
                                                data-trans="{{ $tx->transaction_type }}"
                                                data-amount="{{ $tx->amount }}"
                                                data-desc="{{ $tx->description }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('cashbankDelete', $tx->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this transaction?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total</th>
                                    <th class="text-end">₱{{ number_format($transactions->sum('amount'),2) }}</th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Prefill form for editing
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = document.getElementById('cashBankForm');
            const txId = this.dataset.id;

            document.getElementById('transaction_id').value = txId;
            document.getElementById('transaction_date').value = this.dataset.date;
            document.getElementById('account_type').value = this.dataset.type;
            document.getElementById('account_name').value = this.dataset.account;
            document.getElementById('transaction_type').value = this.dataset.trans;
            document.getElementById('amount').value = this.dataset.amount;
            document.getElementById('description').value = this.dataset.desc;

            form.action = "{{ route('cashbankUpdate', ':id') }}".replace(':id', txId);
            form.querySelector('#saveBtn').innerHTML = '<i class="fas fa-save"></i> Update Transaction';
        });
    });
</script>
@endsection
