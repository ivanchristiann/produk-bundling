@extends('layout.template')

@section('content')
@if (session('status'))
<div class="alert alert-success">{{session('status')}}</div>
@endif
<div style="margin-top: 20px; margin-bottom: 20px; font-size: 20px;">
    <strong>Nilai Kriteria Topsis</strong>
</div> 

{{-- <a href="{{ route('topsis.proses') }}" class="btn btn-success btn-m" style="float: right;"><i class="fa fa-plus"></i> Proses Topsis</a> --}}

<div class="alert alert-success">
    <strong>Informasi</strong>
    <ul>
        <li>Range angka yang dimasukkan mulai dari 0 hingga 10</li>
        <li>Semakin besar angka yang dimasukkan maka kriteria tersebut semakin berpengaruh dalam menentukkan produk substitusi</li>
        <li>Produk substitusi hanya menampilkan produk dari subkategori yang sama</li>
        <li>Terdapat beberapa kriteria yang digunakan untuk menentukkan produk substitusi yaitu Berat, harga dan hashtag</li>
        <li>Apabila terdapat produk yang tidak memiliki berat spesifik maka dapat ditulis 0 pada beratnya</li>
    </ul>
</div>

<form method="POST" action="{{route('topsis.store')}}">
    @csrf
    <div class="row">
        @foreach ($topsis as $tp)
            <div class="form-group">
                <strong>{{$tp->kriteria}}</strong><label>&nbsp;&nbsp;(1 - 10)</label>
                <input type="number" name="{{$tp->kriteria}}" class="form-control topsis" id="{{$tp->kriteria}}" max="10" min="1" value="{{$tp->nilai}}" required>
            </div>
        @endforeach
        <div class="form-group">
            <button type="submit" class="btn btn-primary" style="margin-top: 20px; width: 100%;" id="btnSimpan">                
                <i class='bx bx-save'></i> Simpan
            </button>
        </div>
    </div>
</form>

@endsection

@section('script')
<script>
$(".topsis").on("input", function() {
    var maxValue = ($(this).attr("max"));
    var minValue = ($(this).attr("min"));
    var inputValue = parseFloat($(this).val());

    if (inputValue > maxValue) {
        $(this).val(maxValue);
    }
    if (inputValue < minValue) {
        $(this).val(minValue);
    }
});
</script>
@endsection
