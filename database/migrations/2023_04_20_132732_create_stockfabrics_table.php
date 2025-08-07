<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockfabricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockfabrics', function (Blueprint $table) {
            $table->id();
            $table->string('refId' , 100);
            $table->string('emp' , 100);
            $table->string('fabricStruct')->nullable();
            $table->string('fabricW')->nullable();
            $table->string('fold')->nullable();
            $table->string('sumYard')->nullable();
            $table->string('createDate')->nullable();
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
        Schema::dropIfExists('stockfabrics');
    }
}
