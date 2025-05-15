<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->string('image')->nullable();
            $table->foreignId('test_id')->constrained('tests')->cascadeOnDelete();

            $table->text('option_1');
            $table->text('option_2');
            $table->text('option_3');
            $table->text('option_4');

            $table->integer('correct_option')->check('correct_option IN (1, 2, 3, 4)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
