<style>
    #closePopUp{
        float: right;
        color: black;
        cursor: pointer;
    }   
</style>

@extends('layout.template')

@section('content')
@if (session('status'))
<div class="alert alert-success">{{session('status')}}</div>
@endif
<div style="margin-top: 20px; margin-bottom: 20px; font-size: 20px;">
    <strong>Proses Bundling Dan Substitusi</strong>
</div>

@if ($errors->any())
    <div id="popUpError">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{($error)}}<span id="closePopUp" data-dismiss="alert">&times;</span></div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{route('apriori.store')}}">
@csrf
    <div class="row" style="padding-bottom: 20px; ">
        <div class="col-md-4">
            <strong>From</strong>
            <input type="date" name="tanggalAwalTransaksi" class="form-control" id="tanggalAwalTransaksi" {{ isset($inputData['startDate']) ? 'value='.$inputData['startDate'] : '' }} value="{{ old('tanggalAwalTransaksi') }}">

            <strong>To</strong>
            <input type="date" name="tanggalAkhirTransaksi" class="form-control" id="tanggalAkhirTransaksi" {{ isset($inputData['endDate']) ? 'value='.$inputData['endDate'] : '' }} value="{{ old('tanggalAkhirTransaksi') }}">
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-4">
            <strong>Nilai Min Support</strong><label>&nbsp;&nbsp;(1 - 100)</label>
            <span style="float: right; cursor: pointer;"><i class='bx bxs-info-circle' title="Minimum Probabilitas Customer Membeli Sebuah Produk Secara Bersamaan"></i></span>
            <input type="number" name="nilaiMinSupport" class="form-control" id="nilaiMinSupport" max="100" min="1" {{ isset($inputData['minSupport']) ? 'value='.$inputData['minSupport'] : '' }} value="{{ old('nilaiMinSupport') }}">

            <strong>Nilai Min Confidence</strong><label>&nbsp;&nbsp;(1 - 100)</label>
            <span style="float: right; cursor: pointer;"><i class='bx bxs-info-circle' title="Minimum Probabilitas Produk A dan Produk B dibeli Bersamaan oleh customer"></i></span>
            <input type="number" name="nilaiMinConfidence" class="form-control" id="nilaiMinConfidence" max="100" min="1" {{ isset($inputData['minConfidence']) ? 'value='.$inputData['minConfidence'] : '' }} value="{{ old('nilaiMinConfidence') }}">
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 20px; width: 100%;">PROSES</button>
        @if (isset($inputData))
            <button type="submit" class="btn" style="margin-top: 10px; width: 100%;" id="detailPerhitungan">TAMPILKAN DETAIL PERHITUNGAN</button>
        @endif
    </div>
</form>
    
@if (isset($inputData))
    <div id="detailPerhitunganApriori" style="display: none;">
        <label for="tanggal"><strong> Tanggal Transaksi </strong>: {{ date('j F Y', strtotime($inputData['startDate']))}} - {{ date('j F Y', strtotime($inputData['endDate']))}}</label>
        <br>
        
        <strong>Itemset 1</strong>
        <table id="tabelItemSet1" class="table table-striped" style="width:100%">
            <tr>
                <th>#</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Support</th>
            </tr>
            @foreach($itemset1 as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['produk']}}</td>
                    <td>{{ $item['jumlah']}}</td>
                    <td>{{ $item['support'] }} <span style="color: {{ $item['statusLolos'] === 'Lolos' ? 'green' : 'red' }};">({{ $item['statusLolos']}})</span></td>
                </tr>
            @endforeach
        </table>
        <strong>Final Itemset 1</strong>
        <table id="tabelItemSet1" class="table table-striped" style="width:100%">
            <tr>
                <th>#</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Support</th>
            </tr>
            @foreach($finalItemset1 as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['produk']}}</td>
                    <td>{{ $item['jumlah']}}</td>
                    <td>{{ $item['support']}}</td>
                </tr>
            @endforeach
        </table>
        <strong>Itemset 2</strong>
        <table id="tabelItemSet2" class="table table-striped" style="width:100%">
            <tr>
                <th>#</th>
                <th>Produk 1</th>
                <th>Produk 2</th>
                <th>Jumlah</th>
                <th>Support</th>
            </tr>
            @foreach($itemset2 as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['produk1']}}</td>
                    <td>{{ $item['produk2']}}</td>
                    <td>{{ $item['jumlah']}}</td>
                    <td>{{ $item['support'] }} <span style="color: {{ $item['statusLolos'] === 'Lolos' ? 'green' : 'red' }};">({{ $item['statusLolos']}})</span></td>
                </tr>
            @endforeach
        </table>
        <strong>Final Itemset 2</strong>
        <table id="tabelItemSet2" class="table table-striped" style="width:100%">
            <tr>
                <th>#</th>
                <th>Produk 1</th>
                <th>Produk 2</th>
                <th>Jumlah</th>
                <th>Support</th>
            </tr>
            @foreach($finalItemset2 as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['produk1']}}</td>
                    <td>{{ $item['produk2']}}</td>
                    <td>{{ $item['jumlah']}}</td>
                    <td>{{ $item['support']}}</td>
                </tr>
            @endforeach
        </table>


        <strong>Confidence Itemset 2</strong>
        <table id="tabelItemSet2" class="table table-striped" style="width:100%">
            <tr>
                <th>#</th>
                <th>Produk A >> Produk B </th>
                <th>Support A U B</th>
                <th>Support A</th>
                <th>Confidence</th>
            </tr>
            @foreach($confidenceItemSet2 as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['produk1']}} >> {{ $item['produk2']}}</td>
                    <td>{{ $item['support']}}</td>
                    <td>{{ $item['supportx']}}</td>
                    <td>{{ $item['confidence']}}<span style="color: {{ $item['statusLolos'] === 'Lolos' ? 'green' : 'red' }};">({{ $item['statusLolos']}})</span></td>
                </tr>
            @endforeach
        </table>

        <strong>Uji Lift</strong>
        <table id="tabelItemSet2" class="table table-striped" style="width:100%">
            <tr>
                <th>#</th>
                <th>Produk A >> Produk B </th>
                <th>Confidence</th>
                <th>Confidence Benchmark</th>
                <th>nilai Uji Lif</th>
                <th>Korelasi</th>
            </tr>
            @foreach($ujiLift as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['produk1']}} >> {{ $item['produk2']}}</td>
                    <td>{{ $item['confidence']}}</td>
                    <td>{{ $item['confidenceBenchmark']}}</td>
                    <td>{{ $item['lift']}}</td>
                    <td>{{ $item['lift'] >= 1 ? 'Berkorelasi' : 'Tidak Berkorelasi' }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    <strong>Pasangan Produk yang Sering Dibeli Oleh Customer</strong>
    <table id="tabelItemSet2" class="table table-striped" style="width:100%">
        <tr>
            <th>#</th>
            <th>Produk A</th>
            <th>Produk B</th>
            <th>Produk Bundling</th>
            <th>Produk Substitusi</th>
        </tr>
        @foreach($finalUjiLift as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item['produk1']}}</td>
                <td>{{ $item['produk2']}}</td>
                @php
                    $statusProduk1 = App\Http\Controllers\ProductController::checkStatusProduk($item['idProduk1']);
                    $statusProduk2 = App\Http\Controllers\ProductController::checkStatusProduk($item['idProduk2']);
                @endphp
                @if ($statusProduk1['status'] === "1" && $statusProduk2['status'] === "1")
                    <td><a href="{{ route('productbundling.produkBundlingApriori', ['idProduk1' => $item['idProduk1'], 'idProduk2' => $item['idProduk2']])}}" class="btn btn-primary">Produk Bundling</a></td>
                    <td><a href="{{ route('productbundling.produkBundlingSubstitusi', ['idProduk1' => $item['idProduk1'], 'idProduk2' => $item['idProduk2'], 'startDate' => $inputData['startDate'], 'endDate' => $inputData['endDate'], 'metode' => 'topsis']) }}" class="btn btn-success">Produk Substitusi</a></td>
                @else
                    <td colspan="2" style="text-align: center; color: red;">Produk Nonaktif</td>
                @endif        
            </tr>
        @endforeach
    </table>
@endif

@endsection

@section('script')
<script>
    $("#nilaiMinConfidence").on("input", function() {
        var maxMinConfidence = $("#nilaiMinConfidence").attr("max");
        var minMinConfidence = $("#nilaiMinConfidence").attr("min");
        var inputMinConfidence = parseFloat($(this).val());

        if (inputMinConfidence > maxMinConfidence) {
            $(this).val(maxMinConfidence);
        }
        if (inputMinConfidence < minMinConfidence) {
            $(this).val(minMinConfidence);
        }
    });

    $("#nilaiMinSupport").on("input", function() {
        var maxMinSupport = $("#nilaiMinSupport").attr("max");
        var minMinSupport = $("#nilaiMinSupport").attr("min");
        var inputMinSupport = parseFloat($(this).val());

        if (inputMinSupport > maxMinSupport) {
            $(this).val(maxMinSupport);
        }
        if (inputMinSupport < minMinSupport) {
            $(this).val(minMinSupport);
        }
    });

    $("#detailPerhitungan").click(function(event) {
        event.preventDefault();
        if ($("#detailPerhitunganApriori").css('display') === 'none') {
            $("#detailPerhitunganApriori").css('display', 'block');
            $("#detailPerhitungan").text('SEMBUNYIKAN DETAIL PERHITUNGAN');
        } else {
            $("#detailPerhitunganApriori").css('display', 'none');
            $("#detailPerhitungan").text('TAMPILKAN DETAIL PERHITUNGAN');
        }
    });
 
    <?php if (!isset($inputData)): ?>
        var today = new Date();
        var formattedDate = today.toISOString().substr(0, 10);

        $("#tanggalAwalTransaksi").val(formattedDate);
        $("#tanggalAkhirTransaksi").val(formattedDate);
    <?php endif; ?>

    $(document).ready(function() {
        // Menutup Pop up error selama 7detik
        $("#popUpError").delay(7000).slideUp(function() {
            $(this).alert('close');
        });
    });
</script>
@endsection


