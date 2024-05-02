<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AprioriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('apriori.index');
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
        $validator = Validator::make($request->all(), [
            'tanggalAkhirTransaksi' => 'after_or_equal:tanggalAwalTransaksi',
            'nilaiMinSupport' => 'required | between:1,100',
            'nilaiMinConfidence' => 'required | between:1,100',
        ], [
            'tanggalAkhirTransaksi.after_or_equal' => 'Tanggal Akhir Transaksi Harus Lebih dari Tanggal Awal Transaksi',
            'nilaiMinSupport.required' => 'Nilai Minimum Support Wajib Diisi',
            'nilaiMinConfidence.required' => 'Nilai Confidence Support Wajib Diisi',
            'nilaiMinSupport.between' => 'Nilai Minimum Support Diatara 1 Hingaa 100',
            'nilaiMinConfidence.between' => 'Nilai Confidence Support Diatara 1 Hingaa 100'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $inputData = [
            'startDate' => $request->get('tanggalAwalTransaksi'),
            'endDate' => $request->get('tanggalAkhirTransaksi'),
            'minSupport' => $request->get('nilaiMinSupport'),
            'minConfidence' => $request->get('nilaiMinConfidence'),
        ];

        $jumlahTransaksi = DB::table('transaksi_penjualans')
            ->whereBetween('tanggal', [$inputData['startDate'], $inputData['endDate']])
            ->count();

        $itemset1 = $this->itemSet1($inputData['startDate'], $inputData['endDate'], $jumlahTransaksi, $inputData['minSupport']);
        $finalItemset1 = $this->finalItemSet1Lolos($itemset1);

        $itemset2 = $this->itemSet2($inputData['startDate'], $inputData['endDate'], $jumlahTransaksi, $inputData['minSupport'], $finalItemset1);
        $finalItemset2 = $this->finalItemSet2Lolos($itemset2);

        $confidenceItemSet2 = $this->confidenceItemSet2($inputData['startDate'], $inputData['endDate'], $jumlahTransaksi, $finalItemset2, $inputData['minConfidence']);
        $finalConfidenceItemSet2 = $this->finalConfidenceItemSet2($confidenceItemSet2);

        $ujiLift = $this->ujiLift($finalConfidenceItemSet2);
        $finalUjiLift = $this->finalUjiLift($ujiLift);

        return view('apriori.index', compact('itemset1', 'finalItemset1', 'itemset2', 'finalItemset2', 'confidenceItemSet2', 'ujiLift', 'finalUjiLift', 'inputData'));
    }

    public function itemSet1($startDate, $endDate, $jumlahTransaksi, $minSupport)
    {
        $itemset1 = [];
        $jumlah = DB::table('detail_transaksi_penjualans as dtp')
            ->join('transaksi_penjualans as tp', 'dtp.transaksi_penjualan_id', '=', 'tp.id')
            ->join('products as p', 'p.id', '=', 'dtp.products_id')
            ->whereBetween('tp.tanggal', [$startDate, $endDate])
            ->where('p.is_bundling', '=', '0')
            ->select('p.id', 'p.nama', DB::raw('count(dtp.transaksi_penjualan_id) as jumlah'))
            ->groupBy('dtp.products_id', 'p.nama', 'p.id')
            ->get();
        foreach ($jumlah as $data) {
            $support = number_format($data->jumlah / $jumlahTransaksi * 100, 2);
            $statusItemSet1 = ($support >= $minSupport) ? 'Lolos' : 'Tidak Lolos';

            $itemset1[] = [
                'idProduk' => $data->id,
                'produk' => $data->nama,
                'jumlah' => $data->jumlah,
                'support' => $support,
                'statusLolos' => $statusItemSet1,
            ];
        }
        return $itemset1;
    }

    public function finalItemSet1Lolos($itemset1)
    {
        $itemsetLolos = [];
        foreach ($itemset1 as $item) {
            if ($item['statusLolos'] === 'Lolos') {
                $itemsetLolos[] = $item;
            }
        }
        return $itemsetLolos;
    }

    public function itemSet2($startDate, $endDate, $jumlahTransaksi, $minSupport, $finalItemset1)
    {
        $itemset2 = [];
        for ($i = 0; $i < count($finalItemset1) - 1; $i++) {
            for ($j = $i + 1; $j < count($finalItemset1); $j++) {
                $jumlah = DB::table('transaksi_penjualans as tp')
                    ->join('detail_transaksi_penjualans as dtp1', 'tp.id', '=', 'dtp1.transaksi_penjualan_id')
                    ->join('detail_transaksi_penjualans as dtp2', 'tp.id', '=', 'dtp2.transaksi_penjualan_id')
                    ->whereBetween('tp.tanggal', [$startDate, $endDate])
                    ->where('dtp1.products_id', $finalItemset1[$i]['idProduk'])
                    ->where('dtp2.products_id', $finalItemset1[$j]['idProduk'])
                    ->count();

                $support = number_format($jumlah / $jumlahTransaksi * 100, 2);
                $statusItemSet2 = ($support >= $minSupport) ? 'Lolos' : 'Tidak Lolos';
                $itemset2[] = [
                    'idProduk1' => $finalItemset1[$i]['idProduk'],
                    'idProduk2' => $finalItemset1[$j]['idProduk'],
                    'produk1' => $finalItemset1[$i]['produk'],
                    'produk2' => $finalItemset1[$j]['produk'],
                    'jumlahProduk1' => $finalItemset1[$i]['jumlah'],
                    'jumlahProduk2' => $finalItemset1[$j]['jumlah'],
                    'jumlah' => $jumlah,
                    'support' => $support,
                    'supportProduk1' => $finalItemset1[$i]['support'],
                    'supportProduk2' => $finalItemset1[$j]['support'],
                    'statusLolos' => $statusItemSet2,
                ];
            }
        }
        return $itemset2;
    }

    public function finalItemSet2Lolos($itemset2)
    {
        $itemsetLolos = [];
        foreach ($itemset2 as $item) {
            if ($item['statusLolos'] === 'Lolos') {
                $itemsetLolos[] = $item;
            }
        }
        return $itemsetLolos;
    }

    public function confidenceItemSet2($startDate, $endDate, $jumlahTransaksi, $itemset2, $minConfiddence)
    {
        $confidenceItemSet2 = [];
        for ($i = 0; $i < count($itemset2); $i++) {
            $confidenceA = number_format($itemset2[$i]['jumlah'] / $itemset2[$i]['jumlahProduk1'] * 100, 2);
            $confidenceB = number_format($itemset2[$i]['jumlah'] / $itemset2[$i]['jumlahProduk2'] * 100, 2);

            $statusConfidence1 = ($confidenceA >= $minConfiddence) ? 'Lolos' : 'Tidak Lolos';
            $statusConfidence2 = ($confidenceB >= $minConfiddence) ? 'Lolos' : 'Tidak Lolos';

            $confidenceItemSet2[] = [
                'idProduk1' => $itemset2[$i]['idProduk1'],
                'idProduk2' => $itemset2[$i]['idProduk2'],
                'produk1' => $itemset2[$i]['produk1'],
                'produk2' => $itemset2[$i]['produk2'],
                'support' => $itemset2[$i]['support'],
                'supportx' => $itemset2[$i]['supportProduk1'],
                'confidence' => $confidenceA,
                'confidenceBenchmark' =>  $itemset2[$i]['supportProduk2'],
                'statusLolos' => $statusConfidence1
            ];
            $confidenceItemSet2[] = [
                'idProduk1' => $itemset2[$i]['idProduk2'],
                'idProduk2' => $itemset2[$i]['idProduk1'],
                'produk1' => $itemset2[$i]['produk2'],
                'produk2' => $itemset2[$i]['produk1'],
                'support' => $itemset2[$i]['support'],
                'supportx' => $itemset2[$i]['supportProduk2'],
                'confidence' => $confidenceB,
                'confidenceBenchmark' => $itemset2[$i]['supportProduk1'],
                'statusLolos' => $statusConfidence2
            ];
        }
        return $confidenceItemSet2;
    }

    public function finalConfidenceItemSet2($confidenceItemSet2)
    {
        $confidenceLolos = [];
        foreach ($confidenceItemSet2 as $item) {
            if ($item['statusLolos'] === 'Lolos') {
                $confidenceLolos[] = $item;
            }
        }
        return $confidenceLolos;
    }

    public function ujiLift($finalConfidenceItemSet2)
    {
        $lift = [];
        for ($i = 0; $i < count($finalConfidenceItemSet2); $i++) {
            $ujiLift = number_format($finalConfidenceItemSet2[$i]['confidence'] / $finalConfidenceItemSet2[$i]['confidenceBenchmark'], 2);
            $statusUjiLift = ($ujiLift >= 1) ? 'Lolos' : 'Tidak Lolos';
            $lift[] = [
                'idProduk1' => $finalConfidenceItemSet2[$i]['idProduk1'],
                'idProduk2' => $finalConfidenceItemSet2[$i]['idProduk2'],
                'produk1' => $finalConfidenceItemSet2[$i]['produk1'],
                'produk2' => $finalConfidenceItemSet2[$i]['produk2'],
                'confidence' => $finalConfidenceItemSet2[$i]['confidence'],
                'confidenceBenchmark' => $finalConfidenceItemSet2[$i]['confidenceBenchmark'],
                'lift' => $ujiLift,
                'statusLolos' => $statusUjiLift
            ];
        }
        return $lift;
    }

    public function finalUjiLift($ujiLift)
    {
        $liftLolos = [];
        $sortIdProduk = [];

        foreach ($ujiLift as $item) {
            if ($item['statusLolos'] === 'Lolos') {
                $sortingIdProduk = [$item['idProduk1'], $item['idProduk2']];
                sort($sortingIdProduk);

                $uniqueKey = implode('_', $sortingIdProduk);

                if (!in_array($uniqueKey, $sortIdProduk)) {
                    $sortIdProduk[] = $uniqueKey;
                    $liftLolos[] = $item;
                }
            }
        }
        return $liftLolos;
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
}
