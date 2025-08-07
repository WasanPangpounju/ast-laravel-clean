<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->string('refId', 100);
            $table->string('emp', 100);
            $table->string('createDate', 100);
            $table->string('customerName', 100);
            $table->string('coname', 100);
            $table->string('fabricId', 100);
            $table->string('fabricPattern');
            $table->longText('fabricStructure');
            $table->string('yarn_h_count');
            $table->string('fabric_w');
            $table->string('orderSumYard' , 100);
            $table->string('orderSumM' , 100);
            $table->string('typrtag' , 100);
            $table->string('fabricSPY' , 100);
            $table->string('fabricSpP' , 100);
            $table->string('typemachine' , 100);
            $table->string('machinenumber' , 100);
            $table->string('purchaseOrder' , 100);
            $table->string('po' , 100);
            $table->longText('comment');
            $table->longText('comment2');
            $table->string('payment' , 100);
            
            $table->string('yarnHType1' , 100);
            $table->string('subNameH1' , 100);
            $table->string('yarnHCount1' , 100);
            $table->string('yarnHRatio1' , 100);
            
            $table->string('yarnHType2' , 100);
            $table->string('subNameH2' , 100);
            $table->string('yarnHCount2' , 100);
            
            $table->string('yarnWType1' , 100);
            $table->string('subNameW1' , 100);
            $table->string('yarnWCount1' , 100);
            $table->string('yarnWType2' , 100);
            $table->string('subNameW2' , 100);
            $table->string('yarnWCount2' , 100);
            $table->string('yarnWType3' , 100);
            $table->string('subNameW3' , 100);
            $table->string('yarnWCount3' , 100);
            $table->string('yarnWType4' , 100);
            $table->string('subNameW4' , 100);
            $table->string('yarnWCount4' , 100);
                        
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
        Schema::dropIfExists('productions');
    }
}
