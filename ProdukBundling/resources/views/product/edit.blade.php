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
    #containerImg {
        height: 200px;
        width: 200px;
        overflow: hidden;
    }

    #containerImg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
    <div style="display: inline-block; margin-top: 15px; margin-bottom: 15px; font-size: 25px; font-weight: bold;">
        Edit Produk
    </div>
</div> 
@if ($errors->any())
    <div id="popUpError">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{($error)}}<span id="closePopUp" data-dismiss="alert">&times;</span></div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{route('product.update', $product->id)}}" enctype="multipart/form-data">
    @csrf 
    @method("PUT")
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="namaProduct" class="form-control" id="namaProduct" value='{{old('namaProduct', $product->nama)}}'>
                                                                                    
        <label>Deskripsi</label>
        <input type="text" name="deskripsi" class="form-control" id="deskripsi" value='{{old('deskripsi', $product->deskripsi)}}'>

        <label>Barcode</label>
        <input type="text" name="barcode" class="form-control" id="barcode" value='{{old('barcode', $product->barcode)}}'>

        <label>Harga</label>
        <input type="number" name="harga" class="form-control" id="harga" value='{{old('harga', $product->harga_jual)}}'>

        <label for="inputFoto">Gambar </label>
        @if ($product->gambar != null && $product->gambar != "noImage.jpg")
            <div id="containerImg">
                <img id="tampilGambarProduk" class="card-img-top" src="{{ asset('../assets/images/product/'. $product->gambar) }}" alt="{{ $product->nama }}"/>
            </div>
            <div>
                <input type="button" id="btnGantiGambar" value="Ganti Gambar" style="width: 200px; margin-bottom: 15px;" class="btn">
            </div>
        @else
            <input class="form-control" type="file" id="gambarProduk" name="gambarProduk" accept="image/*" style="display: {{ ($product->gambar != null && $product->gambar != "noImage.jpg") ? 'none' : 'block' }};" onchange="gantiGambar()">
        @endif
        <label>Berat </label>
        <input type="number" name="berat" class="form-control" id="berat"  value='{{old('berat', $product->berat)}}'>

        <label>Satuan </label>
        <div>
            <select class="form-select" aria-label="Default select example" name="satuan" id="satuan">
                <option>-- Pilih Satuan --</option>
                @foreach ($satuans as $satuan)
                    <option value="{{ $satuan->id }}" {{ old('satuan') == $satuan->id ||  $product->satuans_id == $satuan->id ? 'selected' : '' }}>{{$satuan->nama}}</option>
                @endforeach
            </select>
        </div>

        <label>Kategori </label>
        <div>
            <select class="form-select" aria-label="Default select example" name="namaKategori" id="namaKategori">
                <option>-- Pilih Kategori --</option>
                @foreach ($kategories as $kategori)
                    <option value="{{ $kategori->id }}" {{ old('namaKategori') == $kategori->id || $product->kategories_id == $kategori->id ? 'selected' : '' }}>{{$kategori->nama}}</option>
                @endforeach
            </select>
        </div>       
        <div id="subkategori">
        </div>
        <label>Hashtag</label>
        <div>
            @if (count($hashtags) !== 0)
                @foreach ($hashtags as $hashtag)
                <div id="hashtag" class="{{$hashtag['count'] }}">
                    <input class="form-control hashtag{{ $hashtag['count'] }}" aria-label="Default select example" name="hashtag[]" id="hashtag" value="{{ $hashtag['nama'] }}">
                    <button type="submit" class="btn btn-danger" onclick="deletehashtag({{$hashtag['count'] }})">X</button>
                </div>
                @endforeach
            @endif
            <span id="hashtagInput"></span>
            <input type="button" id="btnAddHashtag" value="Tambah Hashtag" style="width: 100%;" class="btn">
        <div>
    </div>
    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Submit</button>
    </div>
</form>
@endsection

@section('script')
<script type="text/javascript">
var subKategori = <?= json_encode($subkategories); ?>;
var product = <?= json_encode($product); ?>;
var count = <?= json_encode($count); ?>;

$("#btnAddHashtag").click(function () {
    $("#hashtagInput").append(
        '<div id="hashtag" class=' + count +
        '><input class="form-control hashtag' + count +'" aria-label="Default select example" name="hashtag[]" id="hashtag">' +
        '<button type="submit" class="btn btn-danger" onclick="deletehashtag(' + count +')">X</button></div>')
    count++;     
});
function deletehashtag(id) {
    $("." + id).remove();
}

$("#namaKategori").change(function () {
    getSubKategori();
});

$(document).ready(function() {    
    getSubKategori();
});

function getSubKategori() {
    var selectedValue = $("#namaKategori").val();

    $("#subkategori").html('');
    $("#subkategori").append('<label>Subkategori</label>');

    var select = '<select class="form-select autoComplete" aria-label="Default select example" name="subkategori" id="subkategori">';
    select += '<option value="-">-- Pilih Subkategori --</option>';

    subKategori.forEach(function(item) {
        if (item.kategories_id == selectedValue) {
            if (product.subkategoris_id == item.id) {
                select += '<option value="' + item.id + '" selected="selected">' + item.nama + '</option>';
            } else {
                select += '<option value="' + item.id + '">' + item.nama + '</option>';
            }
        }
    });
    select += '</select>';

    $("#subkategori").append(select);
}

$("#btnGantiGambar").click(function () {
    var styleDisplay = $("#gambarProduk").css("display");
    if (styleDisplay === 'block') {
        $("#gambarProduk").css("display", 'none');
    } else {
        $("#gambarProduk").css("display", 'block');
    }
});

function gantiGambar() {
  var inputGambar = document.getElementById("gambarProduk");
  var tampilGambar = document.getElementById("tampilGambarProduk");
  
  if (inputGambar.files && inputGambar.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
        tampilGambar.src = e.target.result;
    };
    reader.readAsDataURL(inputGambar.files[0]);
  }
}

$(document).ready(function() {
    // Menutup Pop up error selama 7detik
    $("#popUpError").delay(7000).slideUp(function() {
        $(this).alert('close');
    });
});
</script>
@endsection
