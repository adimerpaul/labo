<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntibioticoInmunologiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antibiotico_inmunologia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inmunologia_id');
            $table->foreign('inmunologia_id')->references('id')->on('inmunologias');
            $table->unsignedBigInteger('antibiotico_id');
            $table->foreign('antibiotico_id')->references('id')->on('antibioticos');
            $table->string('resultado')->nullable()->default('');
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
        Schema::dropIfExists('antibiotico_inmunologia');
    }
}
