<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAstpackageoutsidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('astpackageoutsides', function (Blueprint $table) {
            $table->id();
            $table->string('receiver')->nullable();            
            $table->string('emp');
            $table->string('ref_id')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('spool')->nullable();
            $table->string('spool_type')->nullable();
            $table->string('sack')->nullable();
            $table->string('sack_type')->nullable();
            $table->string('box')->nullable();
            $table->string('box_type')->nullable();
            $table->string('pallet')->nullable();
            $table->string('pallet_type')->nullable();
            $table->string('partition')->nullable();
            $table->string('partition_type')->nullable();
            $table->string('package_status')->nullable();
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
        Schema::dropIfExists('astpackageoutsides');
    }
}
