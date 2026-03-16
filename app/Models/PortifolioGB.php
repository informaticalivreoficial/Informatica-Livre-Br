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
        'watermark',
        'order_img',
    ];

    protected $casts = [
        'cover' => 'boolean',
        'watermark' => 'boolean',
        'order_img' => 'integer',
    ];

    /**
     * Relations
    */
    public function portifolio()
    {
        return $this->belongsTo(Portifolio::class, 'portifolio');
    }    

    /**
     * Scopes
    */
    public function scopeCover($query)
    {
        return $query->where('cover', true);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    /**
     * Accerssors and Mutators
    */
    public function setWatermarkAttribute($value)
    {
        $this->attributes['watermark'] = ($value == true || $value == '1' ? 1 : 0);
    }
}
