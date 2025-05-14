<?php

namespace App\Models;

use App\Casts\ConfigCast;
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
        'config_encrypted',
    ];

    protected $casts = [
        'status' => 'boolean',
        'config_encrypted' => ConfigCast::class,
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
