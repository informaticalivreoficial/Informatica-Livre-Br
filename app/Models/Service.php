<?php

namespace App\Models;

use App\Enums\BillingInterval;
use App\Enums\BillingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'name',
        'description',
        'price',
        'billing_type',   // one_time | recurring
        'interval',       // monthly | quarterly | semiannual | yearly
        'is_public',
        'status',
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'is_public'    => 'boolean',
        'interval'     => BillingInterval::class,
        'billing_type' => BillingType::class,
    ];

    protected static function booted()
    {
        static::deleting(function ($service) {
            // Bloqueia, mas NÃO lança exception
            return !$service->subscriptions()->exists();
        });
    }

    /* ================= RELATIONSHIPS ================= */

    public function category()
    {
        return $this->belongsTo(ServiceCategorie::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }    

    /* ================= SCOPES ================= */

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }    

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
