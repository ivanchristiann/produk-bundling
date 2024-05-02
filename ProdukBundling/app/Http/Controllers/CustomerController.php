<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer = Customer::all();
        return view('customer.index', compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'tempatLahir' => 'required',
            'tanggalLahir' => 'required',
            'email' => 'required',
            'alamat' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'tempatLahir.required' => 'Tempat Lahir wajib diisi',
            'tanggalLahir.required' => 'Tanggal Lahir wajib diisi',
            'email.required' => 'Email wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
 
        $customer = new Customer();
        $customer->nama = $request->get('nama');
        $customer->jenis_kelamin = $request->get('jenisKelamin');
        $customer->tempat_lahir = $request->get('tempatLahir');
        $customer->tanggal_lahir = $request->get('tanggalLahir');
        $customer->email = $request->get('email');
        $customer->phone = $request->get('handphone') === null ? '-' : $request->get('handphone');
        $customer->alamat = $request->get('alamat');
        $customer->kota = $request->get('kota') === null ? '-' : $request->get('kota');

        $customer->created_at = now("Asia/Bangkok");
        $customer->updated_at = now("Asia/Bangkok");
        $customer->save();
        return redirect()->route('customer.index')->with('status', 'New customer ' .  $customer->nama . ' is already inserted');
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
        $customers = Customer::where('id', $id)->first();
        return view('customer.edit', compact('customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'tempatLahir' => 'required',
            'tanggalLahir' => 'required',
            'email' => 'required',
            'alamat' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'tempatLahir.required' => 'Tempat Lahir wajib diisi',
            'tanggalLahir.required' => 'Tanggal Lahir wajib diisi',
            'email.required' => 'Email wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
 
        $customer = Customer::find($id);

        $customer->nama = $request->get('nama');
        $customer->jenis_kelamin = $request->get('jenisKelamin');
        $customer->tempat_lahir = $request->get('tempatLahir');
        $customer->tanggal_lahir = $request->get('tanggalLahir');
        $customer->email = $request->get('email');
        $customer->phone = $request->get('handphone') === null ? '-' : $request->get('handphone');
        $customer->alamat = $request->get('alamat');
        $customer->kota = $request->get('kota') === null ? '-' : $request->get('kota');

        $customer->created_at = now("Asia/Bangkok");
        $customer->updated_at = now("Asia/Bangkok");
        $customer->save();
        return redirect()->route('customer.index')->with('status', 'Edit customer ' .  $customer->nama . ' is already done');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
