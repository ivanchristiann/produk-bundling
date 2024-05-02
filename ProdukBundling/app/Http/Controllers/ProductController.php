<?php

namespace App\Http\Controllers;

use App\Models\DetailBundling;
use App\Models\DetailTransaksiPenjualan;
use App\Models\Kategories;
use App\Models\Product;
use App\Models\Satuan;
use App\Models\Stok;
use App\Models\Subkategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($categoryId = "All", $search = "")
    {
        $produkNonAktif = Product::where('status', "0")->get();

        if ($categoryId == "All") {
            $produkAktif = Product::where('status', 1)->where('nama', 'like', '%'.$search.'%')->paginate(8);
        } else {
            $produkAktif = DB::table('products')
                ->join('subkategories', 'subkategories.id', '=', 'products.subkategoris_id')
                ->where('subkategories.kategories_id', '=', $categoryId)
                ->where('products.nama', 'like', '%'.$search.'%')
                ->select('products.*')
                ->paginate(8);
        }
        $kategories = Kategories::all();
        return view('product.indexProdukAktif', compact('produkNonAktif', 'produkAktif', 'kategories', 'categoryId', 'search'));
    }

    public function indexProdukAktif($categoryId = "All", $search = "")
    {
        if ($categoryId == "All") {
            $produkAktif = DB::table('products as p')
                ->where('p.nama', 'like', '%'.$search.'%')
                ->where('status', 1)
                ->where('is_bundling', '0')
                ->select('p.*', DB::raw('(SELECT SUM(jumlah) FROM stoks WHERE products_id = p.id) as jumlahStok'))
                ->paginate(60);
        } else {
            $produkAktif = DB::table('products as p')
                ->join('subkategories', 'subkategories.id', '=', 'p.subkategoris_id')
                ->where('subkategories.kategories_id', '=', $categoryId)
                ->where('p.nama', 'like', '%'.$search.'%')
                ->where('status', 1)
                ->where('is_bundling', '0')
                ->select('p.*', DB::raw('(SELECT SUM(jumlah) FROM stoks WHERE products_id = p.id) as jumlahStok'))
                ->paginate(60);
        }
        $kategories = Kategories::all();
        return view('product.indexProdukAktif', compact('produkAktif', 'kategories', 'categoryId', 'search'));
    }

    public function indexProdukNonAktif($categoryId = "All", $search = "")
    {
        if ($categoryId == "All") {
            $produkNonAktif = DB::table('products as p')
            ->where('p.nama', 'like', '%'.$search.'%')
            ->where('status', '0')
            ->where('is_bundling', '0')
            ->select('p.*', DB::raw('(SELECT SUM(jumlah) FROM stoks WHERE products_id = p.id) as jumlahStok'))
            ->paginate(60);
        } else {
            $produkNonAktif = DB::table('products as p')
                ->join('subkategories', 'subkategories.id', '=', 'p.subkategoris_id')
                ->where('subkategories.kategories_id', '=', $categoryId)
                ->where('p.nama', 'like', '%'.$search.'%')
                ->where('status', '0')
                ->where('is_bundling', '0')
                ->select('p.*', DB::raw('(SELECT SUM(jumlah) FROM stoks WHERE products_id = p.id) as jumlahStok'))
                ->paginate(60);
        }
        $kategories = Kategories::all();
        return view('product.indexProdukNonAktif', compact('produkNonAktif', 'kategories', 'categoryId', 'search'));
    }


    public static function detailProduk(string $id)
    {
        $product = Product::where('id', $id)->first();

        $stok = Stok::where('products_id', $id)
            ->selectRaw('SUM(jumlah) as total_jumlah')
            ->first();

        $rincianStokDanExp = DB::table('stoks')
            ->select('jumlah', 'exp_date', DB::raw('DATEDIFF(exp_date, NOW()) AS LamaWaktu'))
            ->where('products_id', $id)
            ->where('jumlah', '!=', 0)
            ->orderBy('exp_date', 'asc')
            ->get();

        return view('product.detail', compact('product', 'stok', 'rincianStokDanExp'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategories = Kategories::all();
        $subkategories = Subkategories::all();
        $satuans = Satuan::all();
        return view('product.create', compact('kategories', 'subkategories', 'satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'namaProduct' => 'required',
            'barcode' => 'required',
            'harga' => 'required',
            'satuan' => ['required', 'not_in:-- Pilih Satuan --'],
            'namaKategori' => ['required', 'not_in:-- Pilih Kategori --'],
            'subkategori' => ['required', 'not_in:-- Pilih Subkategori --'],
            'berat' => 'required',
        ], [
            'namaProduct.required' => 'Nama Produk wajib diisi',
            'barcode.required' => 'Barcode wajib diisi',
            'harga.required' => 'Harga wajib diisi',
            'satuan.not_in' => 'Silahkan pilih satuan terlebih dahulu',
            'namaKategori.not_in' => 'Silahkan pilih kategori terlebih dahulu',
            'subkategori.not_in' => 'Silahkan pilih Subkategori terlebih dahulu',
            'berat.required' => 'Berat wajib diisi',

        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
 
        $produk = new Product();
        $produk->nama = $request->get('namaProduct');
        $produk->deskripsi = $request->get('deskripsi') === null ? '-' : $request->get('deskripsi');
        $produk->barcode = $request->get('barcode');
        $produk->harga_jual = $request->get('harga');
        $produk->subkategoris_id = $request->get('subkategori');
        $produk->satuans_id = $request->get('satuan');

        $produk->berat = $request->get('berat');
        $produk->status = '1';
        $produk->is_bundling = '0';
        if ($request->hasFile('gambarProduk')) {
            $file = $request->file('gambarProduk');
            $imgFolder = "assets/images/product";
            $imgFile = time() . "_" . (substr(($file->getClientOriginalName()), 0, 100)) . "." . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $file->move($imgFolder, $imgFile);
            $produk->gambar = $imgFile;
        }

        $hashtagSave = '';
        if (!empty($request->get('hashtag'))) {
            foreach ($request->get('hashtag') as $hastag) {
                $hashtagSave .= '#' . strtolower($hastag) . ', ';
            }
            $produk->hashtag = rtrim($hashtagSave, ', ');
        }else{
            $produk->hashtag = $hashtagSave;
        }

        $produk->created_at = now("Asia/Bangkok");
        $produk->updated_at = now("Asia/Bangkok");
        $produk->save();
        return redirect()->route('productAktif.index')->with('status', 'New product ' .  $produk->nama . ' is already inserted');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategories = Kategories::all();
        $subkategories = Subkategories::all();
        $satuans = Satuan::all();

        $product = DB::table('products')
            ->join('subkategories', 'subkategories.id', '=', 'products.subkategoris_id')
            ->join('kategories', 'kategories.id', '=', 'subkategories.kategories_id')
            ->where('products.id', $id)
            ->select('products.*', 'subkategories.kategories_id')
            ->first();

        $hashtags = [];
        $count = 1;

        if ($product->hashtag != '') {
            $hashtag = str_replace("#", "", $product->hashtag);
            $hashtag = explode(", ", $hashtag);
            foreach ($hashtag as $hashtag => $nama) {
                $hashtags[] = ['count' => $count++, 'nama' => $nama];
            }
        }
        return view('product.edit', compact('kategories', 'subkategories', 'satuans', 'product', 'hashtags', 'count'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'namaProduct' => 'required',
            'barcode' => 'required',
            'harga' => 'required',
            'satuan' => ['required', 'not_in:-- Pilih Satuan --'],
            'namaKategori' => ['required', 'not_in:-- Pilih Kategori --'],
            'subkategori' => ['required', 'not_in:-- Pilih Subkategori --'],
            'berat' => 'required',
        ], [
            'namaProduct.required' => 'Nama Produk wajib diisi',
            'barcode.required' => 'Barcode wajib diisi',
            'harga.required' => 'Harga wajib diisi',
            'satuan.not_in' => 'Silahkan pilih satuan terlebih dahulu',
            'namaKategori.not_in' => 'Silahkan pilih kategori terlebih dahulu',
            'subkategori.not_in' => 'Silahkan pilih Subkategori terlebih dahulu',
            'berat.required' => 'Berat wajib diisi',

        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
 
        $produk = Product::find($id);

        $produk->nama = $request->get('namaProduct');
        $produk->deskripsi = $request->get('deskripsi');
        $produk->barcode = $request->get('barcode');
        $produk->harga_jual = $request->get('harga');
        $produk->subkategoris_id = $request->get('subkategori');
        $produk->satuans_id = $request->get('satuan');

        $produk->berat = $request->get('berat');

        if ($request->hasFile('gambarProduk')) {
            $file = $request->file('gambarProduk');
            $imgFolder = "assets/images/product";
            $imgFile = time() . "_" . (substr(($file->getClientOriginalName()), 0, 100)) . "." . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $file->move($imgFolder, $imgFile);
            $produk->gambar = $imgFile;
        }
       
        $hashtagSave = '';
        if (!empty($request->get('hashtag'))) {
            foreach ($request->get('hashtag') as $hastag) {
                $hashtagSave .= '#' . strtolower($hastag) . ', ';
            }
            $produk->hashtag = rtrim($hashtagSave, ', ');
        }else{
            $produk->hashtag = $hashtagSave;
        }
       

        $produk->updated_at = now("Asia/Bangkok");
        $produk->save();
        return redirect()->route('product.index')->with('status', 'Edit Product ' .  $produk->nama . ' is done');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function nonaktifkan(Request $request)
    {
        $data = Product::find($request->get('id'));
        $data->status = '0';
        $data->save();
        return response()->json(array('status' => 'success'), 200);
    }

    public function aktifkan(Request $request)
    {
        $data = Product::find($request->get('id'));
        $data->status = '1';
        $data->save();
        return response()->json(array('status' => 'success'), 200);
    }

    public static function rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }

    public static function produkBundling()
    {
        $productBundlings = DB::table('products as p')
            ->where('status', 1)
            ->where('is_bundling', '1')
            ->select('p.*', DB::raw('(SELECT SUM(jumlah) FROM stoks WHERE products_id = p.id) as jumlahStok'))
            ->paginate(60);

        $productBundlingNonaktif = DB::table('products as p')
            ->where('status', '0')
            ->where('is_bundling', '1')
            ->select('p.*', DB::raw('(SELECT SUM(jumlah) FROM stoks WHERE products_id = p.id) as jumlahStok'))
            ->paginate(60);
        return view('productBundling.index', compact('productBundlings', 'productBundlingNonaktif'));
    }

    public static function createProdukBundling()
    {
        $produkAktif = Product::where('status', '1')->get();

        $kategories = Kategories::all();
        $subkategories = Subkategories::all();
        $satuans = Satuan::all();
        
        return view('productBundling.create', compact('produkAktif', 'kategories', 'subkategories', 'satuans'));
    }

    public static function createProdukBundlingApriori($idProduk1, $idProduk2)
    {
        $produkAktif = Product::where('status', '1')->get();

        $produk1 = Product::where('id', $idProduk1)->first();
        $produk2 = Product::where('id', $idProduk2)->first();
  
        $kategories = Kategories::all();
        $subkategories = Subkategories::all();
        $satuans = Satuan::all();
        return view('productBundling.create', compact('produkAktif', 'produk1', 'produk2', 'kategories', 'subkategories', 'satuans'));
    }

    public static function createProdukBundlingSubstitusi($idProduk1, $idProduk2, $startDate = null, $endDate = null, $metode)
    {
        $produkAktif = Product::where('status', '1')->get();
        $produk1 = Product::where('id', $idProduk1)->first();
        $produk2 = Product::where('id', $idProduk2)->first();

        $topsisController = new TopsisController();
        $produkSubstitusi1 = $topsisController->proses($produk1->id, $produk1->subkategoris_id, $startDate, $endDate, $metode);
        $produkSubstitusi2 = $topsisController->proses($produk2->id, $produk2->subkategoris_id, $startDate, $endDate, $metode);

        $kategories = Kategories::all();
        $subkategories = Subkategories::all();
        $satuans = Satuan::all();
        return view('productBundling.create', compact('produkAktif','produkSubstitusi1','produkSubstitusi2', 'produk1', 'produk2', 'kategories', 'subkategories', 'satuans'));
    }

    public static function checkStatusProduk($idProduk)
    {
        $status = Product::where('id', $idProduk)->select('status')->first();
        return $status;
    }

    public static function storeProdukBundling(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'namaProdukBundling' => 'required',
            'barcode' => 'required',
            'hargaJual' => 'required',
            'jumlahProduk1' => 'required',
            'jumlahProduk2' => 'required',
            'jumlahBundling' => 'required',
        ], [
            'namaProdukBundling.required' => 'Nama Produk wajib diisi',
            'barcode.required' => 'Barcode wajib diisi',
            'hargaJual.required' => 'Harga wajib diisi',
            'jumlahProduk1.required' => 'Jumlah produk 1 wajib diisi',
            'jumlahProduk2.required' => 'Jumlah produk 2 wajib diisi',
            'jumlahBundling.required' => 'Jumlah bundling wajib diisi',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $produk = new Product();
        $produk->subkategoris_id = $request->get('subkategori');
        $produk->satuans_id = $request->get('satuan');
        $produk->nama = $request->get('namaProdukBundling');
        $produk->deskripsi = $request->get('deskripsi');
        $produk->barcode = $request->get('barcode');
        $produk->harga_pokok_penjualan = $request->get('hppKeduaProduk');
        $produk->harga_jual = $request->get('hargaJual');

        if ($request->hasFile('gambarProduk')) {
            $file = $request->file('gambarProduk');
            $imgFolder = "assets/images/product";
            $imgFile = time() . "_" . (substr(($file->getClientOriginalName()), 0, 100)) . "." . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $file->move($imgFolder, $imgFile);
            $produk->gambar = $imgFile;
        }
        $produk->berat = 0;
        $produk->status = '1';
        $produk->is_bundling = '1';
        $produk->hashtag = '';

        $produk->created_at = now("Asia/Bangkok");
        $produk->updated_at = now("Asia/Bangkok");
        $produk->save();

        if($request->get('product1') === $request->get('product2')){
            $detailBundling1 = new DetailBundling();
            $detailBundling1->product_bundling_id = $produk->id;
            $detailBundling1->products_id = $request->get('product1');
            $detailBundling1->jumlah = $request->get('jumlahProduk1') + $request->get('jumlahProduk2');
            $detailBundling1->created_at = now("Asia/Bangkok");
            $detailBundling1->updated_at = now("Asia/Bangkok");
            $detailBundling1->save();
    
        }else{
            $detailBundling1 = new DetailBundling();
            $detailBundling1->product_bundling_id = $produk->id;
            $detailBundling1->products_id = $request->get('product1');
            $detailBundling1->jumlah = $request->get('jumlahProduk1');
            $detailBundling1->created_at = now("Asia/Bangkok");
            $detailBundling1->updated_at = now("Asia/Bangkok");
            $detailBundling1->save();
    
            $detailBundling2 = new DetailBundling();
            $detailBundling2->product_bundling_id = $produk->id;
            $detailBundling2->products_id = $request->get('product2');
            $detailBundling2->jumlah = $request->get('jumlahProduk2');
            $detailBundling2->created_at = now("Asia/Bangkok");
            $detailBundling2->updated_at = now("Asia/Bangkok");
            $detailBundling2->save();
        }

        $stok = new Stok();
        $stok->products_id = $produk->id;
        $stok->exp_date = $request->get('tanggalKadarluarsa');
        $stok->jumlah = $request->get('jumlahBundling');
        $stok->save();

        $stokProduk1 = Stok::where('products_id', $request->get('product1'))->where('jumlah', '>', 0)->orderBy('exp_date', 'asc')->get();
        $jumlahProduk1 = $request->get('jumlahBundling') * $request->get('jumlahProduk1');
        foreach ($stokProduk1 as $stok) {
            if ($stok->jumlah >= $jumlahProduk1) {
                $jumlah = $stok->jumlah - $jumlahProduk1;
                Stok::updateOrInsert(
                    ['products_id' => $request->get('product1'),'exp_date' => $stok->exp_date],
                    ['jumlah' => $jumlah,'updated_at' => now("Asia/Bangkok")]);
                break;
            } else {
                $jumlahProduk1 -= $stok->jumlah;
                Stok::updateOrInsert(
                    ['products_id' => $request->get('product1'), 'exp_date' => $stok->exp_date],
                    ['jumlah' => 0,'updated_at' => now("Asia/Bangkok")]);
            }
        }

        $stokProduk2 = Stok::where('products_id', $request->get('product2'))->where('jumlah', '>', 0)->orderBy('exp_date', 'asc')->get();
        $jumlahProduk2 = $request->get('jumlahBundling') * $request->get('jumlahProduk2');;
        foreach ($stokProduk2 as $stok) {
            if ($stok->jumlah >= $jumlahProduk2) {
                $jumlah = $stok->jumlah - $jumlahProduk2;
                Stok::updateOrInsert(
                    ['products_id' => $request->get('product2'), 'exp_date' => $stok->exp_date],
                    ['jumlah' => $jumlah, 'updated_at' => now("Asia/Bangkok")]);
                break;
            } else {
                $jumlahProduk1 -= $stok->jumlah;
                Stok::updateOrInsert(
                    ['products_id' => $request->get('product2'), 'exp_date' => $stok->exp_date],
                    ['jumlah' => 0, 'updated_at' => now("Asia/Bangkok")]
                );
            }
        }
        return redirect()->route('productbundling.produkBundling')->with('status', 'New Product Bundling is already inserted');
    }

    public static function detailProdukBundling(string $id)
    {
        $productBundlings = Product::where('id', $id)->first();
        $stok = Stok::where('products_id', $id)
            ->selectRaw('SUM(jumlah) as total_jumlah, MIN(exp_date) as exp_date_terdekat')
            ->first();

        $detailProdukBundlings = DB::table('products as p')
            ->join('detail_bundlings as DB', 'db.products_id', '=', 'p.id')
            ->where('product_bundling_id', $id)
            ->select('db.*', 'p.*')
            ->get();

        $jumlahTerjual = DetailTransaksiPenjualan::where('products_id', $id)
            ->sum('jumlah');
        return view('productBundling.detail', compact('productBundlings', 'detailProdukBundlings', 'stok', 'jumlahTerjual'));
    }

    public static function getExpDate(Request $request)
    {
        $products_id = $request->get('products_id');
        $exp_date = Stok::where('products_id', $products_id)
            ->selectRaw('SUM(jumlah) as total_jumlah, MIN(exp_date) as exp_date_terdekat')
            ->where('jumlah', '!=', 0)
            ->first();
        return response()->json($exp_date);
    }
}
