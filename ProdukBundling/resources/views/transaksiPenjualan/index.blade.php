@extends('layout.template')

@section('content')
@if (session('status'))
<div class="alert alert-success">{{session('status')}}</div>
@endif
<div style="margin-top: 20px; margin-bottom: 20px;">
    <strong style="font-size: 25px;">Daftar Transaksi Penjualan</strong>
    <a href="{{ route('transaksiPenjualan.create') }}" class="btn btn-success btn-m" style="float: right;" title="Tambah Transaksi Penjualan"><i class="fa fa-plus"></i> Add Transaksi Penjualan</a>
</div>

<div style="margin-bottom: 15px;">
    <span style="float: left;">Filter : </span>
    @php
        $startDate = \Carbon\Carbon::parse($startDate);
        $endDate = \Carbon\Carbon::parse($endDate);
    @endphp
    <input type="date" id="startDateFilter" class="form-control" style="width: 20%; float: left; margin-left: 7px;" value="{{$startDate->format('Y-m-d') }}">
    <input type="date" id="endDateFilter" class="form-control" style="width: 20%; float: left; margin-left: 7px;" value="{{$endDate->format('Y-m-d') }}">
</div>

<table id="daftarTransaksiPenjualan" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Karyawan</th>
            <th>Grand Total</th>
            <th>Detail</th>
        </tr>
    </thead>
    <tbody>
        @if (count($transaksiPenjualans) == 0)
        <tr>
            <td class="text-center" colspan="10">Tidak ada transaksi Pembelian yang terdata</td>
        </tr>
        @else
        @foreach ($transaksiPenjualans as $tp)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date('j F Y', strtotime($tp->tanggal))}}</td>
            <td>{{ $tp->namaEmployee }}</td>
            <td>{{ App\Http\Controllers\transaksiPenjualanController::rupiah($tp->grand_total)}}</td>
            <td class="text-center"><a href="{{ route('transaksiPenjualan.detail', $tp->id) }}" class="btn btn-sm btn-info" title="Detail"><i class='bx bx-detail'></i></a></td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
@endsection

@section('script')
<script>
    let table = new DataTable('#daftarTransaksiPenjualan');
    $("#startDateFilter, #endDateFilter").on("change", function () {

        var startDate = $('#startDateFilter').val();
        var endDate = $('#endDateFilter').val();
        if (startDate > endDate) {
            alert("Start date harus lebih awal dari pada end date");
        } else {
            window.location.href = "{{ url('transaksiPenjualan') }}/" + startDate + "/" + endDate;
        }
    });


</script>
@endsection


 