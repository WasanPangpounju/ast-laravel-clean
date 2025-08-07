<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialstoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materialstores', function (Blueprint $table) {
            $table->id();
$table->string('withdrawId', 100);
$table->string('department', 100);
$table->string('emp', 100);
$table->string('supplierName', 100);
$table->longText('yarnType');
$table->string('lot', 100);
$table->string('spool', 100);
$table->string('weight_p_net', 100);
$table->string('weight_kg_net', 100);
$table->string('average_p', 100);
$table->string('average_kg', 100);
$table->string('createDate');
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
        Schema::dropIfExists('materialstores');
    }
}
