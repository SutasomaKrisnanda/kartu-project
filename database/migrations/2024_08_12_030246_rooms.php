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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->unique();
            $table->string('status')->default('waiting'); // waiting, in progress, finished
            $table->string('visibility')->default('public'); // public, private
            $table->string('password')->nullable();
            $table->string('mode')->default('classic'); // e.g., blitz, rapid, classic
            $table->timestamps();
        });

        Schema::create('room_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('player');
            $table->string('result')->nullable(); // won, lost, draw
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_users');
        Schema::dropIfExists('rooms');
    }
};
