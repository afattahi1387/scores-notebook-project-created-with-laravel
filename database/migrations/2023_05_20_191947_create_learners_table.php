<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('row');
            $table->string('name');
            $table->unsignedBigInteger('lesson_room_id');
            $table->foreign('lesson_room_id')->references('id')->on('lesson_rooms')->onDelete('cascade');
            $table->integer('first_term_PN_number')->default(0);
            $table->integer('second_term_PN_number')->default(0);
            $table->integer('first_term_final_scores')->nullable();
            $table->integer('second_term_final_scores')->nullable();
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
        Schema::dropIfExists('learners');
    }
}
