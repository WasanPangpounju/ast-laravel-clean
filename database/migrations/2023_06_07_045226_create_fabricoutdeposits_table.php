<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricoutdepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabricoutdeposits', function (Blueprint $table) {
            $table->id();

            $table->string('createDate')->nullable();
            $table->string('no' , 100)->nullable();
            $table->string('refId' , 100)->nullable();
            $table->string('emp' , 100);
            $table->string('customerName' , 100);
            $table->string('receiveName' , 100);
            $table->string('fabricStruct')->nullable();
            $table->string('fabricPattern')->nullable();
            $table->string('fabricW')->nullable();
            $table->string('fold')->nullable();
            $table->string('sumYard')->nullable();
            $table->string('comment')->nullable();
            $table->string('receiveType')->nullable();
            $table->string('orderId')->nullable();
            $table->string('vatType')->nullable();
            $table->string('customerReplace')->nullable();
            $table->string('fabricStructReplace')->nullable();
            $table->string('vatNo')->nullable();
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
        Schema::dropIfExists('fabricoutdeposits');
    }
}
