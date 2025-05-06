<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'content',
        'link',
        'status',
        'all_users',
    ];

    protected $casts = [
        'status' => 'boolean',
        'all_users' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notifiable');
    }
}
