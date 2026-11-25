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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('type', 20)->index();
            $table->string('category', 100)->index();
            $table->string('location', 150);
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('status', 20)->default('open')->index();
            $table->timestamp('event_at')->useCurrent();
            $table->timestamp('expires_at')->nullable()->index();
            $table->string('verification_question')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
