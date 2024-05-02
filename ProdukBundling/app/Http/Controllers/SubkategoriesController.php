<?php

namespace App\Http\Controllers;

use App\Models\Kategories;
use App\Models\Subkategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubkategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subkategories = DB::table('subkategories')
            ->join('kategories', 'kategories.id', '=', 'subkategories.kategories_id')
            ->select('subkategories.*', 'kategories.nama as namaKategori')
            ->get();
        return view('subkategori.index', compact('subkategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategories = Kategories::all();
        return view('subkategori.create', compact('kategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->get('nama') === null) {
            return back()->withInput()->with('error', 'Nama Kategori wajib diisi');
        } 
        else if ($request->get('kategori') === '-- Pilih Kategori --') {
            return back()->withInput()->with('error', 'Silakan pilih kategori terlebih dahulu');
        } 

        $subkategori = new Subkategories();
        $subkategori->nama = $request->get('nama');
        $subkategori->deskripsi = $request->get('deskripsi') === null ? '-' : $request->get('deskripsi');
        $subkategori->kategories_id = $request->get('kategori');

        $subkategori->created_at = now("Asia/Bangkok");
        $subkategori->updated_at = now("Asia/Bangkok");
        $subkategori->save();
        return redirect()->route('subkategories.index')->with('status', 'New Subkategories ' .  $subkategori->nama . ' is already inserted');
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
        $subkategories = Subkategories::where('id', $id)->first();
        $kategories = Kategories::all();

        return view('subkategori.edit', compact('subkategories', 'kategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->get('nama') === null) {
            return back()->withInput()->with('error', 'Nama Kategori wajib diisi');
        } 
        else if ($request->get('kategori') === '-- Pilih Kategori --') {
            return back()->withInput()->with('error', 'Silakan pilih kategori terlebih dahulu');
        } 

        $subkategori = Subkategories::find($id);

        $subkategori->nama = $request->get('nama');
        $subkategori->deskripsi = $request->get('deskripsi') === null ? '-' : $request->get('deskripsi');
        $subkategori->kategories_id = $request->get('kategori');

        $subkategori->created_at = now("Asia/Bangkok");
        $subkategori->updated_at = now("Asia/Bangkok");
        $subkategori->save();
        return redirect()->route('subkategories.index')->with('status', 'Edit subkategori ' .  $subkategori->nama . ' is done');
    }
}
