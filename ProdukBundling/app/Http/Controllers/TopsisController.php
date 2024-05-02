<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksiPenjualan;
use App\Models\Stok;
use App\Models\Product;
use App\Models\Topsis;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;

class TopsisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topsis = Topsis::all();
        return view('topsis.index', compact('topsis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $timeUpdate = now("Asia/Bangkok");
        Topsis::where('kriteria', 'Berat')->update(['nilai' => $request->Berat, 'updated_at' => $timeUpdate]);
        Topsis::where('kriteria', 'Hashtag')->update(['nilai' => $request->Hashtag, 'updated_at' => $timeUpdate]);
        Topsis::where('kriteria', 'Harga')->update(['nilai' => $request->Harga, 'updated_at' => $timeUpdate]);

        $topsis = Topsis::all();
        return view('topsis.index', compact('topsis'));
    }

    public function proses($idProduk = "", $idSubKategori = "", $startDate = "", $endDate = "", $metode = "")
    {
        $arrayProduk = [];
        $productAsli = Product::where('id', $idProduk)->get()->toArray();
        $productSubstitusis = Product::where('subkategoris_id', $idSubKategori)
            ->where('id', '!=', $productAsli[0]['id'])
            ->get();        
        if($metode != 'jumlahDanExp' && $metode != 'topsis'){
            $metode = 'topsis';
        }

        if($metode == "jumlahDanExp"){
            foreach ($productSubstitusis as $productSubstitusi) {
                $product = Product::selectRaw('products.*, 
                        (SELECT SUM(dtp.jumlah) FROM detail_transaksi_penjualans dtp
                            INNER JOIN transaksi_penjualans tp ON dtp.transaksi_penjualan_id = tp.id
                            WHERE dtp.products_id = products.id AND tp.tanggal BETWEEN ? AND ?) as jumlahTerjual,
                        (SELECT exp_date FROM stoks WHERE products_id = products.id ORDER BY exp_date ASC LIMIT 1) as exp_date', [$startDate, $endDate])
                ->where('id', $productSubstitusi['id'])
                ->first();
    
                $product['jumlahTerjual'] = $product['jumlahTerjual'] ?? 0;
    
                $arrayProduk[] = $product;
            }      
            usort($arrayProduk, function ($produk1, $produk2) {
                $result = $produk1['jumlahTerjual'] <=> $produk2['jumlahTerjual'];
                if ($result === 0) {
                    return $produk1['exp_date'] <=> $produk2['exp_date'];
                }
                return $result;
            });
            
            $finalProdukSubstitusi = $arrayProduk;   
        }
        else if($metode == "topsis"){
            foreach ($productSubstitusis as $productSubstitusi) {
                $normalisasiProduk = $this->normalisasiProduk($productAsli, $productSubstitusi->toArray());
                if (!empty($normalisasiProduk)) {
                    $arrayProduk[] = $normalisasiProduk;
                }
            }

            $normalizedProduk = $this->normalizedDecisionMatrix($arrayProduk);

            $bobotKriteria = [];
            $bobots = Topsis::select('kriteria', 'nilai')->get()->all();
            foreach ($bobots as $bobot) {
                $bobotKriteria[$bobot->kriteria] = (float) $bobot->nilai;
            }

            $weightedNormalizedProduk = $this->weightedNormalizedDecisionMatrix($normalizedProduk, $bobotKriteria);

            $positifIdealSolution = $this->positifIdealSolution($weightedNormalizedProduk);
            $negatifIdealSolution = $this->negatifIdealSolution($weightedNormalizedProduk);

            $separasiPositifIdealSolution = $this->separasiPositifIdealSolution($weightedNormalizedProduk, $positifIdealSolution);
            $separasiNegatifIdealSolution = $this->separasiNegatifIdealSolution($weightedNormalizedProduk, $negatifIdealSolution);

            $relativeCloseness = $this->relativeCloseness($separasiPositifIdealSolution, $separasiNegatifIdealSolution);
            $finalProdukSubstitusi = $this->finalProdukSubstitusi($relativeCloseness, $startDate, $endDate);

            // return view('topsis.showTabel', compact('finalProdukSubstitusi'));
        }
        return $finalProdukSubstitusi;
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

    public function normalisasiHarga(int $harga, int $hargaProdukAsli)
    {
        // if ($harga < 10000) {
        //     $hargaNormalisasi = (int)floor($harga / 1000);
        // } elseif ($harga < 100000) {
        //     $hargaNormalisasi = (int)floor($harga / 10000);
        // } elseif ($harga < 10000000) {
        //     $hargaNormalisasi = (int)floor($harga / 100000);
        // }
        // return $hargaNormalisasi;
        $hargaNormalisasi = number_format(abs($hargaProdukAsli - $harga) / $hargaProdukAsli , 3);
        return $hargaNormalisasi;
    }

    public function normalisasiBerat(int $berat, int $beratProdukAsli, int $idSatuan)
    {
        if($idSatuan === 5 || $idSatuan === 7){
            $berat *= 1000;
            $beratProdukAsli *= 1000;
        }
        $beratNormalisasi = abs($beratProdukAsli - $berat) / $beratProdukAsli;
        return $beratNormalisasi;
    }

    public function normalisasiHashtag(string $hashtagProdukAsli, string $hashtagProdukSubstitusi)
    {
        $hashtagProdukAsli = explode(',', $hashtagProdukAsli);
        $hashtagProdukSubstitusi = explode(',', $hashtagProdukSubstitusi);

        // Menghapus spasi dalam array hastag
        $hashtagProdukAsli = array_map('trim', $hashtagProdukAsli);
        $hashtagProdukSubstitusi = array_map('trim', $hashtagProdukSubstitusi);
    
        $jumlahHashtagSama = 0;
        foreach ($hashtagProdukAsli as $hashtag) {
            if (in_array($hashtag, $hashtagProdukSubstitusi)) {
                $jumlahHashtagSama++;
            }
        }
        return $jumlahHashtagSama;
    }

    public function normalisasiProduk(array $produkAsli, array $produkSubstitusi)
    {
        $nilai_harga = 0;
        $nilai_berat = 0;
        $nilai_hastag = 0;
        $produk = [];

        $nilai_harga = $this->normalisasiHarga($produkSubstitusi['harga_jual'], $produkAsli[0]['harga_jual']);
        $nilai_berat = $this->normalisasiBerat($produkSubstitusi['berat'], $produkAsli[0]['berat'], $produkAsli[0]['satuans_id']);
        $nilai_hastag = $this->normalisasiHashtag($produkAsli[0]['hashtag'], $produkSubstitusi['hashtag']);
        $produk = [
            'id_produk' => $produkSubstitusi['id'],
            'nilai_harga' => $nilai_harga,
            'nilai_berat' => $nilai_berat,
            'nilai_hashtag' => $nilai_hastag
        ];
        return $produk;
    }

    public function normalizedDecisionMatrix(array $daftarProduk)
    {
        $kuadratElement = [];
        foreach ($daftarProduk as $produk) {
            $produk = [
                'id_produk' => $produk["id_produk"],
                'nilai_harga' => number_format($produk["nilai_harga"] **= 2, 3),
                'nilai_berat' => number_format($produk["nilai_berat"] **= 2, 3),
                'nilai_hashtag' => number_format($produk["nilai_hashtag"] **= 2, 3)
            ];
            $kuadratElement[] = $produk;
        }

        $jumlahTiapKriteria = [
            "harga" => 0,
            "berat" => 0,
            "hashtag" => 0
        ];
        
        foreach ($kuadratElement as $nilai) {
            $jumlahTiapKriteria["harga"] += $nilai["nilai_harga"];
            $jumlahTiapKriteria["berat"] += $nilai["nilai_berat"];
            $jumlahTiapKriteria["hashtag"] += $nilai["nilai_hashtag"];
        }

        $akarTiapKriteria = [
            "harga" => number_format(sqrt($jumlahTiapKriteria["harga"]), 3),
            "berat" => number_format(sqrt($jumlahTiapKriteria["berat"]), 3),
            "hashtag" => number_format(sqrt($jumlahTiapKriteria["hashtag"]), 3),
        ];

        $normalizedDecisionMatrix = [];
        foreach ($daftarProduk as $produk) {
            if($akarTiapKriteria["hashtag"] != 0){
                $produk = [
                    'id_produk' => $produk["id_produk"],
                    'nilai_harga' => number_format($produk["nilai_harga"] /= $akarTiapKriteria["harga"], 3),
                    'nilai_berat' => number_format($produk["nilai_berat"] /= $akarTiapKriteria["berat"], 3),
                    'nilai_hashtag' => number_format($produk["nilai_hashtag"] /= $akarTiapKriteria["hashtag"], 3)
                ];
            }
            else{
                $produk = [
                    'id_produk' => $produk["id_produk"],
                    'nilai_harga' => number_format($produk["nilai_harga"] /= $akarTiapKriteria["harga"], 3),
                    'nilai_berat' => number_format($produk["nilai_berat"] /= $akarTiapKriteria["berat"], 3),
                    'nilai_hashtag' => 0
                ];
            }
            $normalizedDecisionMatrix[] = $produk;
        }
        return $normalizedDecisionMatrix;
    }

    public function weightedNormalizedDecisionMatrix(array $normalizedDecisionMatrix, array $bobot)
    {
        $weightedNormalizedDecisionMatrix = [];
        foreach ($normalizedDecisionMatrix as $ndm) {
            $produk = [
                'id_produk' => $ndm["id_produk"],
                'nilai_harga' => number_format($ndm["nilai_harga"] *= $bobot["Harga"], 3),
                'nilai_berat' => number_format($ndm["nilai_berat"] *= $bobot["Berat"], 3),
                'nilai_hashtag' => number_format($ndm["nilai_hashtag"] *= $bobot["Hashtag"], 3)
            ];
            $weightedNormalizedDecisionMatrix[] = $produk;
        }
        return $weightedNormalizedDecisionMatrix;
    }

    public function positifIdealSolution(array $weightedNormalizedDecisionMatrix)
    {
        $positifIdealSolution = [];
        if (!empty($weightedNormalizedDecisionMatrix)) {
            $positifIdealSolution = [
                "harga" => min(array_column($weightedNormalizedDecisionMatrix, 'nilai_harga')),
                "berat" => min(array_column($weightedNormalizedDecisionMatrix, 'nilai_berat')),
                "hashtag" => max(array_column($weightedNormalizedDecisionMatrix, 'nilai_hashtag'))
            ];
        }
        return $positifIdealSolution;
    }
    public function negatifIdealSolution(array $weightedNormalizedDecisionMatrix)
    {
        $negatifIdealSolution = [];
        if (!empty($weightedNormalizedDecisionMatrix)) {
            $negatifIdealSolution = [
                "harga" => max(array_column($weightedNormalizedDecisionMatrix, 'nilai_harga')),
                "berat" => max(array_column($weightedNormalizedDecisionMatrix, 'nilai_berat')),
                "hashtag" => min(array_column($weightedNormalizedDecisionMatrix, 'nilai_hashtag'))
            ];
        }
        return $negatifIdealSolution;
    }
    public function separasiPositifIdealSolution(array $weightedNormalizedDecisionMatrix, array $positifIdealSolution)
    {
        $separasiPositifIdealSolution = [];
        foreach ($weightedNormalizedDecisionMatrix as $wndm) {
            $produk = [
                'id_produk' => $wndm["id_produk"],
                'nilai_harga' => number_format(($wndm["nilai_harga"] -= $positifIdealSolution["harga"]) ** 2, 3),
                'nilai_berat' => number_format(($wndm["nilai_berat"] -= $positifIdealSolution["berat"]) ** 2, 3),
                'nilai_hashtag' => number_format(($wndm["nilai_hashtag"] -= $positifIdealSolution["hashtag"]) ** 2, 3)
            ];
            $produk['jumlah'] = number_format($produk['nilai_harga'] + $produk['nilai_berat'] + $produk['nilai_hashtag'], 3);
            $produk['separasi'] = number_format(sqrt($produk['jumlah']), 3);

            $separasiPositifIdealSolution[] = $produk;
        }

        return $separasiPositifIdealSolution;
    }
    public function separasiNegatifIdealSolution(array $weightedNormalizedDecisionMatrix, array $negatifIdealSolution)
    {
        $separasiNegatifIdealSolution = [];
        foreach ($weightedNormalizedDecisionMatrix as $wndm) {
            $produk = [
                'id_produk' => $wndm["id_produk"],
                'nilai_harga' => number_format(($wndm["nilai_harga"] -= $negatifIdealSolution["harga"]) ** 2, 3),
                'nilai_berat' => number_format(($wndm["nilai_berat"] -= $negatifIdealSolution["berat"]) ** 2, 3),
                'nilai_hashtag' => number_format(($wndm["nilai_hashtag"] -= $negatifIdealSolution["hashtag"]) ** 2, 3)
            ];
            $produk['jumlah'] = number_format($produk['nilai_harga'] + $produk['nilai_berat'] + $produk['nilai_hashtag'], 3);
            $produk['separasi'] = number_format(sqrt($produk['jumlah']), 3);

            $separasiNegatifIdealSolution[] = $produk;
        }
        return $separasiNegatifIdealSolution;
    }

    public function relativeCloseness(array $separasiNegatifIdealSolution, array $separasiPositifIdealSolution)
    {
        $relativeCloseness = [];
        foreach ($separasiNegatifIdealSolution as $snis) {
            foreach ($separasiPositifIdealSolution as $spis) {
                if ($snis["id_produk"] === $spis["id_produk"]) {
                    $relative = ($snis["separasi"] + $spis["separasi"] != 0) ? $spis["separasi"] / ($snis["separasi"] + $spis["separasi"]) : 0;
                    $relative = [
                        'id_produk' => $snis["id_produk"],
                        'relative' => $relative
                    ];
                    $relativeCloseness[] = $relative;
                }
            }
        }
        return $relativeCloseness;
    }
    public function finalProdukSubstitusi(array $relativeCloseness, $tanggalAwal, $tanggalAkhir)
    {
        $finalProdukSubstitusi = [];
        usort($relativeCloseness, function ($produk1, $produk2) {
            return $produk2['relative'] <=> $produk1['relative'];
        });

        foreach ($relativeCloseness as $produk) {
            $product = Product::where('id', $produk['id_produk'])->first();

            $getTotalPenjualan = DetailTransaksiPenjualan::join('transaksi_penjualans as tp', 'tp.id', '=', 'detail_transaksi_penjualans.transaksi_penjualan_id')
                                 ->where('detail_transaksi_penjualans.products_id', $produk['id_produk']);
            if($tanggalAwal != null || $tanggalAkhir != null){
                $getTotalPenjualan->whereBetween('tp.tanggal', [$tanggalAwal, $tanggalAkhir]);
            }
            $product['jumlahTerjual'] =  $getTotalPenjualan->sum('detail_transaksi_penjualans.jumlah');

            $product['exp_date'] = Stok::where('products_id', $produk['id_produk'])
                                    ->orderBy('exp_date', 'asc')
                                    ->value('exp_date');

            $finalProdukSubstitusi[] = $product;
        }
        return $finalProdukSubstitusi;
    }
}
