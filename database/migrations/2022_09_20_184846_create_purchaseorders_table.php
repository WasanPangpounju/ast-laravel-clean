<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaseorders', function (Blueprint $table) {
            $table->id();
$table->string('cus_id', 100);
$table->string('cus_name', 100);
$table->string('cus_co', 100);
$table->string('emp');
$table->timestamp('create_on');
$table->string('fabric_id', 100);
$table->string('fabric_pattern', 100);
$table->string('fabric_structure', 100);
$table->string('yarn_h_count', 100);
$table->string('fabric_w', 100);
$table->string('yarn_h_type1', 100);
$table->string('sub_id_h1', 100);
$table->string('sub_name_h1', 100);
$table->string('yarn_h_count1', 100);
$table->string('yarn_h_ratio1', 100);
$table->string('yarn_h_type2', 100);
$table->string('sub_id_h2', 100);
$table->string('sub_name_h2', 100);
$table->string('yarn_h_count2', 100);
$table->string('yarn_h_ratio2', 100);
$table->string('yarn_w_type1', 100);
$table->string('sub_id_w1', 100);
$table->string('sub_name_w1', 100);
$table->string('yarn_w_count1', 100);
$table->string('yarn_w_ratio1', 100);
$table->string('yarn_w_type2', 100);
$table->string('sub_id_w2', 100);
$table->string('sub_name_w2', 100);
$table->string('yarn_w_count2', 100);
$table->string('yarn_w_ratio2', 100);
$table->string('yarn_w_type3', 100);
$table->string('sub_id_w3', 100);
$table->string('sub_name_w3', 100);
$table->string('yarn_w_count3', 100);
$table->string('yarn_w_ratio3', 100);
$table->string('yarn_w_type4', 100);
$table->string('sub_id_w4', 100);
$table->string('sub_name_w4', 100);
$table->string('yarn_w_count4', 100);
$table->string('yarnWRatio4', 100);
$table->string('phew_number', 100);
$table->string('phew_w', 100);
$table->string('fabric_comment');
$table->string('machine_number', 100);
$table->string('fabric_s', 100);
$table->string('order_sum_yard', 100);
$table->string('order_sum_m', 100);
$table->string('fabric_spy', 100);
$table->string('fabric_sp_p', 100);
$table->string('price_yard', 100);
$table->string('price_m', 100);
$table->string('discount_p', 100);
$table->string('discount_yard', 100);
$table->string('commission', 100);
$table->string('vat', 100);
$table->string('purchase_order', 100);
$table->string('po', 100);
$table->longText('deadline');
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
        Schema::dropIfExists('purchaseorders');
    }
}
