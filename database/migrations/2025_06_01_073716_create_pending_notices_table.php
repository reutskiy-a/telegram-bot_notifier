<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pending_notices', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id', 36);
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('bot_last_message_id')->unsigned()->nullable();
            $table->json('day_of_week')->nullable();
            $table->string('time', 5)->nullable();
            $table->text('text')->nullable();
            $table->string('status', 100)->nullable();
            $table->foreign('chat_id')->references('id')->on('chats');
            $table->foreign('user_id')->references('id')->on('tg_users');
            $table->unique(['chat_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_notices');
    }
};
