<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->unsignedDouble('price');
            $table->integer('supplier_id');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('accountant_id')->nullable();
            $table->integer('status')->default('1'); // 0 - deleted; 1 - not payed; 2 - payed.
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
        Schema::dropIfExists('imports');
    }
}
