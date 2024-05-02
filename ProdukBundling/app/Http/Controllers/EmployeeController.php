<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employeeNonAktif = Employee::all()->where('status', '0');
        $employeeAktif = Employee::all()->where('status', '1');
        return view('employee.index', compact('employeeNonAktif', 'employeeAktif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:8',
            'nama' => 'required',
            'tempatLahir' => 'required',
            'tanggalLahir' => 'required',
            'email' => 'required',
            'alamat' => 'required',
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'tempatLahir.required' => 'Tempat Lahir wajib diisi',
            'tanggalLahir.required' => 'Tanggal Lahir wajib diisi',
            'email.required' => 'Email wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $employee = new Employee();
        $employee->username = $request->get('username');
        $employee->password = Hash::make($request->get('password'));
        $employee->nama = $request->get('nama');
        $employee->jenis_kelamin = $request->get('jenisKelamin');
        $employee->tempat_lahir = $request->get('tempatLahir');
        $employee->tanggal_lahir = $request->get('tanggalLahir');
        $employee->email = $request->get('email');
        $employee->role = $request->get('role');
        $employee->phone = $request->get('handphone') === null ? '-' : $request->get('handphone');
        $employee->hiredate = now("Asia/Bangkok");
        $employee->alamat = $request->get('alamat');

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $imgFolder = "assets/images/employees";
            $imgFile = time() . "_" . (substr(($file->getClientOriginalName()), 0, 100)) . "." . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $file->move($imgFolder, $imgFile);
            $employee->foto = $imgFile;
        }

        $employee->status = '1';
        $employee->created_at = now("Asia/Bangkok");
        $employee->updated_at = now("Asia/Bangkok");
        $employee->save();
        return redirect()->route('employee.index')->with('status', 'New Employee ' . $employee->nama . ' is already inserted');
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
    {   if(str_contains(Auth::user()->id, $id) || str_contains(Auth::user()->role, 'SuperAdmin')){
            $employee = Employee::where('id', $id)->first();
            return view('employee.edit', compact('employee'));
        }
        else{
            $employee = Employee::where('id',Auth::user()->id)->first();
            return view('employee.edit', compact('employee'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'nama' => 'required',
            'tempatLahir' => 'required',
            'tanggalLahir' => 'required',
            'email' => 'required',
            'alamat' => 'required',
        ], [
            'username.required' => 'Username wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'tempatLahir.required' => 'Tempat Lahir wajib diisi',
            'tanggalLahir.required' => 'Tanggal Lahir wajib diisi',
            'email.required' => 'Email wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $employee = Employee::find($id);

        $employee->nama = $request->get('nama');
        $employee->jenis_kelamin = $request->get('jenisKelamin');
        $employee->tempat_lahir = $request->get('tempatLahir');
        $employee->tanggal_lahir = $request->get('tanggalLahir');
        $employee->email = $request->get('email');
        $employee->role = $request->get('role');
        $employee->phone = $request->get('handphone') === null ? '-' : $request->get('handphone');

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $imgFolder = "assets/images/employees";
            $imgFile = time() . "_" . (substr(($file->getClientOriginalName()), 0, 100)) . "." . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $file->move($imgFolder, $imgFile);
            $employee->foto = $imgFile;
        }

        $employee->hiredate = now("Asia/Bangkok");
        $employee->alamat = $request->get('alamat');

        $employee->updated_at = now("Asia/Bangkok");
        $employee->save();
        return redirect()->route('employee.index')->with('status', 'Edit Employee ' .  $employee->nama . ' is already updated');
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
        $employee = Employee::where('id', $request->get('id'))->first();
        $employee->status = '0';
        $employee->outdate = now("Asia/Bangkok");
        $employee->save();
        return response()->json(array('status' => 'success'), 200);
    }

    public function aktifkan(Request $request)
    {
        $employee = Employee::where('id', $request->get('id'))->first();
        $employee->status = '1';
        $employee->outdate = null;
        $employee->save();
        return response()->json(array('status' => 'success'), 200);
    }

    public function ubahPassword()
    {
        return view('ubahpassword');
    }

    public function newPassword(Request $request)
    {
        if ((Hash::check($request->oldPassword, auth()->user()->password)) == false) {
            return back()->withInput()->with("error", "Old Password Tidak Sesuai");
        } 

        $validator = Validator::make($request->all(), [
            'newPassword' => 'required|min:8',
            'KonfNewPassword' => ['same:newPassword'],
        ], [
            'newPassword.required' => 'Password harus diisi.',
            'KonfNewPassword.required' => 'Confirm Password harus diisi.',

            'newPassword.min' => 'Password minimal harus terdiri dari 8 karakter.',
            'KonfNewPassword.same' => 'Confirm Password berbeda dengan Password baru.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            Employee::find(auth()->user()->id)->update(['password' => Hash::make($request->newPassword)]);
            return redirect('/');
        }
    }
}
