@extends('layout.template')

@section('content')
<div class="portlet-title">
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        Daftar Produk 
    </div>
    <div style="float: right; margin: 15px;">
        @if (str_contains(Auth::user()->role, 'SuperAdmin'))
            <a href="{{ route('product.create') }}" class="btn btn-success btn-m"><i class="fa fa-plus"></i> Add Produk</a>
        @endif
    </div>
</div>
@if (session('status'))
<div class="alert alert-success">{{session('status')}}</div>
@endif
    <label for="kategori">Kategori : </label>
    <select class="form-select" aria-label="Default select example" name="namaKategori" id="namaKategori">
        <option value="All">All</option>
        @foreach ($kategories as $kategori)
            <option value="{{ $kategori->id }}" {{ $categoryId == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
        @endforeach
    </select>    

    <div class="form-group" style="margin-top: 15px; display: block;" id="search">
        <input type="text" id="searchProduk" class="form-control" placeholder="Cari produk..." value="{{$search}}">
    </div>
    
    @if (count($produkAktif) > 0)
        <div class="container px-2 px-lg-2 mt-2 productAktif-grid" style="display: block;">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                @foreach ($produkAktif as $product)
                <div class="col mb-5">
                    <div class="card h-100">
                        @if ($product->gambar != null)
                            <img class="card-img-top" src="{{ asset('../assets/images/product/'. $product->gambar) }}" alt="{{ $product->nama }}" height="200px"/>
                        @else
                            <img class="card-img-top" src="{{ asset('../assets/images/product/noImage.jpg') }}" alt="{{ $product->nama }}" height="200px"/>
                        @endif
                        <div class="card-body p-4">
                            <div class="text-center">
                                <strong>{{ $product->nama }}</strong><br>
                                {{ App\Http\Controllers\ProductController::rupiah($product->harga_jual)}}
                                <br>
                                Stok: {{ $product->jumlahStok ?? 0 }}
                                <br>
                                <a href="{{ route('product.detailProduk', $product->id) }}" class="btn btn-sm btn-success" title="Detail"><i class='bx bx-detail'></i></a> 
                                @if (str_contains(Auth::user()->role, 'SuperAdmin'))
                                    <button onclick="nonaktifkan({{ $product->id }})" class="btn btn-sm btn-danger" title="Nonaktifkan"><i class='bx bx-power-off'></i></button>      
                                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-primary" title="Edit"><i class='bx bx-edit-alt'></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @else 
        <div class="container" style="display: block;">
            <div class="row justify-content-center" style="font-size: 25px; font-weight: bold; margin-top:25px;">
                Tidak Ada Produk yang Terdata
            </div>
        </div>
    @endif
    <div class="pagination-container d-flex justify-content-end" id="paginateProdukAktif" style="display: block;">
        @if (count($produkAktif) > 0)
            {{ $produkAktif->links('pagination::bootstrap-4') }}            
        @endif
    </div>
</section>

@endsection

@section('script')
<script>
    // let table = new DataTable('#daftarProdukAktif');

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

    $("#search").on("keypress", function () {
        if (event.keyCode === 13) {
            var categoryId = $("#namaKategori").val();
            var searchKeyword = $('#searchProduk').val();
            window.location.href = "{{ route('productAktif.index', ['categoryId' => ''])}}/" + categoryId + "/" + searchKeyword;
        }
    });

    $("#namaKategori").on("change", function () {
        var categoryId = $("#namaKategori").val();
        var searchKeyword = $('#searchProduk').val();

        window.location.href = "{{ route('productAktif.index', ['categoryId' => ''])}}/" + categoryId + "/" + searchKeyword;
    });

    // $("#buttonGridProdukAktif").on("click", function () {
    //     $(".productAktif-grid").css('display', 'block');
    //     $(".productAktif-list").css('display', 'none');
    //     $("#search").css('display', 'block');
    // });
    // $("#buttonListProdukAktif").on("click", function () {
    //     $(".productAktif-grid").css('display', 'none');
    //     $(".productAktif-list").css('display', 'block');
    //     $("#search").css('display', 'none');
    // });

    // $("#buttonGridProdukNonAktif").on("click", function () {
    //     $(".productNonAktif-grid").css('display', 'block');
    //     $(".productNonAktif-listt").css('display', 'none');
    // });
    // $("#buttonListProdukNonAktif").on("click", function () {
    //     $(".productNonAktif-grid").css('display', 'none');
    //     $(".productNonAktif-list").css('display', 'block');
    // });


</script>
@endsection

 