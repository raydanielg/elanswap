<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcement_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feature_id')->nullable()->constrained('features')->nullOnDelete();
            $table->enum('reaction', ['like','dislike']);
            $table->timestamps();
            $table->unique(['user_id','feature_id','reaction']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_feedbacks');
    }
};
