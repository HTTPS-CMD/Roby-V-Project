<?php

namespace App\Services;

class AesCrypto
{
    public static function encrypt(string $plainText, string $key): string
    {
        $iv = random_bytes(16); // 16 bytes IV برای AES-256-CBC
        $cipher = openssl_encrypt($plainText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv.$cipher); // ترکیب IV و Ciphertext
    }

    public static function decrypt(string $cipherBase64, string $key): string
    {
        $cipherRaw = base64_decode($cipherBase64);
        $iv = substr($cipherRaw, 0, 16);
        $cipherText = substr($cipherRaw, 16);

        return openssl_decrypt($cipherText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }
}
