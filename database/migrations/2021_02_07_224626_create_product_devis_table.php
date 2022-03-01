<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDevisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_devis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('devis_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty');
            $table->BigInteger('price');
            $table->BigInteger('total_amount');
            $table->timestamps();
            $table->foreign('devis_id')->references('id')->on('devis')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_devis');
    }
}
