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
    #gambarProduk1, #gambarProduk2{
        height: 300px;
        width: 300px;
        border: 1px solid black;
    }
    #hashtagProduk1, #hashtagProduk2{
        margin-top: -5px;
    }
    #closePopUp{
        float: right;
        color: black;
        cursor: pointer;
    }

</style> 
@extends('layout.template')
 
@section('content')
<div class="row">
    <div class="col-md-7">
        <div class="portlet-title">
            <div style="display: inline-block;  margin-top: 15px; margin-bottom: 15px; font-size: 25px; font-weight: bold;">
                @if (isset($produkSubstitusi1) && isset($produkSubstitusi2))
                    Cari Produk Substitusi
                @else
                    Buat Produk Bundling
                @endif
            </div>
        </div>
    </div>
    @if (isset($produkSubstitusi1) && isset($produkSubstitusi2))
        <div class="col-md-5 card" style="font-size: 18px;">           
            <label><strong>Urutkan Produk Substitusi Berdasarkan</strong></label>
            <div class="form-check" style="margin-bottom: -5px; margin-top: -5px; ">
                <input type="radio" name="urutanProduk" id="urutanProduk" value="topsis" {{ request('metode') == 'topsis' || !request()->has('metode') ? 'checked' : '' }}>
                <label class="form-check-label" for="topsis">Berat, Harga, Hashtag</label>
            </div>
            <div class="form-check">
                <input type="radio" name="urutanProduk" id="urutanProduk" value="jumlahDanExp" {{ request('metode') == 'jumlahDanExp' ? 'checked' : '' }}>
                <label class="form-check-label" for="jumlahDanExp">Tanggal Kadarluarsa dan Jumlah Penjualan </label>
            </div> 
        </div>
    @endif
</div>
@if ($errors->any())
    <div id="popUpError">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{($error)}}<span id="closePopUp" data-dismiss="alert">&times;</span></div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{route('productbundling.store')}}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6 card text-center">
            <strong><label for="nama" id="labelProduk1"></label></strong>
            <div style="display: flex; justify-content: center;">
                <img class="card-img-top" id="gambarProduk1" />
            </div>
            <label for="hargaProduk1" id="hargaProduk1"></label>
            <label for="hashtagProduk1" id="hashtagProduk1"></label>

            <div style="display: flex; justify-content: center;"> 
                <select class="form-select autoComplete"  aria-label="Default select example" name="product1" id="product1" style="width: 300px; margin-top: 10px;">
                    @if (isset($produk1))
                        <option value="{{ $produk1->id }}">{{$produk1->nama}}</option>
                        @if (isset($produkSubstitusi1))
                            @foreach ($produkSubstitusi1 as $substitusi1)
                                <option value="{{ $substitusi1->id }}" {{ old('product1') == $substitusi1->id ? 'selected' : '' }}>{{$substitusi1->nama}}
                                    @if(request('metode') == 'jumlahDanExp')
                                        (Terjual: {{$substitusi1->jumlahTerjual}}) 
                                        (Exp : {{date('d M Y', strtotime($substitusi1->exp_date))}})
                                    @endif
                                </option>                                      
                            @endforeach                         
                        @endif
                    @else
                        @foreach ($produkAktif as $produk)
                            <option value="{{ $produk->id }}" {{ old('product1') == $produk->id ? 'selected' : '' }}>{{$produk->nama}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div style="width: 100%; text-align: center;  margin-bottom: 15px; margin-top: 10px;">
                <label for="jumlahBundling">Jumlah  : </label>
                <input type="number" name="jumlahProduk1" class="form-control" id="jumlahProduk1" min="0" style="width: 100px; display: inline-block;" value="{{ old('jumlahProduk1', 1) }}">
                <br>
                <div style="color: red;">
                    Stok : 
                    <label for="jumlahBundling" id="stokProduk1" style="color: red;"></label>
                </div>
            </div>
        </div>
        <div class="col-md-6 card text-center">
            <strong><label for="nama" id="labelProduk2"></label></strong>
            <div style="display: flex; justify-content: center;">
                <img class="card-img-top" id="gambarProduk2" />
            </div>
            <label for="hargaProduk2" id="hargaProduk2"></label>
            <label for="hashtagProduk2" id="hashtagProduk2"></label>

            <div style="display: flex; justify-content: center;">
                <select class="form-select autoComplete" aria-label="Default select example" name="product2" id="product2" style="width: 300px; margin-top: 10px;">
                    @if (isset($produk2))
                        <option value="{{ $produk2->id }}">{{$produk2->nama}}</option>
                        @if (isset($produkSubstitusi2))
                            @foreach ($produkSubstitusi2 as $substitusi2)
                                <option value="{{ $substitusi2->id }}" {{ old('product2') == $substitusi2->id ? 'selected' : '' }}>{{$substitusi2->nama}}
                                    @if(request('metode') == 'jumlahDanExp')
                                        (Terjual: {{$substitusi2->jumlahTerjual}}) 
                                        (Exp : {{date('d M Y', strtotime($substitusi2->exp_date))}})
                                    @endif
                                </option>
                            @endforeach                         
                        @endif
                    @else
                        @foreach ($produkAktif as $produk)
                            <option value="{{ $produk->id }}" {{ old('product2') == $produk->id ? 'selected' : '' }}>{{$produk->nama}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div style="width: 100%; text-align: center; margin-bottom: 15px; margin-top: 10px;">
                <label for="jumlahBundling">Jumlah  : </label>
                <input type="number" name="jumlahProduk2" class="form-control" id="jumlahProduk2" min="0" style="width: 100px; display: inline-block;" value="{{ old('jumlahProduk2', 1) }}"><br>
                <div style="color: red;">
                    Stok : 
                    <label for="jumlahBundling" id="stokProduk2" style="color: red;"></label>
                </div>
            </div>
        </div>
        <div style="width: 100%; text-align: center;">
            <label for="jumlahBundling">Jumlah Bundling : </label>
            <input type="number" name="jumlahBundling" class="form-control" id="jumlahBundling" min="1" style="width: 100px; display: inline-block;" value="{{ old('jumlahBundling',1) }}">
            <div style="color: red;">
                Maximal Bundling : 
                <label for="maximalBundling" id="maximalBundling" style="color: red;"></label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 card pb-3 pt-1" >
            <div class="card-body">
                <div>
                    <label>Kategori</label>
                    @foreach ($kategories as $kategorie)
                        @if($kategorie->nama === 'Paket')
                            <input type="hidden" name="namaKategori" class="form-control" id="namaKategori" value="{{$kategorie->id}}" readonly>
                            <input type="text" class="form-control" value="{{ $kategorie->nama }}" readonly>
                        @endif
                    @endforeach
                </div>
                @error('namaKategori')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                <div>
                    <label>Subkategori</label>
                    @foreach ($subkategories as $subkategorie) 
                        @if($subkategorie->nama === 'Paket')
                            <input type="hidden" name="subkategori" class="form-control" id="subkategori" value="{{$subkategorie->id}}" readonly>
                            <input type="text" class="form-control" value="{{ $subkategorie->nama }}" readonly>
                        @endif
                    @endforeach
                </div>
                @error('namaKaryawan')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                <div>
                    <label>Satuan</label>
                    @foreach ($satuans as $satuan)
                        @if($satuan->nama === 'Paket')
                            <input type="hidden" name="satuan" class="form-control" id="satuan" value="{{$satuan->id}}" readonly>
                            <input type="text" class="form-control" value="{{ $satuan->nama }}" readonly>
                        @endif
                    @endforeach
                </div>
                @error('satuan')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-4 card pb-3 pt-1" >
            <div class="card-body">
                <div>
                    <label>Nama Produk Bundling</label>
                    <input type="text" name="namaProdukBundling" class="form-control" id="namaProdukBundling" value="{{ old('namaProdukBundling') }}">
                </div>
                <div>
                    <label>Tanggal Kadarluarsa</label>
                    <input type="date" name="tanggalKadarluarsa" class="form-control" id="tanggalKadarluarsa" readonly>
                </div>
                <div>
                    <label>Harga Jual</label>
                    <input type="number" name="hargaJual" class="form-control" id="hargaJual" value="{{ old('hargaJual') }}" min="0">
                    <label for="hargaSatuan" id="hargaSatuan" style="color: red;"></label>
                </div>
            </div>
        </div>
        <div class="col-md-4 card pb-3 pt-1" >
            <div class="card-body">
                <div>
                    <label>Deskripsi</label>
                    <input type="text" name="deskripsi" class="form-control" id="deskripsi" value="{{ old('deskripsi') }}">
                </div>
                <div>
                    <label>Barcode</label>
                    <input type="text" name="barcode" class="form-control" id="barcode" value="{{ old('barcode') }}">
                </div>
                <div class="mb-3">
                    <label for="inputFoto">Gambar </label>
                    <input class="form-control" type="file" id="gambarProduk" name="gambarProduk">
                </div>
            </div>
        </div>

    </div>
    {{-- <label>Hashtag</label>
    <div id="hashtagInput"></div>
        <input type="button" id="btnAddHashtag" value="Tambah Hashtag" style="width: 100%;" class="btn">
    <div> --}}
    <input type="hidden" name="hppKeduaProduk" class="form-control" id="hppKeduaProduk" style="display: none;">
    <button type="submit" class="btn btn-primary" style="margin-top: 20px; width: 100%;">CREATE</button>

</form>
@endsection

@section('script')
<script>
    var products = <?= json_encode($produkAktif); ?>;
    var subKategori = <?= json_encode($subkategories); ?>;

    $("input[name='urutanProduk']").on("change", function() {
        var url = window.location.href.split('/');
        var sliceUrl = url.slice(0, -1);
        var newUrl = sliceUrl.join('/') + "/" +$(this).val();
        window.location.href = newUrl;
    });


    // var count = 0;
    // $("#btnAddHashtag").click(function () {
    //     count++;
    //     $("#hashtagInput").append(
    //         '<div id="hashtag" class=' + count +
    //         '><input class="form-control hashtag' + count +'" aria-label="Default select example" name="hashtag[]" id="hashtag">' +
    //         '<button type="submit" class="btn btn-danger" onclick="deletehashtag(' + count +')">X</button></div>')
    // });
    // function deletehashtag(id) {
    //     $("." + id).remove();
    // }
    $(document).ready(function() {
        changeProduct();
        $("#popUpError").delay(7000).slideUp(function() {
            $(this).alert('close');
        });
    });

    $("#product1, #product2, #jumlahProduk1, #jumlahProduk2").change(function () {
        changeProduct();
    });

    $("#barcode").keydown(function () {
        if (event.key === "Enter") {
            event.preventDefault(); //Menghentikan proses selanjutnya yaitu Mengirim ke controller
        }
    });


    function changeProduct(){
        var getProduct1 = products.find(item => item.id == $("#product1").val());
        var getProduct2 = products.find(item => item.id == $("#product2").val());

        var jumlahProduk1 = $("#jumlahProduk1").val();
        var jumlahProduk2 = $("#jumlahProduk2").val();

        $("#gambarProduk1").attr("src", "/assets/images/product/" + getProduct1.gambar); 
        $("#gambarProduk2").attr("src", "/assets/images/product/" + getProduct2.gambar); 

        getExpDateAndStok($("#product1").val(), function(dataProduk1) {
            $("#tanggalKadarluarsaProduct1").val(dataProduk1.exp_date);
            $("#stokProduk1").html(dataProduk1.stok !== null ? dataProduk1.stok : 0);
            $("#jumlahProduk1").attr("max", dataProduk1.stok !== null ? dataProduk1.stok : 0);

            getExpDateAndStok($("#product2").val(), function(dataProduk2) {
                $("#tanggalKadarluarsaProduct2").val(dataProduk2.exp_date);
                $("#stokProduk2").html(dataProduk2.stok !== null ? dataProduk2.stok : 0);
                $("#jumlahProduk2").attr("max", dataProduk2.stok !== null ? dataProduk2.stok : 0);


                // Mecari Maximal Produk Bundling dengan membagi stok produk dengan jumlah produk
                // Lalu mencari nilai yang paling kecil dari antara 2 produk
                var paketBundlingMaksimal = Math.floor(Math.min((dataProduk1.stok / jumlahProduk1), (dataProduk2.stok / jumlahProduk2)));
                $("#maximalBundling").html(paketBundlingMaksimal);
                $("#jumlahBundling").attr("max", paketBundlingMaksimal);
                
                // Check apakah exp produk 1 lebih kecil dari exp produk 2
                // jika iya maka exp data yang dipakai adalah exp date produk 1
                $("#tanggalKadarluarsa").val(dataProduk1.exp_date < dataProduk2.exp_date ? dataProduk1.exp_date : dataProduk2.exp_date);
            });
        });

        $("#labelProduk1").text(getProduct1.nama);
        $("#labelProduk2").text(getProduct2.nama);

        $("#hashtagProduk1").text("(" + getProduct1.hashtag + ")");
        $("#hashtagProduk2").text("(" + getProduct2.hashtag + ")");

        $("#hargaProduk1").text("Rp. " + getProduct1.harga_jual);
        $("#hargaProduk2").text("Rp. " + getProduct2.harga_jual);

        $("#hppKeduaProduk").val(getProduct1.harga_pokok_penjualan + getProduct2.harga_pokok_penjualan);
        $("#hargaJual").attr("max", getProduct1.harga_jual + getProduct2.harga_jual);
        $("#hargaSatuan").text("Rp. " + (getProduct1.harga_jual*jumlahProduk1 + getProduct2.harga_jual*jumlahProduk2) + " (tanpa Promo/Bundling)");

    }

    $("#hargaJual").on("input", function() {
        var maxHarga = $("#hargaJual").attr("max");
        var minHarga = $("#hargaJual").attr("min");
        var inputHarga = parseFloat($(this).val());

        if (inputHarga > maxHarga) {
            $(this).val(maxHarga);
        }
        if (inputHarga < minHarga) {
            $(this).val(minHarga);
        }
    });


    // Setting jumlah bundling, jumlah produk 1 dan 2 dan harga jual supaya tidak lebih dari stok
    $("#jumlahBundling").on("input", function() {
        var maxjumlahBundling = $("#jumlahBundling").attr("max");
        var minjumlahBundling = $("#jumlahBundling").attr("min");

        var inputJumlahBundling = parseFloat($(this).val());

        if (inputJumlahBundling > maxjumlahBundling) {
            $(this).val(maxjumlahBundling);
        }
        if (inputJumlahBundling < minjumlahBundling) {
            $(this).val(minjumlahBundling);
        }
    });

    $("#jumlahProduk1").on("input", function() {
        var maxjumlahProduk1 = $("#jumlahProduk1").attr("max");
        var inputJumlahProduk1 = parseFloat($(this).val());

        if (inputJumlahProduk1 > maxjumlahProduk1) {
            $(this).val(maxjumlahProduk1);
        }
    });

    $("#jumlahProduk2").on("input", function() {
        var maxjumlahProduk2 = $("#jumlahProduk2").attr("max");
        var inputJumlahProduk2 = parseFloat($(this).val());

        if (inputJumlahProduk2 > maxjumlahProduk2) {
            $(this).val(maxjumlahProduk2);
        }
    });

    function getExpDateAndStok(ProductId, callback){        
        $.ajax({
            type: 'GET',
            url: "{{ route('productbundling.getExpDate')}}",
            data: {
                '_token': '<?php echo csrf_token(); ?>',
                'products_id': ProductId,
            },
            success: function (data) {
                callback({
                    exp_date: data['exp_date_terdekat'],
                    stok: data['total_jumlah']
                });
            }
        });
    };
</script>
@endsection