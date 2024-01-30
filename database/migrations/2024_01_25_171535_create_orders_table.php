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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('user_id');
            $table->string('email');
            $table->string('contact');
            $table->mediumText('address');
            $table->string('products');
            $table->string('orderID')->nullable();
            $table->string('payerID')->nullable();
            $table->string('paymentID')->nullable();
            $table->mediumText('facilitatorAccessToken')->nullable();
            $table->string('paymentSource')->nullable();
            $table->string('status');
            $table->string('total');
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
        Schema::dropIfExists('order');
    }
};
