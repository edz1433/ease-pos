@extends('layouts.master')

@section('body')
<div class="container-fluid py-4">

    <div class="row g-4">

        <!-- Cash Count Form -->
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-coins me-2"></i> Cash Count Entry</h4>
                </div>
                <div class="card-body">
                    <form id="cashCountForm" method="POST" action="{{ route('cashCountCreate') }}">
                        @csrf

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover align-middle text-center mb-3">
                                <thead class="table-light">
                                    <tr>
                                        <th>Denomination</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach([0.25,0.50,1,5,10,20,50,100,500,1000] as $denom)
                                    <tr>
                                        <td class="fw-bold align-middle">₱{{ number_format($denom, 2) }}</td>
                                        <td class="align-middle">
                                            <input type="number" 
                                                name="qty_{{ str_replace('.','_',$denom) }}" 
                                                id="qty_{{ str_replace('.','_',$denom) }}" 
                                                min="0" 
                                                class="form-control form-control-sm text-center">
                                        </td>
                                        <td class="text-end fw-bold align-middle">₱<span id="subtotal_{{ str_replace('.','_',$denom) }}">0.00</span></td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td class="fw-bold align-middle" colspan="2"><b>GCASH</b></td>
                                        <td class="fw-bold align-middle"><input type="number" name="gcash" id="gcash" class="form-control form-control-sm text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold align-middle" colspan="2"><b>BANK</b></td>
                                        <td class="fw-bold align-middle"><input type="number" name="bank" id="bank" class="form-control form-control-sm text-center"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Hidden system values -->
                        <input type="hidden" name="total_inflow" value="{{ $totalCashInflow->sum('amount') ?? 0 }}">
                        <input type="hidden" name="total_outflow" value="{{ $totalCashOutflow->sum('amount') ?? 0 }}">
                        <input type="hidden" name="total_purchases" value="{{ $totalPurchases ?? 0 }}">
                        <input type="hidden" name="total_sales_today" value="{{ $totalSalesToday ?? 0 }}">

                        <!-- This will be set dynamically by JS -->
                        <input type="hidden" name="variance" id="variance_field">

                        <button type="submit" class="btn bg-main-7 btn-sm text-light w-100">
                            <i class="fas fa-save me-2"></i> Save Cash Count
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i> Summary</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Sales Today -->
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Total Sales Today:</span>
                        <span class="text-primary">₱{{ number_format($totalSalesToday, 2) }}</span>
                    </div>

                    <!-- INFLOW Section -->
                    <h6 class="fw-bold text-success mb-2"><i class="fas fa-arrow-down me-2"></i> INFLOW</h6>
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($totalCashInflow as $data)
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>{{ $data->transaction_type }}{{ isset($data->description) ? ' ('.$data->description.')' : '' }}</span>
                                <span class="fw-semibold">₱{{ number_format($data->amount, 2) }}</span>
                            </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between fw-bold text-success px-0">
                            <span>Total Inflow</span>
                            <span>₱<span id="total_inflow">{{ number_format($totalCashInflow->sum('amount') ?? 0, 2) }}</span></span>
                        </li>
                    </ul>

                    <!-- OUTFLOW Section -->
                    <h6 class="fw-bold text-danger mb-2"><i class="fas fa-arrow-up me-2"></i> OUTFLOW</h6>
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($totalCashOutflow as $data)
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>{{ $data->transaction_type }}{{ isset($data->description) ? ' ('.$data->description.')' : '' }}</span>
                                <span class="fw-semibold">₱{{ number_format($data->amount, 2) }}</span>
                            </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Total Purchases</span>
                            <span class="fw-semibold">₱{{ number_format($totalPurchases ?? 0, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between fw-bold text-danger px-0">
                            <span>Total Outflow + Purchases</span>
                            <span>₱<span id="total_outflow">{{ number_format(($totalCashOutflow->sum('amount') ?? 0) + ($totalPurchases ?? 0), 2) }}</span></span>
                        </li>
                    </ul>

                    <hr>

                    <!-- Totals & Variance -->
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold text-success">Total Cash Counted:</span>
                        <span class="fw-bold text-success">₱<span id="total_cash">0.00</span></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Variance:</span>
                        <span class="fw-bold">₱<span id="variance">0.00</span></span>
                    </div>

                    <!-- Status Badge -->
                    <div class="mt-3 text-center">
                        <span id="statusBadge" class="badge bg-secondary fs-6 px-3 py-2">Waiting...</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    // Denominations
    const denominations = [0.25, 0.50, 1, 5, 10, 20, 50, 100, 500, 1000];

    // Totals from Blade variables
    const totalInflow = parseFloat("{{ $totalCashInflow->sum('amount') ?? 0 }}");
    const totalOutflow = parseFloat("{{ $totalCashOutflow->sum('amount') ?? 0 }}");
    const totalPurchases = parseFloat("{{ $totalPurchases ?? 0 }}");
    const totalSalesToday = parseFloat("{{ $totalSalesToday ?? 0 }}");

    // Format number as Philippine Peso
    function formatPeso(amount) {
        return amount.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Main calculation
    function calculateTotalCash() {
        let cashTotal = 0;

        // Loop through denominations
        denominations.forEach(val => {
            let inputId = 'qty_' + val.toString().replace('.', '_');
            let input = document.getElementById(inputId);
            if (!input) return;

            let qty = parseFloat(input.value) || 0;
            let subtotal = qty * val;
            cashTotal += subtotal;

            // Update subtotal cell
            let subtotalElem = document.getElementById('subtotal_' + val.toString().replace('.', '_'));
            if (subtotalElem) subtotalElem.textContent = formatPeso(subtotal);
        });

        // Add GCASH + BANK
        let gcash = parseFloat(document.getElementById('gcash')?.value) || 0;
        let bank  = parseFloat(document.getElementById('bank')?.value) || 0;
        let totalAvailable = cashTotal + gcash + bank;

        // Update totals
        document.getElementById('total_cash').textContent = formatPeso(totalAvailable);

        // ✅ Expected cash should include total sales
        let expectedCash = totalSalesToday + totalInflow - totalOutflow - totalPurchases;
        let variance = totalAvailable - expectedCash;
        document.getElementById('variance').textContent = formatPeso(variance);

        // Update status badge
        let badge = document.getElementById('statusBadge');
        badge.className = "badge fs-6 px-3 py-2";

        if (variance === 0) {
            badge.textContent = "Balanced (Matches system)";
            badge.classList.add("bg-success");
        } else if (variance < 0) {
            badge.textContent = "Shortage (Under by ₱" + formatPeso(Math.abs(variance)) + ")";
            badge.classList.add("bg-danger");
        } else {
            badge.textContent = "Overage (Over by ₱" + formatPeso(variance) + ")";
            badge.classList.add("bg-primary");
        }

        document.getElementById('variance_field').value = (variance === 0 ? 0 : variance.toFixed(2));
    }

    // Attach event listeners to denominations
    denominations.forEach(val => {
        let inputId = 'qty_' + val.toString().replace('.', '_');
        let input = document.getElementById(inputId);
        if (input) input.addEventListener('input', calculateTotalCash);
    });

    // Attach event listeners to gcash and bank
    document.getElementById('gcash')?.addEventListener('input', calculateTotalCash);
    document.getElementById('bank')?.addEventListener('input', calculateTotalCash);

    // Initial calculation
    calculateTotalCash();
</script>


@endsection
