<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'gateway_fee')) {
                $table->decimal('gateway_fee', 10, 2)->nullable()->after('boleto_barcode');
            }

            if (!Schema::hasColumn('invoices', 'gateway_net_amount')) {
                $table->decimal('gateway_net_amount', 10, 2)->nullable()->after('gateway_fee');
            }

            if (!Schema::hasColumn('invoices', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('gateway_net_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['gateway_fee', 'gateway_net_amount', 'payment_method']);
        });
    }
};