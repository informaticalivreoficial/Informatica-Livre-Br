<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Support\Cropper;

class Parceiro extends Model
{
    use HasFactory;

    protected $table = 'parceiros';

    protected $fillable = [
        'name', 'status', 'email', 'logomarca', 'content', 'link',               
        'slug', 'mapa_google', 'metatags', 'views',
        //Endereço
        'cep', 'rua', 'num', 'complemento', 'bairro', 'cidade', 'uf',
        //Contato
        'telefone', 'celular', 'whatsapp', 'skype', 
        //Redes
        'facebook', 'twitter', 'instagram', 'linkedin', 'vimeo', 
        'youtube', 'fliccr', 'soundclound', 'snapchat',                
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

    public function images()
    {
        return $this->hasMany(ParceiroGb::class, 'parceiro_id', 'id')->orderBy('cover', 'ASC');
    }

    /**
     * Relacionamentos
     */


     /**
     * Accerssors and Mutators
     */
	 
	public function metaimg()
    {
        $images = $this->images();
        $metaimg = $images->where('cover', 1)->first(['path']);

        if(!$metaimg) {
            $images = $this->images();
            $cover = $images->first(['path']);
        }

        if(empty($metaimg['path']) || !Storage::disk()->exists($metaimg['path'])) {
            if(empty($this->logomarca) || !Storage::disk()->exists($this->logomarca)) {
                return url(asset('backend/assets/images/image.jpg'));
            }
            return Storage::url($this->logomarca, 300, 300);
        }

        //return Storage::url(Cropper::thumb($metaimg['path'], 800, 418));
        return Storage::url($this->logomarca);
    }
	
    public function cover()
    {       
        if(empty($this->logomarca) || !Storage::disk()->exists($this->logomarca)) {
            return url(asset('backend/assets/images/image.jpg'));
        }

        //return Storage::url(Cropper::thumb($this->logomarca, 300, 300));
        return Storage::url($this->logomarca);
    }
	
	public function nocover()
    {       
        if(empty($this->logomarca) || !Storage::disk()->exists($this->logomarca)) {
            return url(asset('backend/assets/images/image.jpg'));
        }

        return Storage::url($this->logomarca);
    }

    public function setSlug()
    {
        if(!empty($this->name)){
            $parceiro = Parceiro::where('name', $this->name)->first(); 
            if(!empty($parceiro) && $parceiro->id != $this->id){
                $this->attributes['slug'] = Str::slug($this->name) . '-' . $this->id;
            }else{
                $this->attributes['slug'] = Str::slug($this->name);
            }            
            $this->save();
        }
    }

    public function setCepAttribute($value)
    {
        $this->attributes['cep'] = (!empty($value) ? $this->clearField($value) : null);
    }
    
    public function getCepAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return substr($value, 0, 5) . '-' . substr($value, 5, 3);
    }
    
    public function setTelefoneAttribute($value)
    {
        $this->attributes['telefone'] = (!empty($value) ? $this->clearField($value) : null);
    }
    //Formata o telefone para exibir
    public function getTelefoneAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return  
            substr($value, 0, 0) . '(' .
            substr($value, 0, 2) . ') ' .
            substr($value, 2, 4) . '-' .
            substr($value, 6, 4) ;
    }
    
    public function setCelularAttribute($value)
    {
        $this->attributes['celular'] = (!empty($value) ? $this->clearField($value) : null);
    }
    //Formata o celular para exibir
    public function getCelularAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return  
            substr($value, 0, 0) . '(' .
            substr($value, 0, 2) . ') ' .
            substr($value, 2, 5) . '-' .
            substr($value, 7, 4) ;
    }
    
    public function setWhatsappAttribute($value)
    {
        $this->attributes['whatsapp'] = (!empty($value) ? $this->clearField($value) : null);
    }
    //Formata o celular para exibir
    public function getWhatsappAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return  
            substr($value, 0, 0) . '(' .
            substr($value, 0, 2) . ') ' .
            substr($value, 2, 5) . '-' .
            substr($value, 7, 4) ;
    }

    public function getCreatedAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return date('d/m/Y', strtotime($value));
    }

    private function convertStringToDate(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        list($day, $month, $year) = explode('/', $param);
        return (new \DateTime($year . '-' . $month . '-' . $day))->format('Y-m-d');
    }
    
    private function clearField(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        return str_replace(['.', '-', '/', '(', ')', ' '], '', $param);
    }
}
