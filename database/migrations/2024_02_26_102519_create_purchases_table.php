<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
                        $table->id();
                        $table->string('purchase_from')->nullable();
                        $table->string('invoice_id');
                        $table->string('product_id')->nullable();
                        $table->string('product_sku')->nullable();
                        $table->string('product_quantity')->nullable();
                        $table->string('cost_price')->nullable();
                        $table->string('date')->nullable();
                        $table->string('status')->default(0);
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
        Schema::dropIfExists('purchases');
    }
}
