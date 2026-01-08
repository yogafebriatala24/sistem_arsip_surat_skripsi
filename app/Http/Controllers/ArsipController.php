<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Services\FileEncryptionService;


class ArsipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // jumlah data yang ditampilkan per paginasi halaman
        $pagination = 10;

        if ($request->search) {
            // menampilkan pencarian data
            $arsip = Arsip::select('id', 'nama_surat', 'nomor_surat', 'tanggal_surat', 'kategori_id')->with('kategori:id,nama')
                ->whereAny(['nama_surat', 'nomor_surat'], 'LIKE', '%' . $request->search . '%')
                ->paginate($pagination)
                ->withQueryString();
        } else {
            // menampilkan semua data
            $arsip = Arsip::select('id', 'nama_surat', 'nomor_surat', 'tanggal_surat', 'kategori_id')->with('kategori:id,nama')
                ->latest()
                ->paginate($pagination);
        }

        // tampilkan data ke view
        return view('arsip.index', compact('arsip'))->with('i', ($request->input('page', 1) - 1) * $pagination);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // ambil data kategori
        $kategori = Kategori::get(['id', 'nama']);

        // tampilkan form tambah data arsip dengan data kategori
        return view('arsip.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_surat'         => 'required',
            'nomor_surat'        => 'required|unique:arsip',
            'tanggal_surat'      => 'required|date',
            'kategori'           => 'required|exists:kategori,id',
            'dokumen_elektronik' => 'required|file|mimes:pdf|max:5120'
        ]);

        $file = $request->file('dokumen_elektronik');
        $plainContent = file_get_contents($file);
        $fileName = $file->hashName();

        // Hash untuk deduplikasi
        $fileHash = hash('sha256', $plainContent);

        // Cek apakah file sudah pernah diunggah
        if (Arsip::where('file_hash', $fileHash)->exists()) {
            return redirect()->back()->withErrors(['dokumen_elektronik' => 'File ini sudah pernah diunggah.']);
        }

        // Enkripsi konten
        $encryptionService = new FileEncryptionService();
        $encryptedContent = $encryptionService->encrypt($plainContent);

        // Simpan ke storage terenkripsi
        Storage::disk('private')->put('dokumen/' . $fileName, $encryptedContent);

        // Simpan metadata ke database
        Arsip::create([
            'nama_surat'         => $request->nama_surat,
            'nomor_surat'        => $request->nomor_surat,
            'tanggal_surat'      => $request->tanggal_surat,
            'kategori_id'        => $request->kategori,
            'dokumen_elektronik' => $fileName,
            'file_hash'          => $fileHash,
            'original_file_hash' => $fileHash  // Set original hash saat pembuatan
        ]);

        return redirect()->route('arsip.index')->with(['success' => 'Data arsip surat berhasil disimpan.']);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // dapatkan data berdasarakan "id"
        $arsip = Arsip::findOrFail($id);

        // tampilkan form detail data
        return view('arsip.show', compact('arsip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // dapatkan data berdasarakan "id"
        $arsip = Arsip::findOrFail($id);
        // ambil data kategori
        $kategori = Kategori::get(['id', 'nama']);

        // tampilkan form ubah data
        return view('arsip.edit', compact('arsip', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // validasi form
        $request->validate([
            'nama_surat'         => 'required',
            'nomor_surat'        => 'required|unique:arsip,nomor_surat,' . $id,
            'tanggal_surat'      => 'required|date',
            'kategori'           => 'required|exists:kategori,id',
            'dokumen_elektronik' => 'file|mimes:pdf|max:5120'
        ], [
            'nama_surat.required'      => 'Nama surat tidak boleh kosong.',
            'nomor_surat.required'     => 'Nomor surat tidak boleh kosong.',
            'nomor_surat.unique'       => 'Nomor surat sudah ada.',
            'tanggal_surat.required'   => 'Tanggal surat tidak boleh kosong.',
            'tanggal_surat.date'       => 'Tanggal surat harus berupa tanggal yang valid.',
            'kategori.required'        => 'Kategori surat tidak boleh kosong.',
            'kategori.exists'          => 'Kategori surat yang dipilih tidak valid.',
            'dokumen_elektronik.file'  => 'Dokumen elektronik harus berupa file.',
            'dokumen_elektronik.mimes' => 'Dokumen elektronik harus berupa file dengan jenis: pdf.',
            'dokumen_elektronik.max'   => 'Dokumen elektronik tidak boleh lebih besar dari 5 MB.'
        ]);

        // dapatkan data berdasarakan "id"
        $arsip = Arsip::findOrFail($id);

        // jika "dokumen_elektronik" diubah
        if ($request->hasFile('dokumen_elektronik')) {
            // ambil file baru
            $file = $request->file('dokumen_elektronik');
            $plainContent = file_get_contents($file);
            $fileName = $file->hashName();

            // Hash untuk deduplikasi
            $fileHash = hash('sha256', $plainContent);

            // Cek apakah file sudah pernah diunggah (kecuali untuk arsip ini sendiri)
            if (Arsip::where('file_hash', $fileHash)->where('id', '!=', $id)->exists()) {
                return redirect()->back()->withErrors(['dokumen_elektronik' => 'File ini sudah pernah diunggah oleh arsip lain.']);
            }

            // Enkripsi konten
            $encryptionService = new FileEncryptionService();
            $encryptedContent = $encryptionService->encrypt($plainContent);

            // Simpan ke storage terenkripsi
            Storage::disk('private')->put('dokumen/' . $fileName, $encryptedContent);

            // hapus file lama dari storage private
            Storage::disk('private')->delete('dokumen/' . $arsip->dokumen_elektronik);

            // ubah data - original_file_hash tidak berubah, tetap sebagai hash asli dokumen
            $arsip->update([
                'nama_surat'         => $request->nama_surat,
                'nomor_surat'        => $request->nomor_surat,
                'tanggal_surat'      => $request->tanggal_surat,
                'kategori_id'        => $request->kategori,
                'dokumen_elektronik' => $fileName,
                'file_hash'          => $fileHash
                // original_file_hash tetap sama karena ini adalah hash asli dokumen
            ]);
        }
        // jika "dokumen_elektronik" tidak diubah
        else {
            // ubah data
            $arsip->update([
                'nama_surat'    => $request->nama_surat,
                'nomor_surat'   => $request->nomor_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'kategori_id'   => $request->kategori
            ]);
        }

        // redirect ke halaman index dan tampilkan pesan berhasil ubah data
        return redirect()->route('arsip.index')->with(['success' => 'Data arsip surat berhasil diubah.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        // dapatkan data berdasarakan "id"
        $arsip = arsip::findOrFail($id);

        // hapus file
        Storage::delete('public/dokumen/' . $arsip->dokumen_elektronik);

        // hapus data
        $arsip->delete();

        // redirect ke halaman index dan tampilkan pesan berhasil hapus data
        return redirect()->route('arsip.index')->with(['success' => 'Data arsip surat berhasil dihapus.']);
    }

    /**
     * Download the specified resource.
     */
    public function download($id)
    {
        // Dapatkan arsip berdasarkan ID
        $arsip = Arsip::findOrFail($id);
        $relativePath = 'dokumen/' . $arsip->dokumen_elektronik;

        // validasi apakah file ada di storage
        if (!Storage::disk('private')->exists($relativePath)) {
            return redirect()->route('arsip.show', $arsip->id)->with('error', 'File tidak ditemukan.');
        }

        // Ambil konten terenkripsi dari storage
        $encryptedContent = Storage::disk('private')->get($relativePath);

        // Inisialisasi service untuk dekripsi
        $decryptionService = new FileEncryptionService();

        // Dekripsi konten
        $decryptedContent = $decryptionService->decrypt($encryptedContent);

        // Tampilkan pesan error jika dekripsi gagal, karena ada potensi file rusak atau tidak valid
        if ($decryptedContent === false) {
            return redirect()->route('arsip.show', $arsip->id)->with('error', 'File tidak dapat didekripsi. Ada potensi file rusak atau tidak valid.');
        }

        // Validasi integritas file
        $currentHash = hash('sha256', $decryptedContent);
        if ($currentHash !== $arsip->file_hash) {
            return redirect()->route('arsip.show', $arsip->id)->with('error', 'File rusak atau telah dimodifikasi di luar sistem. Gagal mengunduh.');
        }

        // Kirim ke browser sebagai download
        return response($decryptedContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $arsip->nama_surat . '.pdf"');
    }

    /**
     * Preview the specified resource.
     */
    public function preview($id)
    {
        // Dapatkan arsip berdasarkan ID
        $arsip = Arsip::findOrFail($id);
        $relativePath = 'dokumen/' . $arsip->dokumen_elektronik;

        // Validasi apakah file ada di storage
        if (!Storage::disk('private')->exists($relativePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Ambil konten terenkripsi dari storage
        $encryptedContent = Storage::disk('private')->get($relativePath);

        // Inisialisasi service untuk dekripsi
        $decryptionService = new FileEncryptionService();

        // Dekripsi konten
        $decryptedContent = $decryptionService->decrypt($encryptedContent);

        // Tampilkan pesan error jika dekripsi gagal, karena ada potensi file rusak atau tidak valid
        if ($decryptedContent === false) {
            abort(403, 'File tidak dapat didekripsi atau rusak.');
        }

        // Validasi hash
        $currentHash = hash('sha256', $decryptedContent);
        if ($currentHash !== $arsip->file_hash) {
            abort(403, 'File rusak atau tidak valid.');
        }

        // Kirim ke browser sebagai preview
        return response($decryptedContent)
            ->header('Content-Type', 'application/pdf');
    }

}