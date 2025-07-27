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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique();
            $table->string('product_name');
            $table->string('product_type');
            $table->string('category');
            $table->integer('packaging')->default(1);
            $table->decimal('w_capital', 10, 2)->default(0.00);
            $table->decimal('w_price', 10, 2)->default(0.00);
            $table->string('w_unit');
            $table->decimal('r_capital', 10, 2)->default(0.00);
            $table->decimal('r_price', 10, 2)->default(0.00);
            $table->string('r_unit');
            $table->string('image')->nullable(); // storing path, use VARCHAR
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
        Schema::dropIfExists('products');
    }
};
