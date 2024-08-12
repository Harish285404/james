<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('line_item_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('sku')->nullable();
            $table->string('store')->nullable();
            $table->string('quantity')->nullable();
            $table->string('subtotal')->nullable();     
            $table->string('transaction_id')->nullable();
            $table->string('order_key')->nullable();
            $table->string('date_created')->nullable();
            $table->string('status')->nullable();
             $table->string('url')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
