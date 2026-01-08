<?php

namespace App\Services;

class FileEncryptionService
{
    protected string $cipher = 'aes-128-cbc';
    protected string $key;

    public function __construct()
    {
        $this->key = env('AES_FILE_KEY');

        if (!$this->key || strlen($this->key) !== 16) {
            throw new \Exception("AES_FILE_KEY harus terdiri dari 16 karakter.");
        }
    }

    public function encrypt(string $plainContent): string
    {
        // Buat IV (Initialization Vector), dibutuhkan untuk proses enkripsi karena jenis cipher-nya cbc.
        $iv = random_bytes(openssl_cipher_iv_length($this->cipher));

        // Lakukan enkripsi aes-128-cbc dengan kunci yang sudah diset.
        $encrypted = openssl_encrypt($plainContent, $this->cipher, $this->key, 0, $iv);

        // return base64_encode dari IV dan hasil enkripsi.
        return base64_encode($iv . $encrypted);
    }

    public function decrypt(string $encryptedBase64): string|false
    {
        // Decode base64 untuk mendapatkan IV dan hasil enkripsi.
        $data = base64_decode($encryptedBase64);
        
        // Dapatkan panjang IV berdasarkan cipher yang digunakan.
        $ivLength = openssl_cipher_iv_length($this->cipher);

        // Pisahkan IV dan hasil enkripsi.
        $iv = substr($data, 0, $ivLength);

        // hasil enkripsi dimulai setelah IV.
        $encrypted = substr($data, $ivLength);

        // Lakukan dekripsi dengan IV dan hasil enkripsi yang sudah dipisahkan.
        return openssl_decrypt($encrypted, $this->cipher, $this->key, 0, $iv);
    }
}
