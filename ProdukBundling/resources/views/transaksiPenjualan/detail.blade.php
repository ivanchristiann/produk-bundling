<style>
    label{
        margin-top: 15px;
        margin-bottom: 10px;
        color: black;
    }
</style>
@extends('layout.template')

@section('content')
<div class="portlet-title">
    <div style="display: inline-block; margin-top: 15px; margin-bottom: 15px; font-size: 25px; font-weight: bold;">
        Detail Transaksi Penjualan
    </div>
</div>
<label for="nomorTransaksi"><strong>Nomor Transaksi</strong> : {{$transaksiPenjualans->id}}</label>
<label for="tanggal"><strong>Tanggal</strong>: {{ date('j F Y', strtotime($transaksiPenjualans->tanggal))}}</label>
<label for="namaKaryawan"><strong>Nama Karyawan</strong> : {{$transaksiPenjualans->namaEmployee}}</label>

<table id="daftarDetailTransaksi" class="table table-striped" style="width:100%">
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
            <td class="text-center" colspan="10">Tidak ada transaksi Penjualan yang terdata</td>
        </tr>
        @else
        @foreach ($detailTransaksi as $dt)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $dt->nama }}</td>
            <td><img src="{{ asset('../assets/images/product/'. $dt->gambar) }}" width="100px" height="100px"></td>
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
</table>

@endsection
