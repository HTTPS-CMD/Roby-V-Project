<?php

namespace App\Casts;

use App\Services\AesCrypto;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ConfigCast implements CastsAttributes
{
    private $key;

    public function __construct()
    {
        $this->key = config('services.v2ray.aes_key');
    }

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return AesCrypto::encrypt(json_encode($value), $this->key);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        // return json_decode(AesCrypto::decrypt($value, $key), true);
        return $value;
    }
}
