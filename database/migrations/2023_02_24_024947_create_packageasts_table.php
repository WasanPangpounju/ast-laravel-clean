<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packageasts', function (Blueprint $table) {
            $table->id();
            $table->string('emp');
            $table->string('ref_id');
            $table->string('supplier_name');
            $table->string('spool');
            $table->string('spool_type');
            $table->string('sack');
            $table->string('sack_type');
            $table->string('box');
            $table->string('box_type');
            $table->string('pallet');
            $table->string('pallet_type');
            $table->string('partition');
            $table->string('partition_type');
            $table->string('package_status');
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
        Schema::dropIfExists('packageasts');
    }
}
