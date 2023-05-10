<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorios', function (Blueprint $table) {
            $table->id();
            $table->string('tipomuestra')->nullable()->default('');
            $table->date('fechatoma')->nullable();
            $table->time('horatoma')->nullable();
            $table->date('fechaimp')->nullable();
            $table->string('responsable')->default('');
            $table->text('d1')->nullable()->default('');
            $table->text('d2')->nullable()->default('');
            $table->text('d3')->nullable()->default('');
            $table->text('d4')->nullable()->default('');
            $table->text('d5')->nullable()->default('');
            $table->text('d6')->nullable()->default('');
            $table->text('d7')->nullable()->default('');
            $table->text('d8')->nullable()->default('');
            $table->text('d9')->nullable()->default('');
            $table->text('d10')->nullable()->default('');
            $table->text('d11')->nullable()->default('');
            $table->text('d12')->nullable()->default('');
            $table->text('d13')->nullable()->default('');
            $table->text('d14')->nullable()->default('');
            $table->text('d15')->nullable()->default('');
            $table->text('d16')->nullable()->default('');
            $table->text('d17')->nullable()->default('');
            $table->text('d18')->nullable()->default('');
            $table->text('d19')->nullable()->default('');
            $table->text('d20')->nullable()->default('');
            $table->text('d21')->nullable()->default('');
            $table->text('d22')->nullable()->default('');
            $table->text('d23')->nullable()->default('');
            $table->text('d24')->nullable()->default('');
            $table->text('d25')->nullable()->default('');
            $table->text('d26')->nullable()->default('');
            $table->text('d27')->nullable()->default('');
            $table->text('d28')->nullable()->default('');
            $table->text('d29')->nullable()->default('');
            $table->text('d30')->nullable()->default('');
            $table->text('d31')->nullable()->default('');
            $table->text('d32')->nullable()->default('');
            $table->text('d33')->nullable()->default('');
            $table->text('d34')->nullable()->default('');
            $table->text('d35')->nullable()->default('');
            $table->text('d36')->nullable()->default('');
            $table->text('d37')->nullable()->default('');
            $table->text('d38')->nullable()->default('');
            $table->text('d39')->nullable()->default('');
            $table->text('d40')->nullable()->default('');
            $table->text('d41')->nullable()->default('');
            $table->text('d42')->nullable()->default('');
            $table->text('d43')->nullable()->default('');
            $table->text('d44')->nullable()->default('');
            $table->text('d45')->nullable()->default('');
            $table->text('solicitud')->nullable()->default('');
            $table->text('imagen')->nullable()->default('');
            $table->unsignedBigInteger('tipo_id');
            $table->foreign('tipo_id')->references('id')->on('tipos');
            $table->unsignedBigInteger('paciente_id');
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('doctor_id');
            $table->foreign('doctor_id')->references('id')->on('doctors');
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
        Schema::dropIfExists('laboratorios');
    }
}
