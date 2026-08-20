<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('financial_expenses', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->default(0)->after('due_day');
        });

        Schema::table('financial_incomes', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->default(0)->after('receive_day');
        });
    }

    public function down(): void
    {
        Schema::table('financial_expenses', function (Blueprint $table) {
            $table->dropColumn('amount');
        });

        Schema::table('financial_incomes', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
};