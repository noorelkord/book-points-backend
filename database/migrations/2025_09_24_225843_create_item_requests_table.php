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
        Schema::create('item_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('item_id')->constrained()->cascadeOnDelete();
        $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
        $table->enum('status', ['pending','won','lost','cancelled'])->default('pending');
        $table->timestamps();
        $table->unique(['item_id','requester_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_requests');
    }
};
