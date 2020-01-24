<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateProductOrderTable extends Migration
{

    public function up()
    {
        Schema::create('product_order', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('quantity')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();
            $table->integer('order_id')->unsigned()->index();
            $table->foreign('product_id')
                ->references('id')
                ->on('products');
            $table->foreign('order_id')
                ->references('id')
                ->on('orders');
            // Schema declaration
            // Constraints declaration

        });
    }

    public function down()
    {
        Schema::drop('product_order');
    }
}
