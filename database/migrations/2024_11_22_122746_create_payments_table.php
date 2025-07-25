<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('lotid');
            $table->string('ownersid');
            $table->string('lot_area');
            $table->string('cheque_no')->nullable();
            $table->string('or_number')->nullable();
            $table->enum('pay_type', [1, 2, 3]);
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->string('pay_rate');
            $table->string('adj_rate')->nullable();
            $table->string('pay_amount');
            $table->decimal('discount', 15, 2)->nullable();
            $table->string('received_by');
            $table->date('paydate');
            $table->text('remarks')->nullable();
            $table->text('start_month')->nullable();
            $table->text('end_month')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
