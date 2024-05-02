@extends('layout.template')
@section('content')
<div class="content">
    <div class="row" style="padding-top: 25px;">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <a href="{{ route('productAktif.index')}}" style="text-decoration: none">
                <div class="card card-stats">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning"> 
                                    <i class="bx bxs-package text-warning" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Produk</p>
                                    <p class="card-title">{{$jumlahProduk}}<p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <a href="{{ url('productbundling')}}" style="text-decoration: none">
                <div class="card card-stats">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5 col-md-2">
                                <div class="icon-big text-center icon-warning"> 
                                    <i class="bx bxs-inbox text-success" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-10">
                                <div class="numbers">
                                    <p class="card-category">Produk Bundling</p>
                                    <p class="card-title">{{$jumlahProdukBundling}}<p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <a href="{{ url('kategories')}}" style="text-decoration: none">
                <div class="card card-stats">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning"> 
                                    <i class="bx bx-category text-danger" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Kategori</p>
                                    <p class="card-title">{{$jumlahKategori}}<p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <a href="{{ url('subkategories')}}" style="text-decoration: none">
                <div class="card card-stats">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning"> 
                                    <i class="bx bx-category-alt text-primary" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Subkategori</p>
                                    <p class="card-title">{{$jumlahSubkategori}}<p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" >
            <div class="card" style="height: 60vh;">
                <div class="card-body" style="display: flex; justify-content: center; align-items: center;">
                  <h6 style="text-align: center; opacity: 50%;">Sistem Market Basket Analysis untuk Menentukkan Produk Bundling Supermarket</h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection