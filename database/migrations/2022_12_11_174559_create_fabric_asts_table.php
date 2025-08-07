<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricAstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_asts', function (Blueprint $table) {
            $table->id();
            $table->string('vat' , 100);
            $table->string('purchaseOrder' , 100);
            $table->string('yarn_h_count' , 100);
            $table->string('fabric_w' , 100);
            $table->string('phewNumber' , 100);
            $table->string('phewW' , 100);
            $table->string('payment');
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
        Schema::dropIfExists('fabric_asts');
    }
}
