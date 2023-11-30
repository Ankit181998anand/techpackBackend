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
            $table->string('product_sku');
            $table->string('product_slug');
            $table->string('product_name');
            $table->string('meta_desc');
            $table->string('meta_keyword');
            $table->mediumText('short_description');
            $table->mediumText('long_description');
            $table->mediumText('addi_info');
            $table->string('product-price');
            $table->string('cat_id');
            $table->string('isActive');
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
