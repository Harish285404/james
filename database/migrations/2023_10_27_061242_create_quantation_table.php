<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuantationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quantation', function (Blueprint $table) {
                         $table->id();
                        $table->string('name');
                        $table->string('bussiness_name')->nullable();
                        $table->string('email');
                        $table->string('address')->nullable();
                        $table->string('phone_number')->nullable();
                        $table->string('discount')->nullable();
                        $table->string('freight')->nullable();
                        $table->string('notes')->nullable();
                        $table->string('quote_id')->nullable();
                        $table->string('parent_key')->default(0);
                        $table->string('product_id')->nullable();
                        $table->string('product_name')->nullable();
                        $table->string('product_price')->nullable();
                        $table->string('product_quantity')->nullable();
                        $table->string('total')->nullable();
                        $table->string('grand_total')->nullable();
                        $table->string('misc_item')->nullable();
                        $table->string('misc_value')->nullable();
                        $table->string('date')->nullable();
                        $table->string('status')->default(0);
                        $table->string('reason')->nullable();
                        $table->string('pdf')->nullable();
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
        Schema::dropIfExists('quantation');
    }
}
