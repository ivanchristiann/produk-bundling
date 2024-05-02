<style>
    label{
        margin-top: 15px;
        margin-bottom: 10px;
        color: black;
    }
    #closePopUp{
        float: right;
        color: black;
        cursor: pointer;
    }

    
</style>
@extends('layout.template')

@section('content')
<div class="portlet-title">
    <div style="display: inline-block;  margin-top: 15px; margin-bottom: 15px; font-size: 25px; font-weight: bold;">
        Tambah Karyawan
    </div>
</div>

@if ($errors->any())
    <div id="popUpError">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{($error)}}<span id="closePopUp" data-dismiss="alert">&times;</span></div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{route('employee.store')}}" enctype="multipart/form-data">
    @csrf
        <label>username</label>
        <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}">

        <label>Password</label>
        <input type="password" name="password" class="form-control" id="password" value="{{ old('password') }}">
        @error('password')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        <label>Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" id="nama" value="{{ old('nama') }}">

        <label>Jenis Kelamin</label>
        <div class="form-check">
            <input type="radio" name="jenisKelamin" id="jenisKelamin" value="Pria" checked>
            <label class="form-check-label" for="jenisKelaminPria">Pria</label>
        </div>
        <div class="form-check">
            <input type="radio" name="jenisKelamin" id="jenisKelamin" value="Wanita">
            <label class="form-check-label" for="jenisKelaminWanita">Wanita</label>
        </div> 

        
        <label>Tempat, Tanggal Lahir</label>
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="tempatLahir" class="form-control" id="tempatLahir" value="{{ old('tempatLahir') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggalLahir" class="form-control" id="tanggalLahir"  value="{{ old('tanggalLahir') }}">
            </div>
        </div>

        <label>Email</label>
        <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}">

        <label>Role</label>
        <div class="form-check">
            <input type="radio" name="role" id="role" value="Admin" checked>
            <label class="form-check-label" for="roleAdmin">Admin</label>
        </div>
        <div class="form-check">
            <input type="radio" name="role" id="role" value="Kasir">
            <label class="form-check-label" for="roleKasir">Kasir</label>
        </div>

        <label>Handphone</label>
        <input type="tel" name="handphone" class="form-control" id="handphone" value="{{ old('handphone') }}">

        <label>alamat</label>
        <input type="text" name="alamat" class="form-control" id="alamat" value="{{ old('alamat') }}">

        <div class="mb-3">
            <label for="inputFoto">Foto </label>
            <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
        </div>

    <button type="submit" class="btn btn-primary" style="margin-top: 20px; width: 100%;">Submit</button>
</form>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Menutup Pop up error selama 7detik
        $("#popUpError").delay(7000).slideUp(function() {
            $(this).alert('close');
        });
    });
</script>
@endsection
