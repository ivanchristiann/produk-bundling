<style>
    td{
        white-space: nowrap;
    }
</style>
@extends('layout.template')

@section('content')
<div style="margin-top: 20px; margin-bottom: 20px;">
    <strong style="font-size: 25px;">Daftar Karyawan</strong>
    <a href="{{ route('employee.create') }}" class="btn btn-success btn-m" style="float: right;"><i class="fa fa-plus"></i> Add Karyawan</a>
</div>
@if (session('status'))
    <div class="alert alert-success">{{session('status')}}</div>
@endif


<div style="overflow-x: auto;">
    <table id="daftarEmployeeAktif" class="table table-striped" style="width:100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Tempat, tanggal lahir</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Alamat</th>
                <th>Hire Date</th>
                <th>Role</th>
                <th>Edit</th>
                <th>NonAktifkan</th>
            </tr>
        </thead>
        <tbody>
            @if (count($employeeAktif) == 0)
            <tr>
                <td class="text-center" colspan="8">Tidak ada karyawan yang terdata</td>
            </tr>
            @else
            @foreach ($employeeAktif as $ep)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $ep->nama }}</td>
                <td>{{ $ep->jenis_kelamin }}</td>
                <td>{{ $ep->tempat_lahir . ", " . date('j F Y', strtotime( $ep->tanggal_lahir))}}</td> 
                <td>{{ $ep->email }}</td>
                <td>{{ $ep->phone }}</td>
                <td>{{ $ep->alamat }}</td>
                <td>{{ date('j F Y', strtotime( $ep->hiredate))}}</td>
                <td>{{ $ep->role }}</td>
                <td class="text-center"><a href="{{ route('employee.edit', $ep->id) }}" class="btn btn-sm btn-primary"><i class='bx bx-edit-alt'></i></a></td>
                <td class="text-center"><button onclick="nonaktifkan({{ $ep->id}})" class="btn btn-sm btn-danger"><i class='bx bx-power-off'></i></button></td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>

<div>
    <div style="margin: 15px; font-size: 20px;">
        <strong>Daftar Karyawan Nonaktif</strong>
    </div>
    <div style="overflow-x: auto;">
        <table id="daftarEmployeeNonAktif" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat, tanggal lahir</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Alamat</th>
                    <th>Hire Date</th>
                    <th>Role</th>
                    <th>Edit</th>
                    <th>Aktifkan</th>
                </tr>
            </thead>
            <tbody>
                @if (count($employeeNonAktif) == 0)
                <tr>
                    <td class="text-center" colspan="8">Tidak ada karyawan yang terdata</td>
                </tr>
                @else
                @foreach ($employeeNonAktif as $ep)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $ep->nama }}</td>
                    <td>{{ $ep->jenis_kelamin }}</td>
                    <td>{{ $ep->tempat_lahir . ", " . date('j F Y', strtotime( $ep->tanggal_lahir))}}</td> 
                    <td>{{ $ep->email }}</td>
                    <td>{{ $ep->phone }}</td>
                    <td>{{ $ep->alamat }}</td>
                    <td>{{ date('j F Y', strtotime( $ep->hiredate))}}</td>
                    <td>{{ $ep->role }}</td>
                    <td class="text-center"><a href="{{ route('employee.edit', $ep->id) }}" class="btn btn-sm btn-primary"><i class='bx bx-edit-alt'></i></a></td>
                    <td class="text-center"><button onclick="aktifkan({{ $ep->id }})" class="btn btn-sm btn-success"><i class='bx bx-power-off'></i></button></td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection

@section('script')
<script>
    let table = new DataTable('#daftarEmployeeAktif, #daftarEmployeeNonAktif');

    function nonaktifkan(id) {
        $.ajax({
            type: 'POST',
            url: "{{ route('employee.nonaktifkan') }}",
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
            url: "{{ route('employee.aktifkan')}}",
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


