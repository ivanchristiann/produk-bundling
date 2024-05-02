@extends('layout.template')

@section('content')
@if (session('status'))
<div class="alert alert-success">{{session('status')}}</div>
@endif
<div style="margin-top: 20px; margin-bottom: 20px; font-size: 20px;">
    <strong>Nilai Kriteria Topsis</strong>
</div>

@dd($finalProdukSubstitusi);

@endsection

