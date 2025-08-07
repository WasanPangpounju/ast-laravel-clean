<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabricouts', function (Blueprint $table) {
            $table->id();
            
            $table->string('createDate')->nullable();
            $table->string('no' , 100)->nullable();
            $table->string('refId' , 100)->nullable();
            $table->string('emp' , 100);
            $table->string('customerName' , 100);
            $table->string('receiveName' , 100);
            $table->string('fabricStruct')->nullable();
            $table->string('fold')->nullable();
            $table->string('sumYard')->nullable();
            $table->string('comment')->nullable();
            $table->string('receiveType')->nullable();

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
        Schema::dropIfExists('fabricouts');
    }
}
