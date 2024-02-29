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
        Schema::create('user_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->index('user_id');
            $table->index('tag_id');

            $table->foreign('user_id')
                ->on('users')
                ->references('id')
                ->cascadeOnDelete();
            $table->foreign('tag_id')
                ->on('tags')
                ->references('id')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tags');
    }
};
