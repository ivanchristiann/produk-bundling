<style>
    td{
        white-space: nowrap;
    }
</style>
@extends('layout.template')

@section('content')
<div style="margin-top: 20px; margin-bottom: 20px;">
    <strong style="font-size: 25px;">Daftar Supplier</strong>
    @if (str_contains(Auth::user()->role, 'SuperAdmin'))
        <a href="{{ route('supplier.create') }}" class="btn btn-success btn-m" style="float: right;"><i class="fa fa-plus"></i> Add Supplier</a>
    @endif
</div>
@if (session('status'))
    <div class="alert alert-success">{{session('status')}}</div>
@endif

<div style="overflow-x: auto;">
    <table id="daftarSupplier" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Supplier</th>
                <th>Alamat</th>
                <th>Email</th>
                <th>Nomor Rekening</th>
                <th>Contact Person</th>
                @if (str_contains(Auth::user()->role, 'SuperAdmin'))
                    <th>Edit</th>
                    <th>NonAktifkan</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if (count($supplierAktif) == 0)
            <tr>
                <td class="text-center" colspan="8">Tidak ada supplier yang terdata</td>
            </tr>
            @else
            @foreach ($supplierAktif as $sp)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sp->nama }}</td>
                <td>{{ $sp->alamat }}</td>
                <td>{{ $sp->email }}</td>
                <td>{{ $sp->nama_bank . ' - '. $sp->nomor_rekening }}</td>
                <td>{{ $sp->contact_person }}</td>
                @if (str_contains(Auth::user()->role, 'SuperAdmin'))
                    <td class="text-center"><a href="{{ route('supplier.edit', $sp->id) }}" class="btn btn-sm btn-primary"><i class='bx bx-edit-alt'></i></a></td>
                    <td class="text-center"><button onclick="nonaktifkan({{ $sp->id}})" class="btn btn-sm btn-danger"><i class='bx bx-power-off'></i></button></td>
                @endif
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
<div>
    <div style="margin: 15px; font-size: 20px;">
        <strong>Daftar Supplier Nonaktif</strong>
    </div>
    <div style="overflow-x: auto;">
        <table id="supplierNonAktif" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>Nomor Rekening</th>
                    <th>Contact Person</th>
                    @if (str_contains(Auth::user()->role, 'SuperAdmin'))
                        <th>Edit</th>
                        <th>Aktifkan</th>
                    @endif
                </tr>
            </thead>
        
            <tbody>
            @foreach ($supplierNonAktif as $sp)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sp->nama }}</td>
                <td>{{ $sp->alamat }}</td>
                <td>{{ $sp->email}}</td>
                <td>{{ $sp->nama_bank . ' - '. $sp->nomor_rekening }}</td>
                <td>{{ $sp->contact_person }}</td>
                @if (str_contains(Auth::user()->role, 'SuperAdmin'))
                    <td class="text-center"><a href="{{ route('supplier.edit', $sp->id) }}" class="btn btn-sm btn-primary"><i class='bx bx-edit-alt'></i></a></td>
                    <td class="text-center"><button onclick="aktifkan({{ $sp->id}})" class="btn btn-sm btn-success"><i class='bx bx-power-off'></i></button></td>
                @endif
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
    let table = new DataTable('#daftarSupplier,#supplierNonAktif');

    function nonaktifkan(id) {
        $.ajax({
            type: 'POST',
            url: "{{ route('supplier.nonaktifkan') }}",
            data: {
                '_token': '<?php echo csrf_token(); ?>',
                'id': id,
            },
            success: function (data) {
                if (data['status'] == 'success') {
                    window.location.reload(true);
                }
            }
        });
    }

    function aktifkan(id) {
        $.ajax({
            type: 'POST',
            url: "{{ route('supplier.aktifkan')}}",
            data: {
                '_token': '<?php echo csrf_token(); ?>',
                'id': id,
            },
            success: function (data) {
                if (data['status'] == 'success') {
                    window.location.reload(true);
                }
            }
        });
    }

</script>

@endsection


