<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CatPost extends Model
{
    use HasFactory;

    protected $table = 'cat_post';

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

    protected static function booted()
    {
        static::saving(function ($catpost) {
            $catpost->setSlug();
        });        
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Relacionamentos
     */
    public function parent()
    {
        return $this->belongsTo(CatPortifolio::class, 'id_pai');
    }

    public function children()
    {
        return $this->hasMany(CatPost::class, 'id_pai', 'id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'category');
    }

    /**
     * Accerssors and Mutators
     */ 
    public function setSlug()
    {
        if (!empty($this->title)) {
    
            $baseSlug = Str::slug($this->title);
            $slug = $baseSlug;
            $count = 1;
    
            while (
                CatPost::where('slug', $slug)
                    ->where('id', '!=', $this->id)
                    ->exists()
            ) {
                $slug = $baseSlug . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);
                $count++;
            }
    
            $this->attributes['slug'] = $slug;
        }
    }
}
