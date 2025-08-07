<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricAststructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_aststructures', function (Blueprint $table) {
            $table->id();
            $table->string('vat' , 100);
            $table->string('purchaseOrder' , 100);
            $table->string('yarnHType1' , 100);
            $table->string('subNameH1' , 100);
            $table->string('yarnHCount1' , 100);
            $table->string('yarnHRatio1' , 100);
            $table->string('yarnHType2' , 100);
            $table->string('subNameH2' , 100);
            $table->string('yarnHCount2' , 100);
            $table->string('yarnHRatio2' , 100);
            $table->string('yarnWType1' , 100);
            $table->string('subNameW1' , 100);
            $table->string('yarnWCount1' , 100);
            $table->string('yarnWRatio1' , 100);
            $table->string('yarnWType2' , 100);
            $table->string('subNameW2' , 100);
            $table->string('yarnWCount2' , 100);
            $table->string('yarnWRatio2' , 100);
            $table->string('yarnWType3' , 100);
            $table->string('subNameW3' , 100);
            $table->string('yarnWCount3' , 100);
            $table->string('yarnWRatio3' , 100);
            $table->string('yarnWType4' , 100);
            $table->string('subNameW4' , 100);
            $table->string('yarnWCount4' , 100);
            $table->string('yarnWRatio4' , 100);
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
        Schema::dropIfExists('fabric_aststructures');
    }
}
