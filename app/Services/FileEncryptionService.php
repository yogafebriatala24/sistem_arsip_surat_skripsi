<?php

namespace App\Services;

class FileEncryptionService
{
    protected string $cipher = 'aes-256-cbc';
    protected string $key;

    public function __construct()
    {
        $this->key = env('AES_FILE_KEY');

        if (!$this->key || strlen($this->key) !== 32) {
            throw new \Exception("AES_FILE_KEY must be 32 characters long.");
        }
    }

    public function encrypt(string $plainContent): string
    {
        $iv = random_bytes(openssl_cipher_iv_length($this->cipher));

        $encrypted = openssl_encrypt($plainContent, $this->cipher, $this->key, 0, $iv);

        return base64_encode($iv . $encrypted);
    }

    public function decrypt(string $encryptedBase64): string|false
    {
        $data = base64_decode($encryptedBase64);
        $ivLength = openssl_cipher_iv_length($this->cipher);

        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        return openssl_decrypt($encrypted, $this->cipher, $this->key, 0, $iv);
    }
}
