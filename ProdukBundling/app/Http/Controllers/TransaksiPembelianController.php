<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailTransaksiPembelian;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Stok;
use App\Models\Supplier;
use App\Models\TransaksiPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class TransaksiPembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($startDate = null, $endDate = null, $status = "All")
    {
        if ($startDate === null && $endDate === null) {
            $startDate = now()->subMonth();
            $endDate = now();
        }
        if (!in_array($status, ['All', 'Pending', 'Success'])) {
            $status = 'All';
        }
        $transaksiPembelians = DB::table('transaksi_pembelians as tp')
            ->join('suppliers as s', 's.id', '=', 'tp.suppliers_id')
            ->select('tp.*', 's.nama as namaSupplier', DB::raw('DATEDIFF(tp.jatuh_tempo_pembayaran, NOW()) AS LamaWaktu'))
            ->whereBetween('tp.tanggal', [$startDate, $endDate]);
        if($status != "All"){
            $transaksiPembelians->where('status_pembayaran',$status);
        }
        $transaksiPembelians = $transaksiPembelians->orderBy('status_pembayaran', 'asc')
            ->orderBy('tp.tanggal', 'desc')
            ->get();

        return view('transaksipembelian.index', compact('transaksiPembelians', 'startDate','endDate','status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $supplierAktif = Supplier::all()->where('status', '1');
        $employeeAktif = Employee::all()->where('id', Auth::id());
        $produkAktif = Product::all();
        return view('transaksipembelian.create', compact('supplierAktif', 'employeeAktif', 'produkAktif'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $arrayProduk = json_decode($request->get('arrayProduk'), true);
        if (empty($arrayProduk)) {
            return back()->withInput()->with('error', 'Silahkan Masukkan Produk yang Dibeli.')->withInput();
        }

        $total = 0;
        foreach ($arrayProduk as $produk) {
            $total += $produk['harga'] * $produk['jumlah'];
        }

        $transaksi = new TransaksiPembelian();
        $transaksi->suppliers_id = $request->get('namaSupplier');
        $transaksi->employees_id = Auth::id();
        $transaksi->tanggal = $request->get('tanggalTransaksi');
        $transaksi->total = $total;

        $statusPembayaran = $request->get('statusPembayaran'); 
        if($statusPembayaran == "Success"){
            $transaksi->tanggal_bayar = $request->get('tanggalTransaksi');
            $transaksi->employees_bayar = Auth::id();
        }
        $transaksi->status_pembayaran = $statusPembayaran;
        $transaksi->jatuh_tempo_pembayaran = $request->get('tanggalJatuhTempo');
        $transaksi->created_at = now("Asia/Bangkok");
        $transaksi->updated_at = now("Asia/Bangkok");
        $transaksi->save();

        foreach ($arrayProduk as $ap) {
            // Menentukan harga_pokok_penjualan dengan rumus
            //(JumlahProduk saat ini * HPP saat ini) + (Jumlah produk beli * harga produk) / (Total produk saat ini dan beli)
            $produk = Product::find($ap['id']);
            $jumlahProduct = DB::table('stoks')->where('products_id', '=', $ap['id'])->sum('jumlah');
            $produk->harga_pokok_penjualan = (($jumlahProduct * $produk->harga_pokok_penjualan) + ($ap['jumlah'] * $ap['harga'])) / ($ap['jumlah'] + $jumlahProduct);
            $produk->save();

            $produkDetail = new DetailTransaksiPembelian();
            $produkDetail->products_id = $ap['id'];
            $produkDetail->jumlah = $ap['jumlah'];
            $produkDetail->harga = $ap['harga'];
            $produkDetail->transaksi_pembelians_id = $transaksi->id;

            $produkDetail->created_at = now("Asia/Bangkok");
            $produkDetail->updated_at = now("Asia/Bangkok");
            $produkDetail->save();

            $checkStock = Stok::where('products_id', $ap['id'])->where('exp_date', $ap['exp'])->first();
            if ($checkStock) {
                $jumlah = $checkStock->jumlah + $ap['jumlah'];
                Stok::updateOrInsert(
                    [
                        'products_id' => $ap['id'],
                        'exp_date' => $ap['exp'],
                    ],
                    [
                        'jumlah' => $jumlah,
                        'updated_at' => now("Asia/Bangkok"),
                    ]
                );
            } else {
                $stok = new Stok();
                $stok->products_id = $ap['id'];
                $stok->exp_date = $ap['exp'];
                $stok->jumlah = $ap['jumlah'];
                $stok->created_at = now("Asia/Bangkok");
                $stok->updated_at = now("Asia/Bangkok");
                $stok->save();
            }
        }
        return redirect()->route('transaksiPembelian.index')->with('status', 'New Transaksi Pembelian is already inserted');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transaksiPembelian = TransaksiPembelian::find($id);

        $transaksiPembelian->employees_bayar = Auth::id();
        $transaksiPembelian->status_pembayaran = 'Success';

        $transaksiPembelian->tanggal_bayar = now("Asia/Bangkok");

        $transaksiPembelian->created_at = now("Asia/Bangkok");
        $transaksiPembelian->updated_at = now("Asia/Bangkok");
        $transaksiPembelian->save();
        return redirect()->route('transaksiPembelian.index')->with('status', 'Pembayaran success');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function detail(string $id)
    {
        $transaksiPembelians = DB::table('transaksi_pembelians as tp')
            ->join('suppliers as s', 's.id', '=', 'tp.suppliers_id')
            ->join('employees as e', 'e.id', '=', 'tp.employees_id')
            ->select('tp.*', 's.nama as namaSupplier', 'e.nama  as namaEmployee')
            ->where('tp.id', '=', $id)
            ->first();

        $detailTransaksi = DB::table('detail_transaksi_pembelians as dtp')
            ->join('products as p', 'p.id', '=', 'dtp.products_id')
            ->select('dtp.*', 'p.*')
            ->where('dtp.transaksi_pembelians_id', '=', $id)
            ->get();

        return view('transaksiPembelian.detail', compact('transaksiPembelians', 'detailTransaksi'));
    }

    public static function rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }

    public function pembayaran(string $id)
    {
        $transaksiPembelians = DB::table('transaksi_pembelians as tp')
            ->join('suppliers as s', 's.id', '=', 'tp.suppliers_id')
            ->join('employees as e', 'e.id', '=', 'tp.employees_id')
            ->select('tp.*', 's.nama as namaSupplier', 'e.nama  as namaEmployee')
            ->where('tp.id', '=', $id)
            ->first();

        $detailTransaksi = DB::table('detail_transaksi_pembelians as dtp')
            ->join('products as p', 'p.id', '=', 'dtp.products_id')
            ->select('dtp.*', 'p.*')
            ->where('dtp.transaksi_pembelians_id', '=', $id)
            ->get();

        return view('transaksiPembelian.updatePembayaran', compact('transaksiPembelians', 'detailTransaksi'));
    }
}
