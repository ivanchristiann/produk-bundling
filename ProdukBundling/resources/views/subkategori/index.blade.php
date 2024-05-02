@extends('layout.template')

@section('content')
<div style="margin-top: 20px; margin-bottom: 20px;">
    <strong style="font-size: 25px;">Daftar Subkategori</strong>
    @if (str_contains(Auth::user()->role, 'SuperAdmin'))
        <a href="{{ route('subkategories.create') }}" class="btn btn-success btn-m" style="float: right;"><i class="fa fa-plus"></i> Add Subkategories</a>
    @endif
</div>
@if (session('status'))
    <div class="alert alert-success">{{session('status')}}</div>
@endif

<table id="daftarsubKategories" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Deskripsi</th>
            @if (str_contains(Auth::user()->role, 'SuperAdmin'))
                <th>Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @if (count($subkategories) == 0)
        <tr>
            <td class="text-center" colspan="8">Tidak ada Subkategories yang terdata</td>
        </tr>
        @else
        @foreach ($subkategories as $kat)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $kat->nama }}</td>
            <td>{{ $kat->namaKategori }}</td>
            <td>{{ $kat->deskripsi }}</td>
            @if (str_contains(Auth::user()->role, 'SuperAdmin'))
                <td class="text-center"><a href="{{ route('subkategories.edit', $kat->id) }}" class="btn btn-sm btn-primary"><i class='bx bx-edit-alt'></i></a></td>
            @endif
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
@endsection

@section('script')
<script>
    let table = new DataTable('#daftarsubKategories');
</script>
@endsection


