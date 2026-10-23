<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('description', 255)->nullable()->after('company_id');

            $table->string('payer_name', 191)->nullable()->after('description');
            $table->string('payer_document', 20)->nullable()->after('payer_name');
            $table->string('payer_email', 191)->nullable()->after('payer_document');
            $table->string('payer_phone', 30)->nullable()->after('payer_email');
            $table->string('payer_zipcode', 10)->nullable()->after('payer_phone');
            $table->string('payer_street', 191)->nullable()->after('payer_zipcode');
            $table->string('payer_number', 20)->nullable()->after('payer_street');
            $table->string('payer_complement', 60)->nullable()->after('payer_number');
            $table->string('payer_neighborhood', 80)->nullable()->after('payer_complement');
            $table->string('payer_city', 80)->nullable()->after('payer_neighborhood');
            $table->string('payer_state', 2)->nullable()->after('payer_city');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE invoices MODIFY subscription_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE invoices MODIFY company_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        DB::table('invoices')->whereNull('subscription_id')->orWhereNull('company_id')->delete();

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE invoices MODIFY company_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE invoices MODIFY subscription_id BIGINT UNSIGNED NOT NULL');
        }

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'payer_name',
                'payer_document',
                'payer_email',
                'payer_phone',
                'payer_zipcode',
                'payer_street',
                'payer_number',
                'payer_complement',
                'payer_neighborhood',
                'payer_city',
                'payer_state',
            ]);
        });
    }
};
