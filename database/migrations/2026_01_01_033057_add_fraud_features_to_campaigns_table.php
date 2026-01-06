<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('campaigns', function (Blueprint $table) {
        $table->json('fraud_features')->nullable()->after('fraud_score');
    });
}

public function down()
{
    Schema::table('campaigns', function (Blueprint $table) {
        $table->dropColumn('fraud_features');
    });
}
};