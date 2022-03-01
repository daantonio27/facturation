<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCanceledIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canceled_incomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('payment_method_id');
            $table->BigInteger('amount');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('canceled_incomes');
    }
}
