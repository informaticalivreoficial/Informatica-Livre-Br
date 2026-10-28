<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('two_factor_code')->nullable()->after('status');
            $table->timestamp('two_factor_expires_at')->nullable()->after('two_factor_code');
            $table->timestamp('two_factor_sent_at')->nullable()->after('two_factor_expires_at');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_sent_at');
            $table->unsignedTinyInteger('two_factor_attempts')->default(0)->after('two_factor_confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_code',
                'two_factor_expires_at',
                'two_factor_sent_at',
                'two_factor_confirmed_at',
                'two_factor_attempts',
            ]);
        });
    }
};
