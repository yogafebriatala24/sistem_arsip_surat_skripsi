<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // ambil data kategori
        $kategori = Kategori::get(['id', 'nama']);

        // tampilkan form filter data arsip dengan data kategori
        return view('laporan.index', compact('kategori'));
    }

    /**
     * filter
     */
    public function filter(Request $request): View
    {
        // validasi form
        $request->validate([
            'kategori'  => 'required',
            'tgl_awal'  => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal'
        ], [
            'kategori.required'        => 'Kategori surat tidak boleh kosong.',
            'tgl_awal.required'        => 'Tanggal awal tidak boleh kosong.',
            'tgl_awal.date'            => 'Tanggal awal harus berupa tanggal yang valid.',
            'tgl_akhir.required'       => 'Tanggal akhir tidak boleh kosong.',
            'tgl_akhir.date'           => 'Tanggal akhir harus berupa tanggal yang valid.',
            'tgl_akhir.after_or_equal' => 'Tanggal akhir harus berupa tanggal setelah atau sama dengan tanggal awal.'
        ]);

        // data filter
        $kategoriId = $request->kategori;
        $tglAwal    = $request->tgl_awal;
        $tglAkhir   = $request->tgl_akhir;

        if ($kategoriId == 'Semua') {
            // menampilkan data berdasarkan filter tanggal surat
            $arsip = Arsip::select('id', 'nama_surat', 'nomor_surat', 'tanggal_surat', 'kategori_id')->with('kategori:id,nama')
                ->whereBetween('tanggal_surat', [$tglAwal, $tglAkhir])
                ->orderBy('tanggal_surat', 'asc')
                ->get();
        } else {
            // menampilkan data berdasarkan filter kategori dan tanggal surat
            $arsip = Arsip::select('id', 'nama_surat', 'nomor_surat', 'tanggal_surat', 'kategori_id')->with('kategori:id,nama')
                ->where('kategori_id', $kategoriId)
                ->whereBetween('tanggal_surat', [$tglAwal, $tglAkhir])
                ->orderBy('tanggal_surat', 'asc')
                ->get();
        }

        // ambil data kategori untuk form select
        $kategori = Kategori::get(['id', 'nama']);

        // ambil data nama kategori untuk judul laporan
        $fieldKategori = Kategori::select('nama')
            ->where('id', $kategoriId)
            ->first();

        // tampilkan data ke view
        return view('laporan.index', compact('arsip', 'kategori', 'fieldKategori'));
    }

    /**
     * print (PDF)
     */
    public function print(Request $request)
    {
        // data filter
        $kategoriId = $request->kategori;
        $tglAwal    = $request->tgl_awal;
        $tglAkhir   = $request->tgl_akhir;

        if ($kategoriId == 'Semua') {
            // menampilkan data berdasarkan filter tanggal surat
            $arsip = Arsip::select('id', 'nama_surat', 'nomor_surat', 'tanggal_surat', 'kategori_id')->with('kategori:id,nama')
                ->whereBetween('tanggal_surat', [$tglAwal, $tglAkhir])
                ->orderBy('tanggal_surat', 'asc')
                ->get();
        } else {
            // menampilkan data berdasarkan filter kategori dan tanggal surat
            $arsip = Arsip::select('id', 'nama_surat', 'nomor_surat', 'tanggal_surat', 'kategori_id')->with('kategori:id,nama')
                ->where('kategori_id', $kategoriId)
                ->whereBetween('tanggal_surat', [$tglAwal, $tglAkhir])
                ->orderBy('tanggal_surat', 'asc')
                ->get();
        }

        // ambil data nama kategori untuk judul laporan
        $fieldKategori = Kategori::select('nama')
            ->where('id', $kategoriId)
            ->first();

        // load view PDF
        $pdf = Pdf::loadview('laporan.print', compact('arsip', 'fieldKategori'))->setPaper('a4', 'landscape');
        // tampilkan ke browser
        return $pdf->stream('Laporan-Data-Arsip-Surat.pdf');
    }
}