<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            $satuans = Satuan::all();
            return view('satuan.index', compact('satuans'));
        } else {
            return redirect('/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('satuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->get('nama') === null) {
            return back()->withInput()->with('error', 'Nama Satuan wajib diisi');
        }

        $satuan = new Satuan();
        $satuan->nama = $request->get('nama');
        $satuan->deskripsi = $request->get('deskripsi') === null ? '-' : $request->get('deskripsi');

        $satuan->created_at = now("Asia/Bangkok");
        $satuan->updated_at = now("Asia/Bangkok");
        $satuan->save();
        return redirect()->route('satuan.index')->with('status', 'New satuan ' .  $satuan->nama . ' is already inserted');
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
        $satuan = Satuan::where('id', $id)->first();
        return view('satuan.edit', compact('satuan'));
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->get('nama') === null) {
            return back()->withInput()->with('error', 'Nama Satuan wajib diisi');
        }

        $satuan = Satuan::find($id);

        $satuan->nama = $request->get('nama');
        $satuan->deskripsi = $request->get('deskripsi') === null ? '-' : $request->get('deskripsi');

        $satuan->updated_at = now("Asia/Bangkok");
        $satuan->save();
        return redirect()->route('satuan.index')->with('status', 'Edit satuan ' .  $satuan->nama . ' is done');
    }

}
