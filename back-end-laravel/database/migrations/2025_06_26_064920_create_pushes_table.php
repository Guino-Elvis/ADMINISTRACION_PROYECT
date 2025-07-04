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
        Schema::create('pushes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repository_id');
            $table->foreign('repository_id')->references('id')->on('repositories')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('push_id')->nullable();
            $table->timestamp('pushed_at');
            $table->json('commits')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pushes');
    }
};
