<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCultivoAntibioticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cultivo_antibioticos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cultivo_id');
            $table->foreign('cultivo_id')->references('id')->on('cultivos');
            $table->unsignedBigInteger('antibiotico_id');
            $table->foreign('antibiotico_id')->references('id')->on('antibioticos');
            $table->string('interpretacion')->nullable()->default('')->comment("Sensible,Resistente,Intermedio");
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
        Schema::dropIfExists('cultivo_antibioticos');
    }
}
