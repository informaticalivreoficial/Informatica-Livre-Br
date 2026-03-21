<?php

namespace App\Enums;

enum BillingType: string {
    case ONE_TIME = 'one_time';
    case RECURRING = 'recurring';
}