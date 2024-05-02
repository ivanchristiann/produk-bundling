@extends('layout.template')

@section('content')
<div style="margin-top: 20px; margin-bottom: 20px;">
    <strong style="font-size: 25px;">Daftar Stok Produk</strong>
</div>
@if (session('status'))
    <div class="alert alert-success">{{session('status')}}</div>
@endif
 
<table id="daftarStoks" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Produk</th> 
            <th>Jumlah</th>
            <th>Expired Date</th>
            <th>Lama Waktu</th>
        </tr>
    </thead>
    <tbody> 
        @if (count($stoks) == 0)
        <tr>
            <td class="text-center" colspan="8">Tidak ada stok yang terdata</td>
        </tr>
        @else
        @foreach ($stoks as $stok)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $stok->nama }}</td>
            <td>{{ $stok->jumlah }}</td>
            <td>{{ date('j F Y', strtotime($stok->exp_date))}}
                @if($stok->LamaWaktu <= 30 && $stok->LamaWaktu >= 0)
                    <span style="color: red; margin-left:5px;">(< 30 Hari)</span>
                @elseif($stok->LamaWaktu < 0)
                    <span style="color: red; margin-left:5px;">(Expired)</span>
                @endif
            </td>
            @if ($stok->LamaWaktu < 0)
                <td><span style="color: red;">{{ $stok->LamaWaktu }} Hari</span></td>
            @else
                <td>{{ $stok->LamaWaktu }} <span>Hari</span></td>
            @endif
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
@endsection

@section('script')
<script>
    let table = new DataTable('#daftarStoks');
</script>
@endsection


