<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyService extends Model
{
    use HasFactory;

    protected $table = 'company_services';

    protected $fillable = [
        'company_id',
        'service_id',
        'amount',
        'interval',
        'starts_at',
        'ends_at',
        'active',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
        'active'    => 'boolean',
    ];

    // public function company()
    // {
    //     return $this->belongsTo(Company::class);
    // }

    // public function service()
    // {
    //     return $this->belongsTo(Service::class);
    // }
}
