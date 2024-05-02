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
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        Edit Customer
    </div>
</div>


@if ($errors->any())
    <div id="popUpError">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{($error)}}<span id="closePopUp" data-dismiss="alert">&times;</span></div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{route('customer.update', $customers->id)}}">
    @csrf
    @method("PUT")
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" id="nama" value='{{old('nama', $customers->nama)}}'>

        <label>Jenis Kelamin</label>
        <div class="form-check">
            <input type="radio" name="jenisKelamin" id="jenisKelamin" value="Pria" @if(old('jenisKelamin', $customers->jenis_kelamin) === 'Pria') checked @endif>
            <label class="form-check-label" for="jenisKelaminPria">Pria</label>
        </div>
        <div class="form-check">
            <input type="radio" name="jenisKelamin" id="jenisKelamin" value="Wanita" @if(old('jenisKelamin', $customers->jenis_kelamin) === 'Wanita') checked @endif>
            <label class="form-check-label" for="jenisKelaminWanita">Wanita</label>
        </div>

        <label>Tempat, Tanggal Lahir</label>
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="tempatLahir" class="form-control" id="tempatLahir" value='{{old('tempatLahir', $customers->tempat_lahir)}}'>
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggalLahir" class="form-control" id="tanggalLahir" value='{{old('tanggalLahir', $customers->tanggal_lahir)}}'>
            </div>
        </div>

        <label>Email</label>
        <input type="email" name="email" class="form-control" id="email" value='{{old('email', $customers->email)}}'>

        <label>Handphone</label>
        <input type="tel" name="handphone" class="form-control" id="handphone" value='{{old('handphone', $customers->phone)}}'>

        <label>Alamat</label>
        <input type="text" name="alamat" class="form-control" id="alamat" value='{{old('alamat', $customers->alamat)}}'>

        <label>Kota</label>
        <input type="text" name="kota" class="form-control" id="kota" value='{{old('kota', $customers->kota)}}'>

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
    