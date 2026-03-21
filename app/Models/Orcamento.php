<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Orcamento extends Model
{
    use HasFactory, SoftDeletes;
 
    protected $table = 'orcamentos';
 
    protected $fillable = [
        'token',
        'name',
        'email',
        'telefone',
        'cpf',
        'empresa',
        'email_empresa',
        'cnpj',
        'telefone_fixo',
        'celular',
        'whatsapp',
        'cep',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'notas_adicionais',
        'status',
        'respondido_em',
    ];
 
    protected $casts = [
        'respondido_em' => 'datetime',
    ];
 
    /**
     * Gera token único ao criar
     */
    protected static function booted(): void
    {
        static::creating(function (Orcamento $orcamento) {
            $orcamento->token = $orcamento->token ?? Str::uuid();
        });
 
        static::updating(function (Orcamento $orcamento) {
            if ($orcamento->isDirty('status') && $orcamento->status === 'respondido') {
                $orcamento->respondido_em = now();
            }
        });
    }
 
    /**
     * Scopes
     */
    public function scopePendente($query)
    {
        return $query->where('status', 'pendente');
    }
 
    public function scopeRespondido($query)
    {
        return $query->where('status', 'respondido');
    }
 
    public function scopeEmAndamento($query)
    {
        return $query->where('status', 'em_andamento');
    }
 
    /**
     * Accessors
     */
    public function getLinkAttribute(): string
    {
        return route('web.orcamento', $this->token);
    }
 
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pendente'     => 'Pendente',
            'respondido'   => 'Respondido',
            'em_andamento' => 'Em andamento',
            'finalizado'   => 'Finalizado',
            'cancelado'    => 'Cancelado',
            default        => 'Desconhecido',
        };
    }
 
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pendente'     => 'warning',
            'respondido'   => 'info',
            'em_andamento' => 'primary',
            'finalizado'   => 'success',
            'cancelado'    => 'danger',
            default        => 'secondary',
        };
    }
}
