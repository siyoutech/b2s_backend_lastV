<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositesiyousTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depositesiyous', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('amount');
            $table->integer('supplier_id')->unsigned()->index();
            $table->foreign('supplier_id')
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
        Schema::dropIfExists('depositesiyous');
    }
}
