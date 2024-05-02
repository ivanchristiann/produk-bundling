@php
  $jumlahStokMendekatiExp = DB::table('products as p')
    ->join('stoks as s', 's.products_id', '=', 'p.id')
    ->select(DB::raw('COUNT(*) AS jumlah'))
    ->where('s.jumlah', '!=', 0)
    ->whereRaw('DATEDIFF(s.exp_date, NOW()) < 31')
    ->first();
@endphp

<div class="sidebar-wrapper" style="overflow-x: hidden;">
  <ul class="nav" style="height: auto;">
    <li class="{{ (request()->is('dashboard*') || request()->is('home*')) ? 'menu-item active': 'menu-item'}}">
    
      <a href="{{ url('dashboard') }}" class="menu-link">
        <i class="nc-icon nc-bank"></i>
        <p>Dashboard</p>
      </a>
    </li>
    @if (str_contains(Auth::user()->role, 'SuperAdmin') || str_contains(Auth::user()->role, 'Admin'))
    <li>
      <a data-toggle="collapse" href="#masterData">
        <i class='bx bx-data'></i>
        <p>Master Data<b class="caret"></b> </p>
      </a>
      <div class="{{(request()->is('kategories*') || request()->is('subkategories*') || request()->is('satuan*') || request()->is('employee*') || request()->is('supplier*') || request()->is('customer*')) ? 'collapsed' : 'collapse' }}" id="masterData" style="margin-top: -10px; margin-left: 20px;">
        <ul class="nav">
          <li class="{{ (request()->is('kategories*')) ? 'menu-item active' : 'menu-item' }}">
            <a href="{{ url('kategories') }}" class="menu-link">
              <i class='bx bx-category'></i>
              <p>Kategori</p>
            </a>
          </li>
          <li class="{{ (request()->is('subkategories*')) ? 'menu-item active' : 'menu-item' }}">
            <a href="{{ url('subkategories') }}" class="menu-link">
              <i class='bx bx-category-alt'></i>
              <p>Subkategories</p>
            </a>
          </li>
          <li class="{{ (request()->is('satuan*')) ? 'menu-item active' : 'menu-item' }}">
            <a href="{{ url('satuan') }}" class="menu-link">
              <i class="menu-icon bx bx-user"></i>
              <p>Satuan</p>
            </a>
          </li>
          @if (str_contains(Auth::user()->role, 'SuperAdmin'))
          <li class="{{ (request()->is('employee*')) ? 'menu-item active' : 'menu-item' }}">
            <a href="{{ url('employee') }}" class="menu-link">
              <i class='bx bx-body'></i>
              <p>Karyawan</p>
            </a>
          </li>
          @endif
          <li class="{{ (request()->is('supplier*')) ? 'menu-item active' : 'menu-item' }}">
            <a href="{{ url('supplier') }}" class="menu-link">
              <i class='bx bxs-truck'></i>
              <p>Supplier</p>
            </a>
          </li>
        </ul>
      </div>
    </li>
    @endif
    <li>
      <a data-toggle="collapse" href="#produk">
        <i class='bx bx-package'></i>
        <p>Produk<b class="caret"></b> </p>
      </a>
      <div class="{{(request()->is('product*') ||request()->is('productAktif*') || request()->is('productNonAktif*') || request()->is('productbundling*') ||request()->is('detailproductbundling*') || request()->is('createProdukBundling*') || request()->is('detailproduct*')) ? 'collapsed' : 'collapse' }}" id="produk" style="margin-top: -10px; margin-left: 20px;">
        <ul class="nav">
          <li class="{{ (request()->is('productAktif*') || request()->is('product*') && !request()->is('productbundling*') && !request()->is('productNonAktif*') && !request()->is('detailproduct*')) ? 'menu-item active': 'menu-item'}}">
            <a href="{{ route('productAktif.index') }}" class="menu-link">
              <i class='bx bxs-package'></i>
              <p>Produk Aktif</p>
            </a>
          </li>
          @if (str_contains(Auth::user()->role, 'SuperAdmin') || str_contains(Auth::user()->role, 'Admin'))
            <li class="{{ (request()->is('productNonAktif*')) ? 'menu-item active': 'menu-item'}}">
              <a href="{{ route('productNonAktif.index') }}" class="menu-link">
                <i class='bx bxs-package'></i>
                <p>Produk Nonaktif</p>
              </a>
            </li>
          @endif
          <li class="{{ (request()->is('productbundling*') || request()->is('detailproductbundling*') || request()->is('createProdukBundling*')) ? 'menu-item active': 'menu-item'}}">
            <a href="{{ url('productbundling') }}" class="menu-link">
              <i class='bx bxs-inbox'></i>
              <p>Produk Bundling</p>
            </a>
          </li>
        </ul>
      </div>
    </li>
    <li>
      <a data-toggle="collapse" href="#transaksi">
        <i class='bx bx-transfer'></i>
        <p>Transaksi<b class="caret"></b> </p>
      </a>
      <div class="{{(request()->is('transaksiPembelian*') || request()->is('transaksiPenjualan*')) ? 'collapsed' : 'collapse' }}" id="transaksi" style="margin-top: -10px; margin-left: 20px;">
        <ul class="nav">
          @if (str_contains(Auth::user()->role, 'SuperAdmin') || str_contains(Auth::user()->role, 'Admin'))
            <li class="{{ (request()->is('transaksiPembelian*')) ? 'menu-item active': 'menu-item'}}">
              <a href="{{ url('transaksiPembelian') }}" class="menu-link">
                <i class='bx bxs-down-arrow-alt' style="color: green;"></i>
                <p>Pembelian</p>
              </a>
            </li>
          @endif
          @if (str_contains(Auth::user()->role, 'SuperAdmin') || str_contains(Auth::user()->role, 'Kasir'))
            <li class="{{ (request()->is('transaksiPenjualan*')) ? 'menu-item active': 'menu-item'}}">
              <a href="{{ url('transaksiPenjualan') }}" class="menu-link">
                <i class='bx bxs-up-arrow-alt' style="color: red;"></i>
                <p>Penjualan</p>
              </a>
            </li>
          @endif

        </ul>
      </div>
    </li>
    @if (str_contains(Auth::user()->role, 'SuperAdmin') || str_contains(Auth::user()->role, 'Admin'))
      <li class="{{ (request()->is('stok*')) ? 'menu-item active': 'menu-item'}}">
        <a href="{{ url('stok') }}" class="menu-link">
          <i class='bx bx-notepad'></i>
          <p>Stok
            @if ($jumlahStokMendekatiExp->jumlah > 0)
              <span style="background-color: red; color: white; padding: 4px 8px; border-radius: 4px; margin-left: 15px;">{{ $jumlahStokMendekatiExp->jumlah }}</span>
            @endif
          </p>
        </a>
      </li> 

      <li class="{{ (request()->is('apriori*')) ? 'menu-item active': 'menu-item'}}">
        <a href="{{ url('apriori') }}" class="menu-link">
          <i class='bx bx-rotate-left'></i>
          <p>Proses Bundling</p>
        </a>
      </li>

      <li class="{{ (request()->is('topsis*')) ? 'menu-item active': 'menu-item'}}">
        <a href="{{ url('topsis') }}" class="menu-link">
          <i class='bx bx-key'></i>
          <p>Kriteria Substitusi</p>
        </a>
      </li>
    @endif

    <li class="{{ (request()->is('ubahpassword*')) ? 'menu-item active': 'menu-item'}}">
      <a href="{{ url('ubahpassword') }}" class="menu-link">
        <i class='bx bxs-low-vision'></i></i>
        <div data-i18n="Analytics">Ubah Password</div>
      </a>
    </li>

    <li class="menu-item">
      <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn menu-link btn-logout" style="width: 100%; background: red;">
              <i class="menu-icon tf-icons bx bx-log-out"></i>
              <p style="text-align:left; margin-top: 6px;">Logout</p>
          </button>
      </form>
    </li>
  </ul>
</div>