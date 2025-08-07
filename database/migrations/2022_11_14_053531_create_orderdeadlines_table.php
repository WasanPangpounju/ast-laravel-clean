<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderdeadlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderdeadlines', function (Blueprint $table) {
            $table->id();
            $table->string('purchaseOrder', 100);
            $table->string('round', 100);
            $table->string('dt', 100);
            $table->string('ordery', 100);
            $table->string('orderp', 100);
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
        Schema::dropIfExists('orderdeadlines');
    }
}
