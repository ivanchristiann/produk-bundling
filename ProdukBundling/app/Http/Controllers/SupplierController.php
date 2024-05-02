<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplierNonAktif = Supplier::all()->where('status', '0');
        $supplierAktif = Supplier::all()->where('status', '1');
        return view('supplier.index', compact('supplierNonAktif', 'supplierAktif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'namabank' => 'required_with:nomorrekening',
            'nomorrekening' => 'required_with:namabank',
        ], [
            'namabank.required_with' => 'Nama bank wajib diisi',
            'nomorrekening.required_with' => 'Mohon untuk memasukkan nomor rekening.',
        ]);

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'alamat' => 'required',
            'email' => 'required',
            'cp' => 'required',

        ], [
            'nama.required' => 'Nama wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'email.required' => 'Email wajib diisi',
            'cp.required' => 'Contact Person wajib diisi',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $supplier = new Supplier();
        $supplier->nama = $request->get('nama');
        $supplier->alamat = $request->get('alamat');
        $supplier->email = $request->get('email');
        $supplier->nama_bank = strtoupper($request->get('namabank'));
        $supplier->nomor_rekening = $request->get('nomorrekening');
        $supplier->contact_person = $request->get('cp');
        $supplier->status = "1";
        $supplier->created_at = now("Asia/Bangkok");
        $supplier->updated_at = now("Asia/Bangkok");
        $supplier->save();

        return redirect()->route('supplier.index')->with('status', 'New Supplier ' .  $supplier->nama_supplier . ' is already inserted');
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
    public function edit($id)
    {
        $supplier = Supplier::where('id', $id)->first();
        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'namabank' => 'required_with:nomorrekening',
            'nomorrekening' => 'required_with:namabank',
        ], [
            'namabank.required_with' => 'Nama bank wajib diisi',
            'nomorrekening.required_with' => 'Mohon untuk memasukkan nomor rekening.',
        ]);

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'cp' => 'required',

        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'cp.required' => 'Contact Person wajib diisi',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $supplier = Supplier::find($id);

        $supplier->nama = $request->get('nama');
        $supplier->alamat = $request->get('alamat');
        $supplier->email = $request->get('email');
        $supplier->nama_bank = strtoupper($request->get('namabank'));
        $supplier->nomor_rekening = $request->get('nomorrekening');
        $supplier->contact_person = $request->get('cp');

        $supplier->updated_at = now("Asia/Bangkok");
        $supplier->save();
        return redirect()->route('supplier.index')->with('status', 'Supplier  ' .  $supplier->nama_supplier . ' is already updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function nonaktifkan(Request $request)
    {
        $supplier = Supplier::where('id', $request->get('id'))->first();
        $supplier->status = '0';
        $supplier->save();
        return response()->json(array('status' => 'success'), 200);
    }

    public function aktifkan(Request $request)
    {
        $data = Supplier::where('id', $request->get('id'))->first();
        $data->status = '1';
        $data->save();
        return response()->json(array('status' => 'success'), 200);
    }
}
