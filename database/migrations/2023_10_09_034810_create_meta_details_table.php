<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetaDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_details', function (Blueprint $table) {
            $table->id();
            $table->text('product_id')->nullable(true);
            $table->string('p_id')->nullable(true);
            $table->string('c_id')->nullable(true);
            $table->text('category_id')->nullable(true);
            $table->text('store_id')->nullable(true);
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
        Schema::dropIfExists('meta_details');
    }
}
