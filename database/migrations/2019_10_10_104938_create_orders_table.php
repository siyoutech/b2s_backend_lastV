<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{

    public function up()
    {
        Schema::create('orders', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->date('order_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('delivery_type')->nullable();
            $table->float('delivery_price')->nullable();
            $table->string('delivery_adress')->nullable();
            $table->string('delivery_country')->nullable();
            $table->float('order_price');
            $table->float('commission')->nullable();
            $table->unsignedInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('users');
            $table->unsignedInteger('shop_owner_id');
            $table->foreign('shop_owner_id')->references('id')->on('users');
            // $table->unsignedInteger('sales_manager_id')->nullable();
            // $table->foreign('sales_manager_id')->references('id')->on('users');
            // Schema declaration
            // Constraints declaration

        });
    }

    public function down()
    {
        Schema::drop('orders');
    }
}
