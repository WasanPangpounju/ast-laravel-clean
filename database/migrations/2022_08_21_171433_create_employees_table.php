<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('empID', 100);
            $table->string('name', 100);
            $table->string('ssn');
            $table->string('tel');
            $table->string('gender', 100);
            $table->string('berthdate', 100);
            $table->longText('address');
            $table->longText('description');
            $table->string('department', 100);
            $table->string('jobdescription', 100);
            $table->string('manager', 100);
            $table->string('createEmployeeBy', 100);
            $table->timestamp('added_on');
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
        Schema::dropIfExists('employees');
    }
}
