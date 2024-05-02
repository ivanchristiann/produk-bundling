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
        Tambah Transaksi Pembelian
    </div>
</div>

@if (session('error'))
    <div id="popUpError">
        <div class="alert alert-danger">{{session('error')}}<span id="closePopUp" data-dismiss="alert">&times;</span></div>
    </div>
@endif

<form method="POST" action="{{route('transaksiPembelian.store')}}">
    @csrf
    <div class="row">
        <div class="col-md-4 card pb-3 pt-1" >
            <div class="card-body">
                <div>
                    <label>Tanggal Transaksi</label>
                    <input type="date" name="tanggalTransaksi" class="form-control" id="tanggalTransaksi">
                </div>
                <div>
                    <label>Supplier</label>
                    <select class="form-select autoComplete" aria-label="Default select example" name="namaSupplier" id="namaSupplier">
                        <option value="-">-- Pilih Supplier --</option>
                        @foreach ($supplierAktif as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('namaSupplier') == $supplier->id ? 'selected' : '' }}>{{$supplier->nama}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="text-danger" id="errorSupplier" style="display: none;">Silahkan Pilih Nama Supplier.</div>
    
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
                        <div class="text-danger" id="errorJumlah" style="display: none;">Silahkan Masukkan Jumlah Produk.</div>
                    </div>
                    
                    <div class="col-md-6">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" id="harga" min="0">
                        <div class="text-danger" id="errorHarga" style="display: none;">Silahkan Masukkan Harga Produk.</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-10">
                        <label>Expired Date</label>
                        <input type="date" name="expiredProduct" class="form-control" id="expiredProduct">
                    </div>
                    <div class="col-md-2 mt-3">
                        <button type="button" class="btn btn-success" style="margin-top: 20px;" id="btnAddCart">                
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
            <th>Expired</th>
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

<div class="col-md-12 card" >
    <div class="card-body">
        <label>Pembayaran</label>
        <div class="form-check">
            <input type="radio" name="statusPembayaran" id="statusPembayaranLunas" checked value="Success" onchange="changePembayaran(this)">
            <label class="form-check-label"  style="color: green;">Lunas</label>
        </div>
        <div class="form-check">
            <input type="radio" name="statusPembayaran" id="statusPembayaranPending" value="Pending" onchange="changePembayaran(this)" >
            <label class="form-check-label" style="color: red;">Pending</label>
        </div>

        <div id="jatuhTempo">
            <label>Tanggal Jatuh Tempo</label>
            <input type="date" name="tanggalJatuhTempo" class="form-control" id="tanggalJatuhTempo">
        </div>
    </div>
</div>

<input type="hidden" name="arrayProduk" id="arrayProduk">
<button type="submit" class="btn btn-primary" style="margin-top: 20px; width: 100%;" id="btnSimpan">                
    <i class='bx bx-save'></i> Simpan
</button>
</form>
@endsection

@section('script')
<script type="text/javascript">
    var products = <?= json_encode($produkAktif); ?>;
    
    // Menutup Pop up error selama 7detik
    $("#popUpError").delay(7000).slideUp(function() {
        $(this).alert('close');
    });

    // Set Tanggal Saat Ini
    var today = new Date();
    var formattedDate = today.toISOString().substr(0, 10);
    $("#tanggalTransaksi, #expiredProduct, #tanggalJatuhTempo").val(formattedDate);

    var setDate = new Date().toISOString().split("T")[0];
    $("#tanggalTransaksi").prop("max", setDate);
    $("#tanggalJatuhTempo").prop("min", setDate);  
    $("#expiredProduct").prop("min", setDate);  


    // Auto Complete DropDown
    $(document).ready(function() {
        $('.autoComplete').select2();
        $("#jatuhTempo").hide();
        $("#barcodeProduct").focus();
    });

    // Tampil kolom tanggal jatuh tempo jika user memilih radio pending untuk pembayaran
    function changePembayaran(statusPembayaran){
        statusPembayaran.value === "Pending" ? $("#jatuhTempo").show() : $("#jatuhTempo").hide();
    }

    $("#product").change(function () {
        var selectedValue = this.value;
        if(selectedValue != '-'){
            var getProduct = products.find(item => item.id == selectedValue);
            $("#barcodeProduct").val(getProduct.barcode);

        }else{
            var getProduct = products.find(item => item.id == selectedValue);
            $("#barcodeProduct").val('');
            $("#harga").val('')
            $("#jumlahStock").text('');
        }
    });

    $("#barcodeProduct").keyup(function () {
        var barcode = this.value;
        if (event.key === "Enter") {
            event.preventDefault();
            var barcode = this.value;
            var getProduct = products.find(item => item.barcode == barcode);
            if(getProduct){
                $("#product").val(getProduct.id);
                $("#product").trigger("change");
            
                $("#jumlah").focus();
            }
        }else{
            var getProduct = products.find(item => item.barcode == barcode);
            if(getProduct){
                $("#product").val(getProduct.id);
                $("#product").trigger("change");
                $("#jumlah").focus();

                document.getElementById('product').value = getProduct.id;
            }
        }
        
    });

    // addCart
    var arrayProduk = [];
    $("#btnAddCart").click(function () {
        if( $("#product").val() === "-" ){
            $("#errorProduk").css("display","block");
            $("#errorHarga").css("display","none");
        }else if($("#harga").val() === ""){
            $("#errorHarga").css("display","block");
            $("#errorProduk").css("display","none");
        }else{
            // Check Apakah produk sudah pernah ditambahkan ke array apa belum
            var checkInArray = arrayProduk.find(item => item.id == $("#product").val());

            if(checkInArray === undefined || (checkInArray !== undefined && checkInArray['exp'] != $("#expiredProduct").val())){
                var product = products.find(item => item.id == $("#product").val());

                var newProduk = {
                    id: product.id,
                    barcode: product.barcode,
                    nama: product.nama,
                    jumlah: (parseInt($("#jumlah").val())|| 1),
                    harga:parseInt($("#harga").val()),
                    total:parseInt($("#jumlah").val())*parseInt($("#harga").val()),
                    exp:$("#expiredProduct").val()
                };
                arrayProduk.push(newProduk);
            }else{
                checkInArray['jumlah'] += (parseInt($("#jumlah").val())|| 1),
                checkInArray['harga'] = parseInt($("#harga").val());
                checkInArray['exp'] = $("#expiredProduct").val();
            }       
            refreshDetailProduk();
            $("#errorHarga").css("display","none");
            $("#errorProduk").css("display","none");

            $("#product").val('-');
            $("#product").trigger("change");
            $("#harga").val('');
            $("#jumlah").val('');
            $("#barcodeProduct").val('');
            $("#barcodeProduct").focus();
        }
    });

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
                    '<td>'+ value["exp"] +'</td>'+
                    '<td>'+ parseFloat(value["jumlah"] * value["harga"]).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }) +'</td>'+
                    '<td><button type="button" class="btn btn-danger" onclick="hapusProduk('+ value["id"] +')">X</button></td>'+
                '</tr>');
            subtotal+= parseFloat(value["jumlah"] * value["harga"]);
        });
        $("#total").text(subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }));
        getGrandTotal(subtotal);
    }

    // HapusProdukDariArray
    function hapusProduk(id){
        arrayProduk = arrayProduk.filter(item => item.id != id);
        refreshDetailProduk();
    }

    function getGrandTotal(total){
        $("#grandTotal").text(parseInt(total).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }))
    }


    $("#btnSimpan").click(function () {
        if ($("#namaSupplier").val() === "-") {
            $("#errorSupplier").css("display","block");
            event.preventDefault(); //Menghetikan proses selanjutnya yaitu Mengirim ke controller
        }else{
            $("#errorSupplier").css("display","none");
            $("#arrayProduk").val(JSON.stringify(arrayProduk));
        }
    });
</script>
@endsection
