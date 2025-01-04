<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('ratings')) {
            Schema::create('ratings', function (Blueprint $table) {
                $table->bigIncrements('id');

                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('rateable_id');
                $table->string('rateable_type');

                $table->decimal('rating', 4, 2)->nullable();
                $table->text('comment')->nullable();

                $table->unsignedBigInteger('parent_id')->nullable();
                $table->enum('status', ['active', 'hidden', 'deleted'])->default('active');

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');

                $table->foreign('parent_id')
                    ->references('id')
                    ->on('ratings')
                    ->onDelete('set null');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
