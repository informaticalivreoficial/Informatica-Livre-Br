<?php

namespace App\Models;

use App\Enums\BillingInterval;
use App\Enums\BillingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoPlano extends Model
{
    use HasFactory;

    protected $table = 'produto_planos';

    protected $fillable = [
        'produto_id',
        'nome',
        'descricao',
        'preco',
        'preco_de',
        'billing_type',
        'billing_interval',
        'incluso',
        'destaque',
        'status',
        'ordem',
    ];

    protected $casts = [
        'preco'            => 'decimal:2',
        'preco_de'         => 'decimal:2',
        'billing_type'     => BillingType::class,
        'billing_interval' => BillingInterval::class,
        'incluso'          => 'array',
        'destaque'         => 'boolean',
        'status'           => 'boolean',
        'ordem'            => 'integer',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function pedidoItens()
    {
        return $this->hasMany(PedidoItem::class, 'plano_id');
    }

    public function scopeAtivo($query)
    {
        return $query->where('status', true);
    }

    public function scopeDestaque($query)
    {
        return $query->where('destaque', true);
    }

    public function scopeAvulso($query)
    {
        return $query->where('billing_type', BillingType::ONE_TIME->value);
    }

    public function scopeRecorrente($query)
    {
        return $query->where('billing_type', BillingType::RECURRING->value);
    }

    public function isRecurring(): bool
    {
        return $this->billing_type === BillingType::RECURRING;
    }

    public function billingLabel(): string
    {
        if (! $this->isRecurring()) {
            return 'Pagamento único';
        }

        return $this->billing_interval?->label() ?? 'Recorrente';
    }

    public function getTemDescontoAttribute(): bool
    {
        return ! empty($this->preco_de) && $this->preco_de > $this->preco;
    }

    public function getPercentualDescontoAttribute(): int
    {
        if (! $this->tem_desconto) {
            return 0;
        }

        return (int) round((($this->preco_de - $this->preco) / $this->preco_de) * 100);
    }

    public function getPrecoFormatadoAttribute(): string
    {
        return 'R$ ' . number_format((float) $this->preco, 2, ',', '.');
    }

    public function getPrecoDeFormatadoAttribute(): ?string
    {
        if (! $this->preco_de) {
            return null;
        }

        return 'R$ ' . number_format((float) $this->preco_de, 2, ',', '.');
    }

    public function getPeriodoAttribute(): string
    {
        return match ($this->billing_interval) {
            BillingInterval::Monthly    => '/mês',
            BillingInterval::Quarterly  => '/trimestre',
            BillingInterval::Semiannual => '/semestre',
            BillingInterval::Yearly     => '/ano',
            default                     => '',
        };
    }

    public function getPrecoComPeriodoAttribute(): string
    {
        return $this->preco_formatado . $this->periodo;
    }

    public function getInclusoListaAttribute(): array
    {
        return array_values(array_filter((array) $this->incluso));
    }
}
