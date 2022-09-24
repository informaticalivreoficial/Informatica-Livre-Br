<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Support\Cropper;

class Portifolio extends Model
{
    use HasFactory;

    protected $table = 'portifolio';

    protected $fillable = [
        'categoria',
        'empresa',
        'name',
        'content',
        'headline',
        'slug',
        'link',
        'tags',
        'views',
        'cat_pai',        
        'data_inicio',        
        'data_termino',        
        'status',
        'exibir',        
        'thumb_legenda',
        'valor'
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

    public function scopeExibir($query)
    {
        return $query->where('exibir', 1);
    }

    /**
     * Relacionamentos
    */
    public function images()
    {
        return $this->hasMany(PortifolioGb::class, 'portifolio', 'id')->orderBy('cover', 'ASC');
    }
    
    public function countimages()
    {
        return $this->hasMany(PortifolioGb::class, 'portifolio', 'id')->count();
    }

    public function categoriaObject()
    {
        return $this->hasOne(CatPortifolio::class, 'id', 'categoria');
    }

    public function empresaObject()
    {
        return $this->hasOne(Empresa::class, 'id', 'empresa');
    }

    /**
     * Accerssors and Mutators
    */

    public function getContentWebAttribute()
    {
        return Str::words($this->content, '20', ' ...');
    }
    
    public function cover()
    {
        $images = $this->images();
        $cover = $images->where('cover', 1)->first(['path']);

        if(!$cover) {
            $images = $this->images();
            $cover = $images->first(['path']);
        }

        if(empty($cover['path']) || !Storage::disk()->exists($cover['path'])) {
            return url(asset('backend/assets/images/image.jpg'));
        }

        return Storage::url($cover['path']);
    }

    public function nocover()
    {
        $images = $this->images();
        $cover = $images->where('cover', 1)->first(['path']);

        if(!$cover) {
            $images = $this->images();
            $cover = $images->first(['path']);
        }

        if(empty($cover['path']) || !Storage::disk()->exists($cover['path'])) {
            return url(asset('backend/assets/images/image.jpg'));
        }

        return Storage::url($cover['path']);
    }

    public function setExibirAttribute($value)
    {
        $this->attributes['exibir'] = ($value == true || $value == '1' ? 1 : 0);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = ($value == '1' ? 1 : 0);
    }
    
    public function getPublishAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return date('d/m/Y', strtotime($value));
    }

    public function setSlug()
    {
        if(!empty($this->name)){
            $portifolio = Portifolio::where('name', $this->name)->first(); 
            if(!empty($portifolio) && $portifolio->id != $this->id){
                $this->attributes['slug'] = Str::slug($this->name) . '-' . $this->id;
            }else{
                $this->attributes['slug'] = Str::slug($this->name);
            }            
            $this->save();
        }
    } 

    public function setDataInicioAttribute($value)
    {
        $this->attributes['data_inicio'] = (!empty($value) ? $this->convertStringToDate($value) : null);
    }

    public function getDataInicioAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return date('d/m/Y', strtotime($value));
    }

    public function setDataTerminoAttribute($value)
    {
        $this->attributes['data_termino'] = (!empty($value) ? $this->convertStringToDate($value) : null);
    }

    public function getDataTerminoAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return date('d/m/Y', strtotime($value));
    }

    public function setValorAttribute($value)
    {
        $this->attributes['valor'] = (!empty($value) ? floatval($this->convertStringToDouble($value)) : null);
    }

    public function getValorAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return number_format($value, 2, ',', '.');
    }
    
    private function convertStringToDate(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        list($day, $month, $year) = explode('/', $param);
        return (new \DateTime($year . '-' . $month . '-' . $day))->format('Y-m-d');
    }

    private function convertStringToDouble($param)
    {
        if(empty($param)){
            return null;
        }
        return str_replace(',', '.', str_replace('.', '', $param));
    }

    private function clearField(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        return str_replace(['.', '-', '/', '(', ')', ' '], '', $param);
    }
}
