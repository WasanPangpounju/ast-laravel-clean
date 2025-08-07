<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHtrpackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('htrpackages', function (Blueprint $table) {
            $table->id();
            $table->string('emp');
            $table->string('supplier_name');
            $table->string('spool');
            $table->string('sack');
            $table->string('box');
            $table->string('pallet');
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
        Schema::dropIfExists('htrpackages');
    }
}
