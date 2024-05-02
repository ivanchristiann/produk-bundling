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
    #container{
        display: flex;
        justify-content: center;
        align-items: center;
    }

</style>
@extends('layout.template')

@section('content')
<div class="portlet-title">
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        Detail Produk Bundling 
    </div>
</div>

<label for="nomorTransaksi"><strong>Nama Produk Bundling : </strong> {{$productBundlings->nama}}</label>
@if (str_contains(Auth::user()->role, 'SuperAdmin'))
    <label for="hpp"><strong>Harga Pokok Penjualan : </strong> {{ App\Http\Controllers\ProductController::rupiah($productBundlings->harga_pokok_penjualan)}}</label>
@endif
<label for="hargajual"><strong>Harga Jual : </strong> {{ App\Http\Controllers\ProductController::rupiah($productBundlings->harga_jual)}}</label>
<label for="jumlah"><strong>Stok : </strong> {{$stok->total_jumlah}}</label>
<label for="expDate"><strong>Expired Date : </strong> {{ \Carbon\Carbon::parse($stok->exp_date_terdekat)->format('d F Y') }}
</label>
<label for="jumlahTerjual"><strong>Jumlah Terjual : </strong>{{$jumlahTerjual}}</label>

<label for="detail"><strong>Detail:</strong></label>

<div class="row">
    @foreach ($detailProdukBundlings as $dpb)
        <div class="col-md-6 card text-center" id="container">
            <strong><label for="nama">Produk {{$loop->iteration}} : {{$dpb->nama}} </label></strong>
            <div id="containerImg">
                <img class="card-img-top" src="{{ asset('../assets/images/product/'. $dpb->gambar) }}" alt="{{ $dpb->nama }}"/>
            </div>
            <label for="jumlah">(Jumlah : {{$dpb->jumlah}})</label>
        </div>
    @endforeach
</div>

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
