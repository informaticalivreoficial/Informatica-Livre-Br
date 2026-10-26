<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->index()->after('cpf_cnpj');
            $table->boolean('recorrente')->default(false)->after('status');
            $table->string('billing_interval')->nullable()->after('recorrente');
            $table->string('recorrencia_status')->nullable()->after('billing_interval');
            $table->unsignedInteger('ciclo')->default(1)->after('recorrencia_status');
            $table->date('proximo_vencimento')->nullable()->after('ciclo');
            $table->foreignId('pedido_pai_id')->nullable()->index()->after('proximo_vencimento');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn([
                'company_id',
                'recorrente',
                'billing_interval',
                'recorrencia_status',
                'ciclo',
                'proximo_vencimento',
                'pedido_pai_id',
            ]);
        });
    }
};
