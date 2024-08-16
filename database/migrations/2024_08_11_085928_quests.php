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
// Suggested code may be subject to a license. Learn more: ~LicenseLog:4233556232.
        Schema::create('quests', function (Blueprint $table){
            $table->id();
            $table->string('description');
            $table->string('reward')->nullable();
            $table->integer('required_progress');
            $table->timestamps();
        });

        Schema::create('quest_progress', function (Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('quest_id')->index();
            $table->integer('current_progress')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quests');
        Schema::dropIfExists('quest_progress');
    }
};
