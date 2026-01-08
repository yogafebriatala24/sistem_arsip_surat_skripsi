<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Arsip extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'arsip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_surat',
        'nomor_surat',
        'tanggal_surat',
        'kategori_id',
        'dokumen_elektronik',
        'file_hash',
        'original_file_hash',
    ];

    /**
     * Relasi Kategori
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Cek apakah dokumen asli (belum pernah diubah)
     */
    public function isOriginal(): bool
    {
        // Jika original_file_hash belum diisi, kita gunakan file_hash saat ini sebagai hash asli
        // Ini untuk menangani dokumen-dokumen lama sebelum kolom original_file_hash ditambahkan
        $originalHash = $this->original_file_hash ?? $this->file_hash;

        // Dokumen asli jika hash saat ini sama dengan hash asli
        return $this->file_hash === $originalHash;
    }

    /**
     * Cek apakah dokumen telah diubah dari aslinya
     */
    public function isModified(): bool
    {
        return !$this->isOriginal();
    }
}
