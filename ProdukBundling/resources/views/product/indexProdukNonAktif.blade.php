@extends('layout.template')

@section('content')
<div class="portlet-title">
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        Daftar Produk NonAktif
    </div>
    <div style="float: right; margin: 15px;">
        <a href="{{ route('product.create') }}" class="btn btn-success btn-m"><i class="fa fa-plus"></i> Add Produk</a>
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
    <div class="container px-2 px-lg-2 mt-2 productNonAktif-grid" style="display: block;">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            @foreach ($produkNonAktif as $productNonAktif)
            <div class="col mb-5">
                <div class="card h-100">
                    <img class="card-img-top" src="{{ asset('../assets/images/product/'. $productNonAktif->gambar) }}" alt="{{ $productNonAktif->nama }}" height="200px"/>
                    <div class="card-body p-4">
                        <div class="text-center">
                            <strong>{{ $productNonAktif->nama }}</strong><br>
                            {{ App\Http\Controllers\ProductController::rupiah($productNonAktif->harga_jual)}}
                            <br>
                            Stok: {{ $product->jumlahStok ?? 0 }}

                            <br>
                            <a href="{{ route('product.detailProduk', $productNonAktif->id) }}" class="btn btn-sm btn-success" title="Detail"><i class='bx bx-detail'></i></a> 
                            <button onclick="aktifkan({{ $productNonAktif->id }})" class="btn btn-sm btn-success" title="Nonaktifkan"><i class='bx bx-power-off'></i></button>      
                            <a href="{{ route('product.edit', $productNonAktif->id) }}" class="btn btn-sm btn-primary" title="Edit"><i class='bx bx-edit-alt'></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="pagination-container d-flex justify-content-end">
        @if (count($produkNonAktif) > 0)
            {{ $produkNonAktif->links('pagination::bootstrap-4') }}
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

    $("#search").on("keypress", function () {
        if (event.keyCode === 13) {
            var categoryId = $("#namaKategori").val();
            var searchKeyword = $('#searchProduk').val();
            window.location.href = "{{ route('productNonAktif.index', ['categoryId' => ''])}}/" + categoryId + "/" + searchKeyword;
        }
    });

    $("#namaKategori").on("change", function () {
        var categoryId = $("#namaKategori").val();
        var searchKeyword = $('#searchProduk').val();

        window.location.href = "{{ route('productNonAktif.index', ['categoryId' => ''])}}/" + categoryId + "/" + searchKeyword;
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

 