<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offered_lectures', function (Blueprint $table) {
            $table->tinyText('year'); //2020-2021
            $table->enum('semester',['spring','fall']);
            $table->enum('type',['semester','add-drop'])->default('semester');
            $table->dateTime('start_at')->nullable();//Y-M-D H:M:S
            $table->dateTime('end_at')->nullable();//Y-M-D H:M:S
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offered_lectures');
    }
};
