<?php

namespace App\Models;

use App\Casts\ConfigCast;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VConfig extends Model
{
    protected $fillable = [
        'title',
        'server_id',
        'user_id',
        'used',
        'status',
        'operator',
        'config_encrypted',
        'expire',
    ];

    protected $casts = [
        'status' => 'boolean',
        'config_encrypted' => ConfigCast::class,
    ];

    protected $appends = ['is_expired'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function getIsExpiredAttribute()
    {
        return is_null($this->expire) or ! Carbon::parse($this->expire)->isFuture();
    }

    public function scopeBetweenCreated(Builder $query, string $start, string $end): void
    {
        $query->whereBetween('created_at', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()]);
    }

    public function scopeBetweenExpired(Builder $query, string $start, string $end): void
    {
        $query->whereBetween('expire', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()]);
    }
}
