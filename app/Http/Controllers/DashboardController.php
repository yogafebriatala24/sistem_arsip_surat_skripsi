<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // menampilkan jumlah data arsip surat
        $totalArsip = Arsip::count();
        // menampilkan jumlah data kategori surat
        $totalKategori = Kategori::count();
        // menampilkan jumlah data user
        $totalUser = User::count();

        // menampilkan jumlah arsip surat per kategori surat
        $kategori = Kategori::select('id', 'nama')->withCount('arsip')->get();

        // tampilkan data ke view
        return view('dashboard.index', compact('totalArsip', 'totalKategori', 'totalUser', 'kategori'));
    }
}