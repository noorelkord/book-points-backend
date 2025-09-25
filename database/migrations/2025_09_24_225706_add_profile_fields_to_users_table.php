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
       Schema::table('users', function (Blueprint $table) {
        $table->foreignId('college_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('default_meeting_point_id')->nullable()->constrained('meeting_points')->nullOnDelete();
        $table->unsignedInteger('points')->default(0);
        $table->string('phone')->nullable();
        $table->string('avatar_url')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        $table->dropConstrainedForeignId('college_id');
        $table->dropConstrainedForeignId('location_id');
        $table->dropConstrainedForeignId('default_meeting_point_id');
        $table->dropColumn(['points','phone','avatar_url']);
    });
    }
};
