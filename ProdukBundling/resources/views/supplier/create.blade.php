<style>
    label{
        margin-top: 15px;
        margin-bottom: 10px;
        color: black;
    }
    .uppercase {
        text-transform: uppercase;
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
        Tambah Supplier
    </div>
</div>

@if ($errors->any())
    <div id="popUpError">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{($error)}}<span id="closePopUp" data-dismiss="alert">&times;</span></div>
        @endforeach
    </div>
@endif


<form method="POST" action="{{route('supplier.store')}}">
    @csrf
    <div class="form-group">
        <label>Nama Supplier</label>
        <input type="text" name="nama" class="form-control" id="nama" value="{{ old('nama') }}">

        <label>Alamat Supplier</label>
        <input type="text" name="alamat" class="form-control" id="alamat" value="{{ old('alamat') }}">

        <label>Email Supplier</label>
        <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}">

        <label>Nama Bank</label>
        <input type="text" name="namabank" style="text-transform: uppercase" class="form-control" id="namabank" value="{{ old('namabank') }}">
        @error('namabank')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        <label>Nomor Rekening</label>
        <input type="number" name="nomorrekening" class="form-control" id="nomorrekening" value="{{ old('nomorrekening') }}">
        @error('nomorrekening')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        <label>Contact Person</label>
        <input type="tel" name="cp" class="form-control" id="cp" value="{{ old('cp') }}">
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
