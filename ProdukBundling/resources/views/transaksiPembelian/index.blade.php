@extends('layout.template')

@section('content')
@if (session('status'))
<div class="alert alert-success">{{session('status')}}</div>
@endif
<div style="margin-top: 20px; margin-bottom: 20px;">
    <strong style="font-size: 25px;">Daftar Transaksi Pembelian</strong>
    <a href="{{ route('transaksiPembelian.create') }}" class="btn btn-success btn-m" style="float: right;"><i class="fa fa-plus"></i> Add Transaksi Pembelian</a>
</div>
 
<div style="display: flex; align-items: center;">
    <span style="float: left;">Filter : </span>
    @php
        $startDate = \Carbon\Carbon::parse($startDate);
        $endDate = \Carbon\Carbon::parse($endDate);
    @endphp
    <input type="date" id="startDateFilter" class="form-control" style="width: 20%; float: left; margin-left: 7px;" value="{{$startDate->format('Y-m-d') }}">
    <input type="date" id="endDateFilter" class="form-control" style="width: 20%; float: left; margin-left: 7px;" value="{{$endDate->format('Y-m-d') }}">
</div>

<div style="display: flex; align-items: center; margin-top: 10px; margin-bottom: 10px; ">
    <span style="float: left;">Status Pembayaran: </span>
    <select class="form-select" aria-label="Default select example" name="status" id="status" style="width: 25%; float: left; margin-left: 7px;">
        <option value="All" @if ($status == 'All') selected @endif>All</option>
        <option value="Pending" @if ($status == 'Pending') selected @endif>Pending</option>
        <option value="Success" @if ($status == 'Success') selected @endif>Success</option> 
    </select>    
</div>

<table id="daftarTransaksiPembelian" class="table table-striped" style="width:100%;">
    <thead>
        <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Supplier</th>
            <th>Grand Total</th>
            <th>Status Pembayaran</th>
            <th>Jatuh Tempo</th>
            <th style="text-align: center;">Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($transaksiPembelians) == 0)
        <tr>
            <td class="text-center" colspan="7">Tidak ada transaksi Pembelian yang terdata</td>
        </tr>
        @else
        @foreach ($transaksiPembelians as $tp)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date('j F Y', strtotime($tp->tanggal))}}</td>
            <td>{{ $tp->namaSupplier }}</td>
            <td>{{ App\Http\Controllers\transaksiPembelianController::rupiah($tp->total)}}</td>

            <td style="color: {{ $tp->status_pembayaran == 'Pending' ? 'red' : 'green' }}">{{ $tp->status_pembayaran }}</td>
            @if($tp->LamaWaktu > 0 && $tp->LamaWaktu < 31 && $tp->status_pembayaran == 'Pending')
                <td>{{ date('j F Y', strtotime($tp->jatuh_tempo_pembayaran))}} <span style="color: red;">({{ $tp->LamaWaktu}}Hari)</span></td>   
            @elseif($tp->LamaWaktu <= 0 && $tp->status_pembayaran == 'Pending')
                <td>{{ date('j F Y', strtotime($tp->jatuh_tempo_pembayaran))}}<span style="color: red;">(Tempo)</span></td>
            @else
                <td>{{ date('j F Y', strtotime($tp->jatuh_tempo_pembayaran))}}</td>
            @endif

            @if ($tp->status_pembayaran == 'Success')
                <td colspan="2" class="text-center"><a href="{{ route('transaksiPembelian.detail', $tp->id) }}" class="btn btn-sm btn-info"><i class='bx bx-detail'></i></a></td>
            @else
                <td class="text-center"><a href="{{ route('transaksiPembelian.detail', $tp->id) }}" title="Detail" class="btn btn-sm btn-info"><i class='bx bx-detail'></i></a>
                <a href="{{ route('transaksiPembelian.pembayaran', $tp->id) }}" class="btn btn-sm btn-danger" title="Bayar"><i class='bx bx-money'></i></a></td>
            @endif
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
@endsection

@section('script')
<script>
    let table = new DataTable('#daftarTransaksiPembelian');

    $("#startDateFilter, #endDateFilter, #status").on("change", function () {
        var startDate = $('#startDateFilter').val();
        var endDate = $('#endDateFilter').val();
        var status = $('#status').val();

        if (startDate > endDate) {
            alert("Start date harus lebih awal dari pada end date");
        } else {
            window.location.href = "{{ url('transaksiPembelian') }}/" + startDate + "/" + endDate + "/" + status ;
        }
    });
</script>
@endsection


