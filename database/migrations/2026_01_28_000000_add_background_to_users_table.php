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
            // User background fields
            $table->string('education')->nullable()->after('bio');
            $table->string('occupation')->nullable()->after('education');
            $table->text('work_history')->nullable()->after('occupation');
            $table->boolean('is_verified')->default(false)->after('is_blocked');
            $table->text('background_notes')->nullable()->after('is_verified');
            $table->timestamp('verified_at')->nullable()->after('background_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'education',
                'occupation',
                'work_history',
                'is_verified',
                'background_notes',
                'verified_at'
            ]);
        });
    }
};
