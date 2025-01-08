<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('membership_packages')->onDelete('cascade');
            $table->foreignId('trainer_id')->constrained('trainers')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['group', 'personal','free'])->default('group');
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('current_capacity')->default(0);
            $table->integer('max_capacity');
            $table->enum('status', ['active', 'expired', 'scheduled',])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('training_sessions');
    }
};
