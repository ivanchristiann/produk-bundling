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
    <div style="display: inline-block; margin-top: 15px; margin-bottom: 15px; font-size: 25px; font-weight: bold;">
        Tambah Transaksi Penjualan
    </div>
</div>

@if (session('error'))
    <div id="popUpError">
        <div class="alert alert-danger">{{session('error')}}<span id="closePopUp" data-dismiss="alert">&times;</span></div>
    </div>
@endif

<form method="POST" action="{{route('transaksiPenjualan.store')}}">
    @csrf
    <div class="row">
        <div class="col-md-4 card pb-3 pt-1" >
            <div class="card-body">
                <div>
                    <label>Tanggal Transaksi</label>
                    <input type="date" name="tanggalTransaksi" class="form-control" id="tanggalTransaksi">
                </div>
                <div>
                    <label>Karyawan</label>
                    <select class="form-select" aria-label="Default select example" name="namaKaryawan" id="namaKaryawan" disabled>
                        @foreach ($employeeAktif as $employee)
                            <option value="{{ $employee->id }}">{{$employee->nama}}</option>
                        @endforeach
                    </select>
                </div>
                @error('namaKaryawan')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
    
            </div>
        </div>
        <div class="col-md-8 card" >
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Barcode</label>
                        <input type="text" name="barcodeProduct" class="form-control" id="barcodeProduct">
                    </div>
                    <div class="col-md-6">
                        <label>Produk</label>
                        <select class="form-select autoComplete" aria-label="Default select example" name="product" id="product">
                            <option value="-">-- Pilih Produk --</option>
                            @foreach ($produkAktif as $produk)
                                <option value="{{ $produk->id }}">{{$produk->nama}}</option>
                            @endforeach
                        </select>
                        <div class="text-danger" id="errorProduk" style="display: none;">Silahkan Pilih Produk Terlebih Dahulu.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" id="jumlah" min="0">
                        <label>Stock : </label><label id="jumlahStock"></label>
                    </div>
                    <div class="col-md-6">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" id="harga" min="0" readonly style="border: none; outline: none;"">
                    </div>
                </div>

                <div class="row">
                    <div>
                        <button type="button" class="btn btn-success w-100" style="margin-top: 5px;" id="btnAddCart">                
                            <i class='bx bx-cart'></i> Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<table id="daftarDetailTransaksi" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Barcode</th>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="detailProduct">
     </tbody>
    <tfoot>
        <tr>
            <th colspan="5"></th>
            <th>Total</th>
            <th id="total" colspan="2"></th>
        </tr>

        <tr>
            <th colspan="5"></th>
            <th>Grand Total</th>
            <th id="grandTotal" colspan="2"></th>
        </tr>
    </tfoot>
</table>

<input type="hidden" name="arrayProduk" id="arrayProduk">
<button type="submit" class="btn btn-primary" style="margin-top: 20px; width: 100%;" id="btnSimpan">                
    <i class='bx bx-save'></i> Simpan
</button>
</form>
@endsection

@section('script')
<script type="text/javascript">
    $("#popUpError").delay(7000).slideUp(function() {
        $(this).alert('close');
    });
    
    var products = <?= json_encode($produkAktif); ?>;
    var stok = <?= json_encode($stok); ?>;

    // Set Tanggal Saat Ini
    var today = new Date();
    var formattedDate = today.toISOString().substr(0, 10);
    $("#tanggalTransaksi").val(formattedDate);

    var setMaxDate = new Date().toISOString().split("T")[0];
    $("#tanggalTransaksi").prop("max", setMaxDate);

    // Auto Complete DropDown
    $(document).ready(function() {
        $('.autoComplete').select2();
        $("#barcodeProduct").focus();
    });

    $("#product").change(function () {
        var selectedValue = this.value;
        if(selectedValue != '-'){
            var getProduct = products.find(item => item.id == selectedValue);
            $("#barcodeProduct").val(getProduct.barcode);
            $("#harga").val(getProduct.harga_jual)
            
            $("#jumlah").focus();

            var productStok = stok.find(item => item.products_id == getProduct.id);
            if(productStok == undefined){
                $("#jumlahStock").text('0');
                $("#btnAddCart").prop("disabled", true);
                alert("Tidak ada stok pada produk yang anda pilih");
            }else{
                $("#btnAddCart").prop("disabled", false);
                $("#jumlahStock").text(productStok.total_jumlah);
            }
        }else{
            var getProduct = products.find(item => item.id == selectedValue);
            $("#barcodeProduct").val('');
            $("#harga").val('')
            $("#jumlahStock").text('');
        }
    });

    $("#barcodeProduct").keydown(function () {
        var barcode = this.value;
        if (event.key === "Enter") {
            event.preventDefault();
            var getProduct = products.find(item => item.barcode == barcode);
            if(getProduct){
                $("#product").val(getProduct.id);
                $("#product").trigger("change");
                $("#harga").val(getProduct.harga_jual)

                $("#jumlah").focus();
                
                var productStok = stok.find(item => item.products_id == getProduct.id);
                $("#jumlahStock").text(productStok.total_jumlah);
            }
        }else{
            var getProduct = products.find(item => item.barcode == barcode);
            if(getProduct){
                $("#product").val(getProduct.id);
                $("#product").trigger("change");
                $("#jumlah").focus();

                $("#harga").val(getProduct.harga_jual)
 
                var productStok = stok.find(item => item.products_id == getProduct.id);
                $("#jumlahStock").text(productStok.total_jumlah);

                document.getElementById('product').value = getProduct.id;
            }
        }
    });

    $("#barcodeProduct").keyup(function () {
        var barcode = this.value;
        var getProduct = products.find(item => item.barcode == barcode);
        if(getProduct){
            $("#product").val(getProduct.id);
            $("#product").trigger("change");
            $("#harga").val(getProduct.harga_jual)

            $("#jumlah").focus();
                
            var productStok = stok.find(item => item.products_id == getProduct.id);
            $("#jumlahStock").text(productStok.total_jumlah);
        }
    });

    // addCart
    var arrayProduk = [];
    $("#jumlah").keydown(function () {
        if (event.key === "Enter") {
            addProdukToCart();
            event.preventDefault(); //Menghetikan proses selanjutnya yaitu Mengirim ke controller
        }
    });

    $("#btnAddCart").click(function () {
        if( $("#product").val() === "-" ){
            $("#errorProduk").css("display","block");
        }else{
            addProdukToCart();
        }
    });
    
    function addProdukToCart(){
        // Check Apakah produk sudah pernah ditambahkan ke array apa belum
        var checkInArray = arrayProduk.find(item => item.id == $("#product").val());

        if(checkInArray === undefined){
            var product = products.find(item => item.id == $("#product").val());
            
            var stokCheck = checkStok($("#product").val(), parseInt($("#jumlah").val())|| 1);
            if(stokCheck == true){
                var newProduk = {
                            id: product.id,
                            barcode: product.barcode,
                            nama: product.nama,
                            hpp: product.harga_pokok_penjualan,
                            jumlah: (parseInt($("#jumlah").val())|| 1),
                            harga:parseInt($("#harga").val()),
                            total:parseInt($("#jumlah").val())*parseInt($("#harga").val()),
                        };
                        arrayProduk.push(newProduk);
            }else{
                alert("Jumlah Stok Tidak Mencukupi");
            }
            
        }else{
            var stokCheck = checkStok($("#product").val(), checkInArray['jumlah'] + parseInt($("#jumlah").val())|| 1);
            if(stokCheck == true){
                checkInArray['jumlah'] += parseInt($("#jumlah").val() || 1);
                checkInArray['harga'] = parseInt($("#harga").val());
            }else{
                alert("Jumlah Stok Tidak Mencukupi");
            }

        }       
        refreshDetailProduk();
        $("#product").val('-');
        $("#product").trigger("change");
        $("#harga").val('');
        $("#jumlah").val('');
        $("#barcodeProduct").val('');
        $("#barcodeProduct").focus();
        $("#errorProduk").css("display","none");
    };

    // RefreshDetailProduk
    function refreshDetailProduk(){
        var count = 1;
        var subtotal = 0;
        $("#detailProduct").empty();
        $.each(arrayProduk, function (key, value) {
            $("#detailProduct").append(
                '<tr id="produk' + value["id"] +'">'+
                    '<td>'+ count++ +'</td>'+
                    '<td>'+ value["barcode"]+'</td>'+
                    '<td>'+ value["nama"]+'</td>'+
                    '<td>'+ value["jumlah"]+'</td>'+
                    '<td>'+ value["harga"].toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }) +'</td>'+
                    '<td>'+ parseFloat(value["jumlah"] * value["harga"]).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }) +'</td>'+
                    '<td><button type="button" class="btn btn-danger" onclick="hapusProduk('+ value["id"] +')">X</button></td>'+
                '</tr>');
            subtotal+= parseFloat(value["jumlah"] * value["harga"]);
        });
        $("#total").text(subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }));
        $("#grandTotal").text(subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }));

    }

    // HapusProdukDariArray
    function hapusProduk(id){
        arrayProduk = arrayProduk.filter(item => item.id != id);
        refreshDetailProduk();
    }

    // checkStokProduk
    function checkStok(idProduk, jumlahBeli){
        var productStok = stok.find(item => item.products_id == idProduk);
        if(jumlahBeli <= productStok.total_jumlah){
            return true;
        }else{
            return false;
        }
    }

    $("#btnSimpan").click(function () {
        $("#arrayProduk").val(JSON.stringify(arrayProduk));
    });
</script>
@endsection
