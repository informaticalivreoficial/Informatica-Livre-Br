<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portifolio extends Model
{
    use HasFactory;

    protected $table = 'portifolios';

    protected $fillable = [
        'category',
        'company',
        'name',
        'content',
        'link',
        'slug',
        'headline',
        'tags',
        'views',
        'cat_pai',
        'status',
        'exibir',
        'thumb_legenda',
        'value',
        'data_inicio',
        'data_termino',
    ];

    protected $casts = [
        'status'      => 'boolean',
        'exibir'      => 'boolean',
        'value'       => 'decimal:2',
        'data_inicio' => 'date',
        'data_termino'=> 'date',
    ];

    public function category()
    {
        return $this->belongsTo(CatPortifolio::class, 'category');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company');
    }

    public function images()
    {
        return $this->hasMany(PortifolioGb::class, 'portifolio');
    }

    public function cover()
    {
        return $this->hasOne(PortifolioGb::class, 'portifolio')->where('cover', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopePublic($query)
    {
        return $query->where('exibir', 1);
    }
}
