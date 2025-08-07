<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdershippedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordershippeds', function (Blueprint $table) {
            $table->id();
            $table->string('refId' , 100);
            $table->string('emp' , 100);
            $table->string('staff' , 100)->nullable();
            $table->string('customerName' , 100)->nullable();
            $table->string('exceptName' , 100)->nullable();
            $table->string('orderId' , 100);
            $table->string('createDate' , 100);
            $table->string('fabricId')->nullable();
            $table->string('fabricStruct')->nullable();
            $table->string('fold')->nullable();
            $table->string('sumYard')->nullable();
            $table->string('sumM')->nullable();
            $table->string('fabricW')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('ordershippeds');
    }
}
