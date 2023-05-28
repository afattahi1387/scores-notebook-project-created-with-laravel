<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('roll_call_id');
            $table->foreign('roll_call_id')->references('id')->on('roll_calls')->onDelete('cascade');
            $table->unsignedBigInteger('learner_id');
            $table->foreign('learner_id')->references('id')->on('learners')->onDelete('cascade');
            $table->string('roll_call');
            $table->integer('score')->nullable();
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('student_attendances');
    }
}
