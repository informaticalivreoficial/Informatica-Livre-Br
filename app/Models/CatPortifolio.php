<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatPortifolio extends Model
{
    use HasFactory;

    protected $table = 'cat_portifolios';

    protected $fillable = [
        'id_pai',
        'title',
        'content',
        'slug',
        'tags',
        'views',
        'type',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(CatPortifolio::class, 'id_pai');
    }

    public function children()
    {
        return $this->hasMany(CatPortifolio::class, 'id_pai');
    }

    public function portifolios()
    {
        return $this->hasMany(Portifolio::class, 'category');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
