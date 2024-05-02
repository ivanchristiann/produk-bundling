<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailTransaksiPenjualan;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Stok;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($startDate = null, $endDate = null)
    {
        if ($startDate === null && $endDate === null) {
            $startDate = now()->subMonth();
            $endDate = now();
        }  
        $transaksiPenjualans = DB::table('transaksi_penjualans as tp')
            ->join('employees as e', 'e.id', '=', 'tp.employees_id')
            ->select('tp.*', 'e.nama as namaEmployee')
            ->whereBetween('tp.tanggal', [$startDate, $endDate])
            ->orderBy('tp.id', 'desc')
            ->get();
        return view('transaksipenjualan.index', compact('transaksiPenjualans', 'startDate','endDate'));
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employeeAktif = Employee::all()->where('id', Auth::id());
        $produkAktif = Product::where('status', "1")->get();

        $stok = Stok::select('products_id', DB::raw('SUM(jumlah) as total_jumlah'))
            ->groupBy('products_id')
            ->get();
        return view('transaksiPenjualan.create', compact('employeeAktif', 'produkAktif', 'stok'));
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

        $transaksi = new TransaksiPenjualan();
        $transaksi->employees_id = Auth::id();
        $transaksi->tanggal = $request->get('tanggalTransaksi');
        $transaksi->grand_total = $total;
        $transaksi->created_at = now("Asia/Bangkok");
        $transaksi->updated_at = now("Asia/Bangkok");
        $transaksi->save();

        foreach ($arrayProduk as $ap) {
            $produkDetail = new DetailTransaksiPenjualan();
            $produkDetail->products_id = $ap['id'];
            $produkDetail->jumlah = $ap['jumlah'];
            $produkDetail->harga = $ap['harga'];
            $produkDetail->hpp = $ap['hpp'];

            $produkDetail->transaksi_penjualan_id = $transaksi->id;

            $produkDetail->created_at = now("Asia/Bangkok");
            $produkDetail->updated_at = now("Asia/Bangkok");
            $produkDetail->save();

            $stokProduk = Stok::where('products_id', $ap['id'])->where('jumlah', '>', 0)->orderBy('exp_date', 'asc')->get();
            foreach ($stokProduk as $stok) {
                if ($stok->jumlah >= $ap['jumlah']) {
                    $jumlah = $stok->jumlah - $ap['jumlah'];
                    Stok::updateOrInsert(
                        ['products_id' => $ap['id'], 'exp_date' => $stok->exp_date],
                        ['jumlah' => $jumlah, 'updated_at' => now("Asia/Bangkok")]
                    );
                    break;
                } else {
                    $ap['jumlah'] -= $stok->jumlah;
                    Stok::updateOrInsert(
                        ['products_id' => $ap['id'],'exp_date' => $stok->exp_date],
                        ['jumlah' => 0, 'updated_at' => now("Asia/Bangkok")]
                    );
                }
            }
        }
        return redirect()->route('transaksiPenjualan.index')->with('status', 'New Transaksi Penjualan is already inserted');
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
        //
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
        $transaksiPenjualans = DB::table('transaksi_penjualans as tp')
            ->join('employees as e', 'e.id', '=', 'tp.employees_id')
            ->select('tp.*', 'e.nama  as namaEmployee')
            ->where('tp.id', '=', $id)
            ->first();

        $detailTransaksi = DB::table('detail_transaksi_penjualans as dtp')
            ->join('products as p', 'p.id', '=', 'dtp.products_id')
            ->select('dtp.*', 'p.*')
            ->where('dtp.transaksi_penjualan_id', '=', $id)
            ->get();

        return view('transaksipenjualan.detail', compact('transaksiPenjualans', 'detailTransaksi'));
    }
    public static function rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }
}
