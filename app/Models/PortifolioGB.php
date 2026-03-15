<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PortifolioGB extends Model
{
    use HasFactory;

    protected $table = 'portifolio_gbs';

    protected $fillable = [
        'portifolio',
        'path',
        'cover',
    ];

    protected $casts = [
        'cover' => 'boolean',
    ];

    public function portifolio()
    {
        return $this->belongsTo(Portifolio::class, 'portifolio');
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    public function scopeCover($query)
    {
        return $query->where('cover', true);
    }
}
