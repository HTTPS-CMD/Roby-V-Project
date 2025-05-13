<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Server extends Model
{
    use SoftDeletes,HasTags;

    protected $fillable = [
        'name',
        'latin_name',
        'traffic',
        'location',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'serverable');
    }

    public function configs()
    {
        return $this->hasMany(VConfig::class, 'server_id');
    }
}
