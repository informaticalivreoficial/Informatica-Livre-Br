<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProdutoGB extends Model
{
    use HasFactory;

    protected $table = 'produto_gbs';

    protected $fillable = [
        'produto',
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
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto');
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
