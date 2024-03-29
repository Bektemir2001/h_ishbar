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
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('employer_id');
            $table->unsignedBigInteger('category_id');
            $table->date('start_work');
            $table->integer('amount');
            $table->string('x');
            $table->string('y');
            $table->integer('price')->nullable();
            $table->integer('status')->default(0); //0-open, 1-closed
            $table->timestamps();

            $table->index('employer_id');
            $table->index('category_id');
            $table->foreign('employer_id')
                ->on('users')
                ->references('id');
            $table->foreign('category_id')
                    ->on('work_categories')
                    ->references('id')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
