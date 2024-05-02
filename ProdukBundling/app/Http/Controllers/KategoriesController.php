<?php

namespace App\Http\Controllers;

use App\Models\Kategories;
use Illuminate\Http\Request;

class KategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategories = Kategories::all();
        return view('kategori.index', compact('kategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->get('nama') === null) {
            return back()->withInput()->with('error', 'Nama Kategori wajib diisi');
        }

        $kategori = new Kategories();
        $kategori->nama = $request->get('nama');
        $kategori->deskripsi = $request->get('deskripsi') === null ? '-' : $request->get('deskripsi');

        $kategori->created_at = now("Asia/Bangkok");
        $kategori->updated_at = now("Asia/Bangkok");
        $kategori->save();
        return redirect()->route('kategories.index')->with('status', 'New kategories ' .  $kategori->nama . ' is already inserted');
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
        $kategories = Kategories::where('id', $id)->first();
        return view('kategori.edit', compact('kategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->get('nama') === null) {
            return back()->withInput()->with('error', 'Nama Kategori wajib diisi');
        }

        $kategori = Kategories::find($id);

        $kategori->nama = $request->get('nama');
        $kategori->deskripsi = $request->get('deskripsi') === null ? '-' : $request->get('deskripsi');
        $kategori->created_at = now("Asia/Bangkok");
        $kategori->updated_at = now("Asia/Bangkok");
        $kategori->save();
        return redirect()->route('kategories.index')->with('status', 'Edit kategories ' .  $kategori->nama . ' is done');
    }
}
