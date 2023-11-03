<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\Fatura;

class FaturaObserver
{
    public function creating(Fatura $fatura)
    {
        $fatura->uuid = (string) Str::uuid();
    }
}
