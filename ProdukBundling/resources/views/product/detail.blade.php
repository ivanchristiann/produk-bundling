<style>
    label{
        margin-top: 15px;
        margin-bottom: 10px;
        color: black;
    }
    #containerImg{
        height: 200px;
        width: 200px;
    }

</style>
@extends('layout.template')

@section('content')
<div class="portlet-title">
    <div style="display: inline-block; margin-top: 15px; margin-bottom: 15px; font-size: 25px; font-weight: bold;">
        Detail Produk 
    </div>
</div>
 
<div class="row">
    <div class="col-md-6">
        <label for="nomorTransaksi"><strong>Nama Produk : </strong> {{$product->nama}}</label><br>
        <label for="nomorTransaksi"><strong>Deskripsi Produk : </strong> {{$product->deskripsi}}</label><br>
        @if (str_contains(Auth::user()->role, 'SuperAdmin'))
            <label for="hpp"><strong>Harga Pokok Penjualan : </strong> {{ App\Http\Controllers\ProductController::rupiah($product->harga_pokok_penjualan)}}</label><br>
        @endif
        <label for="hargajual"><strong>Harga Jual : </strong> {{ App\Http\Controllers\ProductController::rupiah($product->harga_jual)}}</label><br>
        <label for="hastag"><strong>Hashtag : </strong> {{$product->hashtag}}</label><br>
        <label for="jumlahStok"><strong>Jumlah Stok : </strong> {{$stok->total_jumlah}}</label> 
    </div>
    <div class="col-md-6" id="containerImg">
        <img class="card-img-top" src="{{ asset('../assets/images/product/'. $product->gambar) }}" alt="{{ $product->nama }}"/>
    </div>
</div>

@if (count($rincianStokDanExp) != 0)
<label for="jumlah"><strong>Rincian Stok : </strong>
<table id="rincianStok" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Jumlah</th>
            <th>Exp Produk</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rincianStokDanExp as $rincian)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $rincian->jumlah }}</td>
            <td>{{ \Carbon\Carbon::parse($rincian->exp_date)->format('d F Y') }}
                @if($rincian->LamaWaktu <= 30 && $rincian->LamaWaktu >= 0)
                    <span style="color: red; margin-left:5px;">(< 30 Hari)</span>
                @elseif($rincian->LamaWaktu < 0)
                    <span style="color: red; margin-left:5px;">(Expired)</span>
                @endif
            </td>
            
        </tr>
        @endforeach
    </tbody>
</table>
@endif
 




{{-- <label for="namaSupplier">Nama Supplier: {{$productBundlings->namaSupplier}}</label>
<label for="namaKaryawan">Nama Karyawan: {{$productBundlings->namaEmployee}}</label> --}}

{{-- <table id="daftarDetailTransaksi" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Produk</th>
            <th>Gambar Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @if (count($detailTransaksi) == 0)
        <tr>
            <td class="text-center" colspan="10">Tidak ada transaksi Pembelian yang terdata</td>
        </tr>
        @else
        @foreach ($detailTransaksi as $dt)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $dt->nama }}</td>
            <td><img src="/images/product/{{ $dt->gambar}}" width="100px" height="100px"></td>
            <td>{{ $dt->jumlah}}</td>
            <td>{{App\Http\Controllers\ProductController::rupiah($dt->harga)}}</td>
            <td>{{App\Http\Controllers\ProductController::rupiah($dt->jumlah * $dt->harga)}}</td>
        </tr>

        @endforeach
        <tr>
            @for ($i = 0; $i < 4; $i++)
                <td></td>
            @endfor
            <td><strong>Grand Total</strong></td>
            @php
                $subTotal = 0;
                foreach ($detailTransaksi as $detailTransaksi){
                    $subTotal += $detailTransaksi->jumlah * $detailTransaksi->harga;
                }
            @endphp
            <th>{{App\Http\Controllers\ProductController::rupiah($subTotal)}}</th>
        </tr>
        @endif
    </tbody>
</table> --}}

@endsection
