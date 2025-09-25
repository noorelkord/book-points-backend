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
         Schema::create('meeting_points', function (Blueprint $table) {
    $table->id();
    $table->foreignId('location_id')->constrained()->cascadeOnDelete(); // بدل college_id
    $table->string('name');
    $table->string('description')->nullable();     // اختياري
    $table->decimal('lat', 10, 7)->nullable();     // اختياري
    $table->decimal('lng', 10, 7)->nullable();     // اختياري
    $table->timestamps();

    $table->unique(['location_id', 'name']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_points');
    }
};
