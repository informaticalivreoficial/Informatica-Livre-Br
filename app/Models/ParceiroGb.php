<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Support\Cropper;

class ParceiroGb extends Model
{
    use HasFactory;

    protected $table = 'parceiro_gbs'; 

    protected $fillable = [
        'parceiro_id',
        'path',
        'cover'
    ];

    /**
     * Accerssors and Mutators
     */

    public function getUrlCroppedAttribute()
    {
        //return Storage::url(Cropper::thumb($this->path, 1366, 768));
        return Storage::url($this->path);
    }

    public function getUrlImageAttribute()
    {
        return Storage::url($this->path);
    }
}
