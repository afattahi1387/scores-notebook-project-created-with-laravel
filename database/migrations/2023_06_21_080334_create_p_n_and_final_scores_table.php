<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePNAndFinalScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_n_and_final_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('relation_ship_id');
            $table->foreign('relation_ship_id')->references('id')->on('relation_ships')->onDelete('cascade');
            $table->unsignedBigInteger('learner_id');
            $table->foreign('learner_id')->references('id')->on('learners')->onDelete('cascade');
            $table->integer('first_term_PN_number')->default(0);
            $table->integer('second_term_PN_number')->default(0);
            $table->integer('first_term_development_score')->nullable();
            $table->integer('second_term_development_score')->nullable();
            $table->integer('first_term_final_score')->nullable();
            $table->integer('second_term_final_score')->nullable();
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
        Schema::dropIfExists('p_n_and_final_scores');
    }
}
