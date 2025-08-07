<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialOutsidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_outsides', function (Blueprint $table) {
            $table->id();
            $table->string('emp', 100);
$table->string('supplierName', 100);
$table->string('createDate');
$table->longText('yarnType');
$table->string('lot', 100);
$table->string('pallet', 100);
$table->string('box', 100);
$table->string('sack', 100);
$table->string('spool', 100);
$table->string('weight_p_sum', 100);
$table->string('weight_kg_sum', 100);
$table->string('weight_p_package', 100);
$table->string('weight_kg_package', 100);
$table->string('weight_p_net', 100);
$table->string('weight_kg_net', 100);
$table->string('average_p', 100);
$table->string('average_kg', 100);
$table->string('recipient', 100);
$table->string('comment', 100);
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
        Schema::dropIfExists('material_outsides');
    }
}
