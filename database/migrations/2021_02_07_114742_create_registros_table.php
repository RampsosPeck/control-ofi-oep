<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->date('fecha');
            $table->unsignedBigInteger('horario_id');
            $table->time('llegadam')->nullable();
            $table->time('retirom')->nullable();
            $table->time('llegadat')->nullable();
            $table->time('retirot')->nullable();
            $table->time('atraso')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('horario_id')->references('id')->on('horarios');

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
        Schema::dropIfExists('registros');
    }
}
