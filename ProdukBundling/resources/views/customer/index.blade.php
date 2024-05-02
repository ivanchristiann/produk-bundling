<style>
    td{
        white-space: nowrap;
    }
</style>
@extends('layout.template')

@section('content')
<div style="margin-top: 20px; margin-bottom: 20px;">
    <strong style="font-size: 25px;">Daftar Customer</strong>
    <a href="{{ route('customer.create') }}" class="btn btn-success btn-m" style="float: right;"><i class="fa fa-plus"></i> Add Customer</a>
</div>
@if (session('status'))
    <div class="alert alert-success">{{session('status')}}</div>
@endif

<div style="overflow-x: auto;">
    <table id="daftarCustomer" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Tempat, Tanggal Lahir</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Alamat</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (count($customer) == 0)
            <tr>
                <td class="text-center" colspan="8">Tidak ada customer yang terdata</td>
            </tr>
            @else
            @foreach ($customer as $cus)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $cus->nama }}</td>
                <td>{{ $cus->jenis_kelamin }}</td>
                <td>{{ $cus->tempat_lahir . ", " . date('j F Y', strtotime( $cus->tanggal_lahir))}}</td> 
                <td>{{ $cus->email }}</td>
                <td>{{ $cus->phone }}</td>
                <td>{{ $cus->alamat }}</td>
                <td class="text-center"><a href="{{ route('customer.edit', $cus->id) }}" class="btn btn-sm btn-primary"><i class='bx bx-edit-alt'></i></a></td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
</div>
@endsection

@section('script')
<script>
    let table = new DataTable('#daftarCustomer');
</script>
@endsection


