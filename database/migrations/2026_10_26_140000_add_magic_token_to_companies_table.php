<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('companies', 'magic_token')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('magic_token', 64)->nullable()->unique()->after('api_token');
            });
        }

        if (! Schema::hasColumn('companies', 'magic_token_expires_at')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->timestamp('magic_token_expires_at')->nullable()->after('magic_token');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('companies', 'magic_token_expires_at')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('magic_token_expires_at');
            });
        }

        if (Schema::hasColumn('companies', 'magic_token')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropUnique(['magic_token']);
                $table->dropColumn('magic_token');
            });
        }
    }
};
