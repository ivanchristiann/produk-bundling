<?php

namespace App\Http\Controllers;

use App\Models\Kategories;
use App\Models\Product;
use App\Models\Subkategories;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jumlahProduk = Product::where('is_bundling', '0')->count();
        $jumlahProdukBundling = Product::where('is_bundling', '1')->count();
        $jumlahKategori = Kategories::count();
        $jumlahSubkategori = Subkategories::count();
        $produkAktif = Product::where('status', 1)->where('is_bundling', '0')->paginate(8);

        return view('dashboard', compact('jumlahProduk', 'jumlahProdukBundling', 'jumlahKategori', 'jumlahSubkategori','produkAktif'));
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
