<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_class_id');
            $table->unsignedBigInteger('student_id');
            // Ratings 1-5
            $table->tinyInteger('teaching_rating')->unsigned();
            $table->tinyInteger('support_rating')->unsigned();
            $table->tinyInteger('material_rating')->unsigned();
            
            $table->text('content')->nullable();
            $table->boolean('is_anonymous')->default(true);
            
            $table->timestamps();

            // Foreign keys
            $table->foreign('course_class_id')->references('id')->on('course_classes')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            
            // Unique evaluation per student per class
            $table->unique(['course_class_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_evaluations');
    }
};