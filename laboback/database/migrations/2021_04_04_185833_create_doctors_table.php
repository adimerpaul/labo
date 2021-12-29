<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('ci')->nullable();
            $table->string('nombre');
            $table->string('paterno')->nullable();
            $table->string('materno')->nullable();
            $table->string('especialidad')->nullable();
            $table->string('matricula')->nullable();
            $table->string('celular')->nullable();
            $table->tinyInteger('activo')->default(1);
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
        Schema::dropIfExists('doctors');
    }
}
