
<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Change Password</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>  
  @extends('layout.template')
  @section('content')
<body>

  <div class="card">
    <div class="card-body">
      <h4 class="mb-2" style="font-weight: bold;">Change Password</h4>
      <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('newPassword') }}">
        @csrf
       
        <div class="mb-3 form-password-toggle">
          <div class="d-flex justify-content-between">
            <label class="form-label" for="password">Old Password</label>
          </div>
          <div class="input-group input-group-merge">
            <input
              type="password"
              id="oldPassword"
              class="form-control @error('oldPassword') is-invalid @enderror"
              name="oldPassword"
              placeholder="Old Password"
              aria-describedby="password"
              required autocomplete="current-password"
              value="{{ old('oldPassword') }}"
            />
            <span class="input-group-text cursor-pointer" id="iconOldPassword"><i class="bx bx-hide" id="iconOldPassword"></i></span>
          </div>
            @if (session('error'))
              <span role="alert" style="color: red;">
                <strong>{{ session('error') }}</strong>
              </span>
            @endif
        </div>

        <div class="mb-3 form-password-toggle">
          <div class="d-flex justify-content-between">
            <label class="form-label" for="password">New Password</label>
          </div>
          <div class="input-group input-group-merge">
            <input
              type="password"
              id="newPassword"
              class="form-control @error('newPassword') is-invalid @enderror"
              name="newPassword"
              placeholder="New Password"
              aria-describedby="password"
              required autocomplete="current-password"
              value="{{ old('newPassword') }}"
            />
            <span class="input-group-text cursor-pointer" id="iconNewPassword"><i class="bx bx-hide" id="iconNewPassword"></i></span>
            @error('newPassword')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>

        <div class="mb-3 form-password-toggle">
          <div class="d-flex justify-content-between">
            <label class="form-label" for="password">Konfirmasi New Password</label>
          </div>
          <div class="input-group input-group-merge">
            <input
              type="password"
              id="KonfNewPassword"
              class="form-control @error('KonfNewPassword') is-invalid @enderror"
              name="KonfNewPassword"
              placeholder="Confirm New Password"
              aria-describedby="password"
              required autocomplete="current-password"
              value="{{ old('KonfNewPassword') }}"
            />
            <span class="input-group-text cursor-pointer"  id="iconKonfNewPassword"><i class="bx bx-hide" id="iconKonfNewPassword"></i></span>
            @error('KonfNewPassword')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
        <div class="mb-3">
          <button class="btn d-grid w-100 btn-danger" type="submit" onclick="goBack()" style="margin-bottom: 10px;" >Change</button>
        </div>
      </form>
    </div>
  </div>
  @endsection

  <!-- / Content -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  @section('script')

  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

  <script src="../assets/vendor/js/menu.js"></script>
  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>

  <!-- Page JS -->

  <!-- Place this tag in your head or just before your close body tag. -->
  {{-- <script async defer src="https://buttons.github.io/buttons.js"></script> --}}

<script>

  $("#iconKonfNewPassword").click(function() {
    var KonfNewPassword = $("#KonfNewPassword")[0];
    if (KonfNewPassword.type === 'password') {
      KonfNewPassword.type = 'text';
    } else {
      KonfNewPassword.type = 'password';
    }
  });

  $("#iconNewPassword").click(function() {
    var newPassword = $("#newPassword")[0];
    if (newPassword.type === 'password') {
      newPassword.type = 'text';
    } else {
      newPassword.type = 'password';
    }
  });

  $("#iconOldPassword").click(function() {
    var oldPassword = $("#oldPassword")[0];
    if (oldPassword.type === 'password') {
      oldPassword.type = 'text';
    } else {
      oldPassword.type = 'password';
    }
  });

</script>
@endsection
</body>
</html>

