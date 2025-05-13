<?php

namespace App\Models;

use Iksaku\Laravel\MassUpdate\MassUpdatable;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use MassUpdatable;

    protected $fillable = [
        'title',
        'content',
        'status',
        'sortable',
    ];

    protected $casts = ['status' => 'boolean'];
}
