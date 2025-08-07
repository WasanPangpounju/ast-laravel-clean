<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAstPurchaseordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ast_purchaseorders', function (Blueprint $table) {
            $table->id();
$table->string('emp', 100);
$table->string('createDate', 100);
$table->string('customerName', 100);
$table->string('fabricId', 100);
$table->string('fabricPattern');
$table->longText('fabricStructure');
$table->string('orderSumYard' , 100);
$table->string('orderSumM' , 100);
$table->string('fabricSPY' , 100);
$table->string('fabricSpP' , 100);
$table->string('priceYard' , 100);
$table->string('priceM' , 100);
$table->string('discountP' , 100);
$table->string('discountYard' , 100);
$table->string('commission' , 100);
$table->string('vat' , 100);
$table->string('purchaseOrder' , 100);
$table->string('po' , 100);
$table->string('deadline');
$table->longText('comment');
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
        Schema::dropIfExists('ast_purchaseorders');
    }
}
