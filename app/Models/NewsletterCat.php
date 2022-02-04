<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterCat extends Model
{
    use HasFactory;

    protected $table = 'newsletter_cat';

    protected $fillable = [
        'titulo',
        'content',
        'status',
        'servidor_smtp',
        'servidor_porta',
        'servidor_senha',
        'servidor_email',
        'sistema'
    ];
}
