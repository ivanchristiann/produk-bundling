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
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        Edit Kategori
    </div>
</div>

@if (session('error'))
    <div id="popUpError">
        <div class="alert alert-danger">{{session('error')}}<span id="closePopUp" data-dismiss="alert">&times;</span></div>
    </div>
@endif

<form method="POST" action="{{route('kategories.update', $kategories->id)}}">
    @csrf
    @method("PUT")
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" id="nama" value="{{ old('nama', $kategories->nama) }}">
        
        <label>Deskripsi</label>
        <input type="text" name="deskripsi" class="form-control" id="deskripsi" value="{{ old('deskripsi', $kategories->deskripsi) }}">   
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
