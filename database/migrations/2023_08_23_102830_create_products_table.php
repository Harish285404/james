<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->string('category_id');
            $table->string('title');
            $table->string('product_id')->nullable();
            $table->string('product_type')->nullable();
            $table->string('bundle_child_id')->nullable();
            $table->string('store')->nullable();
            $table->string('image');
            $table->string('sku');
            $table->string('product_quantity')->nullable();
            $table->string('cost_price')->nullable();
            $table->string('varient')->nullable();
            $table->string('stock_quantity')->default('0');
            $table->string('sales_price')->nullable();
            $table->string('sales_from')->nullable();
            $table->string('sales_to')->nullable();
            $table->string('retail_price')->nullable();
             $table->string('current_adjustment')->default('0');
            $table->string('adjustment_stock')->default('0');
            $table->string('current_quantity')->default('0');
            $table->string('current_stock')->default('0');
            $table->string('total_quantity')->default('0');
            $table->text('description')->nullable();
            $table->string('status');
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
}
