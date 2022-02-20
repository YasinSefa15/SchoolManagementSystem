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
        Schema::create('lecture_to_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lecture_id');
            $table->foreign('lecture_id')->references('id')->on('lectures');
            $table->tinyText('type');
            $table->integer('percentage');
            $table->integer('average_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lecture_to_exams');
    }
};
