<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message'); // User's message
            $table->text('response'); // Bot's response
            $table->string('intent')->nullable(); // grade_query, schedule_query, etc.
            $table->boolean('is_ai_response')->default(false); // true if from OpenAI
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_logs');
    }
};