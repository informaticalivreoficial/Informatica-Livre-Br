<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'description',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}