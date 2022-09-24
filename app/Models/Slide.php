<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Support\Cropper;

class Slide extends Model
{
    use HasFactory;

    protected $table = 'slides';

    protected $fillable = [
        'titulo',
        'subtitulo',
        'botaolabel',
        'imagem',
        'content',
        'link',
        'target',
        'slug',
        'expira',
        'status',
        'exibir_titulo',
        'categoria'
    ];

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
        if(empty($this->imagem) || !Storage::disk()->exists($this->imagem)) {
            return url(asset('backend/assets/images/image.jpg'));
        } 
        return Storage::url($this->imagem);
    }

    public function getUrlImagemAttribute()
    {
        if (!empty($this->imagem)) {
            return Storage::url($this->imagem);
        }
        return '';
    }    

    public function setExpiraAttribute($value)
    {
        $this->attributes['expira'] = (!empty($value) ? $this->convertStringToDate($value) : null);
    }

    public function setTargetAttribute($value)
    {
        $this->attributes['target'] = ($value == '1' ? 1 : 0);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = ($value == '1' ? 1 : 0);
    }
    
    public function getExpiraAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return date('d/m/Y', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return date('d/m/Y', strtotime($value));
    }

    public function setSlug()
    {
        if(!empty($this->titulo)){
            $post = Slide::where('titulo', $this->titulo)->first(); 
            if(!empty($post) && $post->id != $this->id){
                $this->attributes['slug'] = Str::slug($this->titulo) . '-' . $this->id;
            }else{
                $this->attributes['slug'] = Str::slug($this->titulo);
            }            
            $this->save();
        }
    }

    private function convertStringToDate(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        list($day, $month, $year) = explode('/', $param);
        return (new \DateTime($year . '-' . $month . '-' . $day))->format('Y-m-d');
    }
}
