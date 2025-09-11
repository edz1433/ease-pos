<!-- Print Section (Hidden by default) -->
<div id="printSection" class="d-none">
    <div class="container p-4">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2>Cash Count Report</h2>
                <p class="mb-1">Date: <?php echo e(date('F j, Y')); ?></p>
                <p>Generated at: <?php echo e(date('g:i A')); ?></p>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card summary-card">
                    <div class="card-body">
                        <h5 class="card-title">Cash Count Details</h5>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Denomination</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="printDenominations">
                                <!-- Populated by JS -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total Cash</th>
                                    <th id="printTotalCash">₱0.00</th>
                                </tr>
                                <tr>
                                    <th colspan="2">GCASH</th>
                                    <th id="printGcash">₱0.00</th>
                                </tr>
                                <tr>
                                    <th colspan="2">BANK</th>
                                    <th id="printBank">₱0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card summary-card">
                    <div class="card-body">
                        <h5 class="card-title">Summary</h5>
                        <table class="table table-sm">
                            <tr>
                                <td>Total Sales</td>
                                <td id="printSales">₱0.00</td>
                            </tr>
                            <tr>
                                <td>Total Inflow</td>
                                <td id="printInflow">₱0.00</td>
                            </tr>
                            <tr>
                                <td>Total Outflow + Purchases</td>
                                <td id="printOutflow">₱0.00</td>
                            </tr>
                            <tr class="table-primary">
                                <td>Expected Cash</td>
                                <td id="printExpected">₱0.00</td>
                            </tr>
                            <tr class="table-info">
                                <td>Actual Cash Counted</td>
                                <td id="printActual">₱0.00</td>
                            </tr>
                            <tr>
                                <td>Variance</td>
                                <td id="printVariance">₱0.00</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td id="printStatus">Waiting...</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex justify-content-start gap-5">
                    <!-- Prepared by -->
                    <div class="text-left">
                        <p class="mb-1">Prepared by:</p>
                        <div style="height: 80px; border-bottom: 1px solid #000; margin-bottom: 5px;">
                        </div>
                        <p class="fw-bold mb-0 text-uppercase" id="signatureName"><?php echo e(ucwords(auth()->user()->fname . ' ' . auth()->user()->lname)); ?></p>
                        <small class="text-muted">Authorized Personnel</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const denominations = [0.25,0.50,1,5,10,20,50,100,500,1000];
const totalInflow = parseFloat("<?php echo e($totalCashInflow->sum('amount') ?? 0); ?>");
const totalOutflow = parseFloat("<?php echo e($totalCashOutflow->sum('amount') ?? 0); ?>");
const totalPurchases = parseFloat("<?php echo e($totalPurchases ?? 0); ?>");
const totalSalesToday = parseFloat("<?php echo e($totalSalesToday ?? 0); ?>");

function formatPeso(amount){
    return amount.toLocaleString('en-PH',{minimumFractionDigits:2,maximumFractionDigits:2});
}

function calculateTotalCash(){
    let cashTotal = 0;
    denominations.forEach(val=>{
        let inputId='qty_'+val.toString().replace('.', '_');
        let input=document.getElementById(inputId);
        if(!input) return;
        let qty=parseFloat(input.value)||0;
        let subtotal=qty*val;
        cashTotal+=subtotal;
        let subtotalElem=document.getElementById('subtotal_'+val.toString().replace('.', '_'));
        if(subtotalElem) subtotalElem.textContent=formatPeso(subtotal);
    });

    let gcash=parseFloat(document.getElementById('gcash')?.value)||0;
    let bank=parseFloat(document.getElementById('bank')?.value)||0;
    let totalAvailable=cashTotal+gcash+bank;

    document.getElementById('total_cash').textContent=formatPeso(totalAvailable);

    let expectedCash=totalSalesToday+totalInflow-totalOutflow-totalPurchases;
    let variance=totalAvailable-expectedCash;
    document.getElementById('variance').textContent=formatPeso(variance);

    let badge=document.getElementById('statusBadge');
    badge.className="badge fs-6 px-3 py-2";
    if(variance===0){badge.textContent="Balanced (Matches system)";badge.classList.add("bg-success");}
    else if(variance<0){badge.textContent="Shortage (Under by ₱"+formatPeso(Math.abs(variance))+")";badge.classList.add("bg-danger");}
    else{badge.textContent="Overage (Over by ₱"+formatPeso(variance)+")";badge.classList.add("bg-primary");}

    document.getElementById('variance_field').value=variance.toFixed(2);

    updatePrintSection(cashTotal,gcash,bank,totalAvailable,expectedCash,variance);

    return {cashTotal,gcash,bank,totalAvailable,expectedCash,variance};
}

function updatePrintSection(cashTotal,gcash,bank,totalAvailable,expectedCash,variance){
    let printDenomBody=document.getElementById('printDenominations');
    printDenomBody.innerHTML='';
    denominations.forEach(val=>{
        let inputId='qty_'+val.toString().replace('.', '_');
        let input=document.getElementById(inputId);
        if(!input) return;
        let qty=parseFloat(input.value)||0;
        let subtotal=qty*val;
        if(qty>0){
            let row=document.createElement('tr');
            row.innerHTML=`<td>₱${formatPeso(val)}</td><td>${qty}</td><td>₱${formatPeso(subtotal)}</td>`;
            printDenomBody.appendChild(row);
        }
    });

    document.getElementById('printTotalCash').textContent='₱'+formatPeso(cashTotal);
    document.getElementById('printGcash').textContent='₱'+formatPeso(gcash);
    document.getElementById('printBank').textContent='₱'+formatPeso(bank);
    document.getElementById('printSales').textContent='₱'+formatPeso(totalSalesToday);
    document.getElementById('printInflow').textContent='₱'+formatPeso(totalInflow);
    document.getElementById('printOutflow').textContent='₱'+formatPeso(totalOutflow+totalPurchases);
    document.getElementById('printExpected').textContent='₱'+formatPeso(expectedCash);
    document.getElementById('printActual').textContent='₱'+formatPeso(totalAvailable);
    document.getElementById('printVariance').textContent='₱'+formatPeso(variance);

    let printStatus=document.getElementById('printStatus');
    if(variance===0){printStatus.textContent="Balanced (Matches system)";printStatus.className="text-success fw-bold";}
    else if(variance<0){printStatus.textContent="Shortage (Under by ₱"+formatPeso(Math.abs(variance))+")";printStatus.className="text-danger fw-bold";}
    else{printStatus.textContent="Overage (Over by ₱"+formatPeso(variance)+")";printStatus.className="text-primary fw-bold";}
}

function printCashCount(){
    calculateTotalCash();
    let printSection=document.getElementById('printSection');
    printSection.classList.remove('d-none');

    const style=document.createElement('style');
    style.innerHTML=`@media print{
        body *{visibility:hidden;}
        #printSection,#printSection *{visibility:visible;}
        #printSection{position:absolute;left:0;top:0;width:100%;}
        .no-print{display:none !important;}
    }`;
    document.head.appendChild(style);

    setTimeout(()=>{
        window.print();
        setTimeout(()=>{
            printSection.classList.add('d-none');
            document.head.removeChild(style);
        },300);
    },300);
}

// Auto-calc on input
denominations.forEach(val=>{
    let inputId='qty_'+val.toString().replace('.', '_');
    let input=document.getElementById(inputId);
    if(input) input.addEventListener('input',calculateTotalCash);
});
document.getElementById('gcash')?.addEventListener('input',calculateTotalCash);
document.getElementById('bank')?.addEventListener('input',calculateTotalCash);

// Save and print
document.getElementById('saveBtn').addEventListener('click',function(){
    const {variance,totalAvailable}=calculateTotalCash();
    let statusText=document.getElementById('statusBadge').textContent;

    Swal.fire({
        title:'Confirm Cash Count',
        html:`<p>Total Cash Counted: <b>₱${formatPeso(totalAvailable)}</b></p>
              <p>Variance: <b>₱${formatPeso(variance)}</b></p>
              <p>Status: <span class="${variance===0?'text-success':variance<0?'text-danger':'text-primary'}">${statusText}</span></p>
              <p>Are you sure you want to save this cash count?</p>`,
        icon:'question',
        showCancelButton:true,
        confirmButtonColor:'#1cc88a',
        cancelButtonColor:'#e74a3b',
        confirmButtonText:'Yes, save and print!',
        cancelButtonText:'Cancel'
    }).then((result)=>{
        if(result.isConfirmed){
            const formData=new FormData();
            denominations.forEach(val=>{
                let inputId='qty_'+val.toString().replace('.', '_');
                let input=document.getElementById(inputId);
                if(input) formData.append(inputId,input.value||0);
            });
            formData.append('gcash',document.getElementById('gcash').value||0);
            formData.append('bank',document.getElementById('bank').value||0);
            formData.append('total_inflow',totalInflow);
            formData.append('total_outflow',totalOutflow);
            formData.append('total_purchases',totalPurchases);
            formData.append('total_sales_today',totalSalesToday);
            formData.append('variance',variance);
            formData.append('_token','<?php echo e(csrf_token()); ?>');

            Swal.fire({
                title:'Saving Cash Count',
                text:'Please wait...',
                allowOutsideClick:false,
                didOpen:()=>{Swal.showLoading();}
            });

            fetch('<?php echo e(route('cashCountCreate')); ?>',{
                method:'POST',
                body:formData
            })
            .then(res=>res.json())
            .then(data=>{
                if(data.success){
                    Swal.fire({
                        title:'Success!',
                        text:data.message||'Cash count saved successfully',
                        icon:'success',
                        confirmButtonColor:'#1cc88a'
                    }).then(()=>{
                        calculateTotalCash();
                        setTimeout(printCashCount,300);
                    });
                } else {
                    Swal.fire({title:'Error!',text:data.message||'Failed to save cash count',icon:'error',confirmButtonColor:'#e74a3b'});
                }
            })
            .catch(err=>{
                console.error(err);
                Swal.fire({title:'Error!',text:'An error occurred while saving the cash count',icon:'error',confirmButtonColor:'#e74a3b'});
            });
        }
    });
});

document.getElementById('printBtn').addEventListener('click',()=>{calculateTotalCash(); printCashCount();});

// Initial calculation
calculateTotalCash();
</script>
<?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/script/cashCountScript.blade.php ENDPATH**/ ?>