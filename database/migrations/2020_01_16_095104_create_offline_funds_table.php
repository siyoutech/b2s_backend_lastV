<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_funds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('paiment_date');
            $table->integer('supplier_id')->unsigned()->index();
            $table->integer('shop_owner_id')->unsigned()->index()->nullable();
            $table->float('amount')->default(0);
            $table->foreign('supplier_id')
                ->references('id')
                ->on('users');

            $table->foreign('shop_owner_id')
                ->references('id')
                ->on('users');
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
        Schema::dropIfExists('offline_funds');
    }
}
