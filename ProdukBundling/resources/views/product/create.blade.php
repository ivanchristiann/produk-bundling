<style>
    label{
        margin-top: 15px;
        margin-bottom: 10px;
        color: black;
    }
    #hashtag {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        align-content: center;
        gap: 1em;
        white-space: nowrap;
        width: 100%;
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
        Tambah Produk
    </div>
</div>

@if ($errors->any())
    <div id="popUpError">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{($error)}}<span id="closePopUp" data-dismiss="alert">&times;</span></div>
        @endforeach
    </div>
@endif


<form method="POST" action="{{route('product.store')}}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Nama </label>
        <input type="text" name="namaProduct" class="form-control" id="namaProduct" value="{{ old('namaProduct') }}">

        <label>Deskripsi</label>
        <input type="text" name="deskripsi" class="form-control" id="deskripsi" value="{{ old('deskripsi') }}">

        <label>Barcode</label>
        <input type="text" name="barcode" class="form-control" id="barcode" value="{{ old('barcode') }}">
 
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" id="harga" value="{{ old('harga') }}">

        <label for="inputFoto">Gambar </label>
        <input class="form-control" type="file" id="gambarProduk" name="gambarProduk" accept="image/*">

        
        <label style="float: left;">Berat </label>
        <span style="float: left; cursor: pointer; margin-top: 16px; margin-left: 10px;"><i class='bx bxs-info-circle' title="Apabila terdapat produk yang tidak memiliki berat spesifik maka dapat ditulis 1 pada beratnya"></i></span>
    
        <input type="number" name="berat" class="form-control" id="berat" value="{{ old('berat') }}">

        <label>Satuan </label>
        <div>
            <select class="form-select" aria-label="Default select example" name="satuan" id="satuan">
                <option value="-- Pilih Satuan --">-- Pilih Satuan --</option>
                @foreach ($satuans as $satuan)
                    @if ($satuan->nama != 'Paket')
                        <option value="{{ $satuan->id}}" {{ old('satuan') == $satuan->id ? 'selected' : '' }}>{{$satuan->nama}}</option>                    
                    @endif
                @endforeach
            </select>
        </div>

        <label>Kategori </label>
        <div>
            <select class="form-select" aria-label="Default select example" name="namaKategori" id="namaKategori">
                <option value="-- Pilih Kategori --">-- Pilih Kategori --</option>
                @foreach ($kategories as $kategori)
                    @if ($kategori->nama != 'Paket')
                        <option value="{{ $kategori->id }}" {{ old('namaKategori') == $kategori->id ? 'selected' : '' }}>{{$kategori->nama}}</option>
                    @endif
                @endforeach
            </select>
        </div>       
        <div>
            <label>Subkategori</label>
            <select class="form-select autoComplete" aria-label="Default select example" name="subkategori" id="subkategori"></select>
        </div>
        <label>Hashtag</label>
        <div id="hashtagInput">
            @if(old('hashtag'))
            @foreach(old('hashtag') as $index => $tag)
                <div id="hashtag" class='{{ $index + 1 }}'>
                    <input class="form-control hashtag{{ $index + 1 }}" aria-label="Default select example" name="hashtag[]" id="hashtag" value="{{ $tag }}">
                    <span class="btn btn-danger" onclick="deletehashtag({{ $index + 1 }})">X</span>
                </div>
            @endforeach
        @endif
        </div>
            <input type="button" id="btnAddHashtag" value="Tambah Hashtag" style="width: 100%;" class="btn">
        <div>
    </div> 
    <button type="submit" class="btn btn-primary" style="margin-top: 20px; width: 100%;">Submit</button>

    </div>
</form>
@endsection

@section('script')
<script type="text/javascript">
var subKategori = <?= json_encode($subkategories); ?>;
var count = 0;

$("#btnAddHashtag").click(function () {
        count++;
        $("#hashtagInput").append(
            '<div id="hashtag" class=' + count +
            '><input class="form-control hashtag' + count +'" aria-label="Default select example" name="hashtag[]" id="hashtag">' +
            '<span class="btn btn-danger" onclick="deletehashtag(' + count +')">X</span></div>')
    });
function deletehashtag(id) {
    $("." + id).remove();
}

$("#namaKategori").change(function () {
    getSubkategori();
});

$(document).ready(function() {
    getSubkategori();

    // Menutup Pop up error selama 7detik
    $("#popUpError").delay(7000).slideUp(function() {
        $(this).alert('close');
    });
    $("#namaProduct").focus();
});

$("#barcode").keydown(function () {
    if (event.key === "Enter") {
        $("#harga").focus();
        event.preventDefault(); //Menghetikan proses selanjutnya yaitu Mengirim ke controller
    }
});

function getSubkategori(){
    var selectedValue = $("#namaKategori").val();
    $("#subkategori").html('');
    $("#subkategori").append('<option value="-- Pilih Subkategori --">-- Pilih Subkategori --</option>');
    subKategori.forEach(function(item) {
        if(item.kategories_id == selectedValue){
            if (item.id === {{ old('subkategori', 0) }}) {
                $("#subkategori").append('<option value="' + item.id + '" selected>' + item.nama + '</option>');
            }else{
                $("#subkategori").append('<option value="' + item.id + '">' + item.nama + '</option>');
            }
        }
    });
}


</script>
@endsection
