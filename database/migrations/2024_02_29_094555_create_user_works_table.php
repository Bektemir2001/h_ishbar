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
        Schema::create('user_works', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('work_id');
            $table->timestamps();
            $table->integer('status')->default(0); // 0-progress, 1-accepted, 2-failed
            $table->index('user_id');
            $table->index('work_id');
            $table->foreign('user_id')
                ->on('users')
                ->references('id')
                ->cascadeOnDelete();
            $table->foreign('work_id')
                ->on('works')
                ->references('id')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_works');
    }
};
