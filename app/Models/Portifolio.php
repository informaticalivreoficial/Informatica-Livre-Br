<?php

namespace App\Models;

use App\Support\Cropper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Portifolio extends Model
{
    use HasFactory;

    protected $table = 'portifolios';

    protected $fillable = [
        'category',
        'company',
        'name',
        'content',
        'link',
        'slug',
        'headline',
        'tags',
        'views',
        'status',
        'exibir',
        'thumb_legenda',
        'value',
        'data_inicio',
        'data_termino',
        'display_marked_water'
    ];

    protected $casts = [
        'status'      => 'integer',
        'exibir'      => 'integer',
        'value'       => 'decimal:2',
        'data_inicio' => 'date',
        'data_termino'=> 'date',
    ];

    public function categoryRelation()
    {
        return $this->belongsTo(CatPortifolio::class, 'category');
    }

    public function companyRelation()
    {
        return $this->belongsTo(Company::class, 'company');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company');
    }

    public function images()
    {
        return $this->hasMany(PortifolioGB::class, 'portifolio');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopePublic($query)
    {
        return $query->where('exibir', 1);
    }

    public function cover()
    {
        $images = $this->images();
        $cover = $images->where('cover', 1)->first(['path']) ??
                $images->first(['path']);

        if (!$cover || empty($cover->path)) {
            return asset('theme/images/image.jpg');
        }

        return Storage::url(Cropper::thumb($cover['path'], 1366, 768));
    } 

    public function thumb()
    {
        $images = $this->images();
        $cover = $images->where('cover', 1)->first(['path']) ??
                $images->first(['path']);

        if (!$cover || empty($cover->path)) {
            return asset('theme/images/image.jpg');
        }

        return Storage::url(Cropper::thumb($cover['path'], 200, 200));
    }
}
