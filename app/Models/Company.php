<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'user',
        'logo',
        'social_name',
        'alias_name',
        'document_company',
        'document_company_secondary',
        'information',
        'status',
        //Redes Sociais
        'facebook', 'twitter', 'instagram', 'linkedin',
        //contact 
        'phone', 'cell_phone', 'whatsapp', 'telegram', 'email', 'additional_email',
        //Address      
        'zipcode', 'street', 'number', 'complement', 'neighborhood', 'state', 'city',
    ];

    protected $casts = [
        'status' => 'boolean',
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
     * Relationships
    */

    public function services()
    {
        return $this->belongsToMany(Service::class, 'company_services')
            ->withPivot([
                'amount',
                'interval',
                'starts_at',
                'ends_at',
                'active',
            ])
            ->withTimestamps();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'user');
    }    

    /**
     * Accerssors and Mutators
    */
    public function getlogo()
    {
        if(empty($this->logo) || !Storage::disk()->exists($this->logo)) {
            return asset('theme/images/image.jpg');
        } 
        return Storage::url($this->logo);
    }

    public function logoPathForPdf(): string
    {
        if ($this->logo && file_exists(storage_path('app/public/' . $this->logo))) {
            return storage_path('app/public/' . $this->logo);
        }

        return public_path('theme/images/image.jpg');
    }

    public function setZipcodeAttribute($value)
    {
        $this->attributes['zipcode'] = (!empty($value) ? $this->clearField($value) : null);
    }

    public function getZipcodeAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return substr($value, 0, 5) . '-' . substr($value, 5, 3);
    }

    public function setDocumentCompanyAttribute($value)
    {
        $this->attributes['document_company'] = (!empty($value) ? $this->clearField($value) : null);
    }

    public function getDocumentCompanyAttribute($value)
    {
        return substr($value, 0, 2) . '.' . substr($value, 2, 3) . '.' . substr($value, 5, 3) .
            '/' . substr($value, 8, 4) . '-' . substr($value, 12, 2);
    }

    public function setCellPhoneAttribute($value)
    {
        $this->attributes['cell_phone'] = (!empty($value) ? $this->clearField($value) : null);
    }
    
    public function getCellPhoneAttribute($value)
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

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = (!empty($value) ? $this->clearField($value) : null);
    }

    public function getPhoneAttribute($value)
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
