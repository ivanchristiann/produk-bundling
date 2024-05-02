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
        Tambah Subkategori
    </div>
</div>
@if (session('error'))
    <div id="popUpError">
        <div class="alert alert-danger">{{session('error')}}<span id="closePopUp" data-dismiss="alert">&times;</span></div>
    </div>
@endif


<form method="POST" action="{{route('subkategories.store')}}">
    @csrf
    <label>Nama</label>
    <input type="text" name="nama" class="form-control" id="nama" value="{{ old('nama') }}">

    <label>Kategori</label>
    <div>
        <select class="form-select autoComplete" aria-label="Default select example" name="kategori" id="kategori">
            <option>-- Pilih Kategori --</option>
            @foreach ($kategories as $kategori)
                <option value="{{ $kategori->id }}" {{ old('kategori') == $kategori->id ? 'selected' : '' }}>{{$kategori->nama}}</option>
            @endforeach
        </select>
    </div> 

    <label>Deskripsi</label>
    <input type="text" name="deskripsi" class="form-control" id="deskripsi" value="{{ old('deskripsi') }}">

    <button type="submit" class="btn btn-primary" style="margin-top: 20px; width: 100%;">Submit</button>

</form>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('.autoComplete').select2();

        // Menutup Pop up error selama 7detik
        $("#popUpError").delay(7000).slideUp(function() {
            $(this).alert('close');
        });

    });

</script>
@endsection
