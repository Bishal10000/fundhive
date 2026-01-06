<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SAFETY CHECK (important for SQLite)
        if (!Schema::hasColumn('campaigns', 'fraud_features')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->json('fraud_features')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('campaigns', 'fraud_features')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->dropColumn('fraud_features');
            });
        }
    }
};
