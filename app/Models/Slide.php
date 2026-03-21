<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Support\Cropper;
use Carbon\Carbon;

class Slide extends Model
{
    use HasFactory;

    protected $table = 'slides';

    protected $fillable = [
        'title',
        'image',
        'content',
        'link',
        'target',
        'slug',
        'expired_at',
        'status',
        'view_title',
        'category'
    ];

    protected static function booted()
    {
        // Gerar slug automaticamente
        static::saving(function ($slide) {
            $slide->setSlug();
        });

        // 👈 Deleta imagem antiga ao atualizar
        static::updating(function ($slide) {
            if ($slide->isDirty('image') && $slide->getOriginal('image')) {
                Storage::disk('public')->delete($slide->getOriginal('image'));
            }
        });

        // 👈 Deleta imagem ao excluir registro
        static::deleting(function ($slide) {
            if ($slide->image) {
                Storage::disk('public')->delete($slide->image);
            }
        });
    }

    /**
     * Scopes
    */
    public function scopeAvailable($query)
    {
        return $query->where('status', 1);
    }

    public function scopeUnavailable($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Accerssors and Mutators
    */
    public function getimagem()
    {
        if (empty($this->image) || !Storage::disk('public')->exists($this->image)) {
            return asset('theme/images/image.jpg');
        }

        return Storage::url(Cropper::thumb($this->image, 2200, 1200));
    }

    public function setExpiredAtAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['expired_at'] = null;
            return;
        }

        $this->attributes['expired_at'] = Carbon::parse($value)->format('Y-m-d'); // 👈 salva Y-m-d no banco
    }

    public function setTargetAttribute($value)
    {
        $this->attributes['target'] = ($value == '1' ? 1 : 0);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = ($value == '1' ? 1 : 0);
    }   

    public function setSlug()
    {
        if (!empty($this->title)) {
    
            $baseSlug = Str::slug($this->title);
            $slug = $baseSlug;
            $count = 1;
    
            while (
                Slide::where('slug', $slug)
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
