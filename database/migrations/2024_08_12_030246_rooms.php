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
            $table->string('status')->default('waiting');
            $table->string('visibility')->default('public');
            $table->string('password')->nullable();
            $table->string('mode')->default('classic');
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->foreign('winner_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('room_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('player');
            $table->timestamps();
        });

        Schema::create('room_user_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('room_user_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('hp')->default(8);
            $table->string('effect')->nullable();
            $table->json('cooldown')->nullable();
            $table->timestamps();

        });

        Schema::create('room_moves', function (Blueprint $table){
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('move_data')->nullable();
            $table->foreign('move_data')->references('id')->on('items')->onDelete('set null');
            $table->boolean('success')->default(false)->comment('True if the move is successful');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_moves');
        Schema::dropIfExists('room_user_status');
        Schema::dropIfExists('room_user_items');
        Schema::dropIfExists('room_users');
        Schema::dropIfExists('rooms');
    }
};
