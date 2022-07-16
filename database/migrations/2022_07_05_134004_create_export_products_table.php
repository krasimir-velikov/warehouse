<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('amount_before');
            $table->integer('product_id');
            $table->unsignedInteger('amount');
            $table->integer('export_id');
            $table->unsignedDouble('price');
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
        Schema::dropIfExists('export_products');
    }
}
