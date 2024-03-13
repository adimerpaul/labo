<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntibioticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antibioticos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable()->default('');
            $table->string('unidad')->nullable()->default('');
            $table->double('rangoMin')->nullable()->default('');
            $table->double('rangoMax')->nullable()->default('');
            $table->text('referencia')->nullable()->default('');
            $table->text('tipo')->nullable()->default('')->comment("'CULTIVO','INMUNOLOGIA'");
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
        Schema::dropIfExists('antibioticos');
    }
}
