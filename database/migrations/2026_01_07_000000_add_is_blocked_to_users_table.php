<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('users', 'is_blocked')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_blocked')->default(false)->after('remember_token');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'is_blocked')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_blocked');
            });
        }
    }
};
