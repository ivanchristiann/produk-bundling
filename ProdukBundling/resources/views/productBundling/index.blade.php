@extends('layout.template')

@section('content')
<div style="margin-top: 20px; margin-bottom: 20px;">
    <strong style="font-size: 25px;">Daftar Produk Bundling</strong>
    @if (str_contains(Auth::user()->role, 'SuperAdmin') || str_contains(Auth::user()->role, 'Admin'))
        <a href="{{ route('productbundling.createProdukBundling') }}"  style="float: right;" class="btn btn-success btn-m"><i class="fa fa-plus"></i> Add Produk Bundling</a>
    @endif
</div>
@if (session('status'))
    <div class="alert alert-success">{{session('status')}}</div>
@endif
    <div class="container px-2 px-lg-2 mt-2">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            @foreach ($productBundlings as $product)
            <div class="col mb-5">
                <div class="card h-100">
                    <img class="card-img-top" src="{{ asset('../assets/images/product/'. $product->gambar) }}" alt="{{ $product->nama }}" height="250px"/>

                    <div class="card-body p-4">
                        <div class="text-center">
                            <strong>{{ $product->nama }}</strong><br>
                            {{ App\Http\Controllers\ProductController::rupiah($product->harga_jual)}}
                            <br>
                            Stok : {{$product->jumlahStok}}
                            <br>
                            <a href="{{ route('productbundling.detailProdukBundling', $product->id) }}" class="btn btn-default">Detail</a> 
                            <button onclick="nonaktifkan({{ $product->id }})" class="btn btn-danger"><i class='bx bx-power-off'></i></button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="pagination-container d-flex justify-content-end">
        {{ $productBundlings->links('pagination::bootstrap-4') }}
    </div>


    <div style="margin-top: 20px; margin-bottom: 20px;">
        <strong style="font-size: 25px;">Daftar Produk Bundling Nonaktif</strong>
    </div>
    <div class="container px-2 px-lg-2 mt-2">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            @foreach ($productBundlingNonaktif as $productNonaktif)
            <div class="col mb-5">
                <div class="card h-100">
                    <img class="card-img-top" src="{{ asset('../assets/images/product/'. $productNonaktif->gambar) }}" alt="{{ $productNonaktif->nama }}" height="250px"/>
                    <div class="card-body p-4">
                        <div class="text-center">
                            <strong>{{ $productNonaktif->nama }}</strong><br>
                            {{ App\Http\Controllers\ProductController::rupiah($productNonaktif->harga_jual)}}
                            <br>
                            Stok : {{$productNonaktif->jumlahStok}}
                            <br>
                            <a href="{{ route('productbundling.detailProdukBundling', $productNonaktif->id) }}" class="btn btn-default">Detail</a> 
                            @if (str_contains(Auth::user()->role, 'SuperAdmin'))
                                <button onclick="aktifkan({{ $productNonaktif->id }})" class="btn btn-success"><i class='bx bx-power-off'></i></button> 
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="pagination-container d-flex justify-content-end">
        {{ $productBundlingNonaktif->links('pagination::bootstrap-4') }}
    </div>
@endsection

@section('script')
<script>
    function nonaktifkan(id) {
        $.ajax({
            type: 'POST',
            url: "{{ route('product.nonaktifkan') }}",
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
            url: "{{ route('product.aktifkan')}}",
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