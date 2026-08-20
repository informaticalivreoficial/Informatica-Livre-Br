<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->string('description')->nullable()->after('notes');
            $table->string('payment_method')->nullable()->after('description');
            $table->date('due_date')->nullable()->after('payment_method');
            $table->string('attachment_path')->nullable()->after('due_date');
            $table->timestamp('due_notified_at')->nullable()->after('attachment_path');
        });
    }

    public function down(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->dropColumn(['description', 'payment_method', 'due_date', 'attachment_path', 'due_notified_at']);
        });
    }
};