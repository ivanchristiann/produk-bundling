<style>
    label{
        margin-top: 15px;
        margin-bottom: 10px;
        color: black;
    }
    #containerImg {
        height: 200px;
        width: 200px;
        overflow: hidden;
    }

    #containerImg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #closePopUp{
        float: right;
        color: black;
        cursor: pointer;
    }

</style>
@extends('layout.template')

@section('content')
<div class="portlet-title">
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        Edit Karyawan
    </div>
</div>

@if ($errors->any())
    <div id="popUpError">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{($error)}}<span id="closePopUp" data-dismiss="alert">&times;</span></div>
        @endforeach
    </div>
@endif


<form method="POST" action="{{route('employee.update', $employee->id)}}" enctype="multipart/form-data">
    @csrf
    @method("PUT")
    <div class="mb-3">
        <label>username</label>
        <input type="text" name="username" class="form-control" id="username" value='{{old('username', $employee->username)}}' readonly>
        
        <label>Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" id="nama" value='{{old('nama', $employee->nama)}}'>

        <label>Jenis Kelamin</label>
        <div class="form-check">
            <input type="radio" name="jenisKelamin" id="jenisKelamin" value="Pria" @if(old('jenisKelamin', $employee->jenis_kelamin) === 'Pria') checked @endif>
            <label class="form-check-label" for="jenisKelaminPria">Pria</label>
        </div>
        <div class="form-check">
            <input type="radio" name="jenisKelamin" id="jenisKelamin" value="Wanita" @if(old('jenisKelamin', $employee->jenis_kelamin) === 'Wanita') checked @endif>
            <label class="form-check-label" for="jenisKelaminWanita">Wanita</label>
        </div>
        
        
        <label>Tempat, Tanggal Lahir</label>
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="tempatLahir" class="form-control" id="tempatLahir" value='{{old('tempatLahir', $employee->tempat_lahir)}}'>
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggalLahir" class="form-control" id="tanggalLahir" value='{{old('tanggalLahir', $employee->tanggal_lahir)}}'>
            </div>
        </div> 

        <label>Email</label>
        <input type="email" name="email" class="form-control" id="email" value='{{old('email',$employee->email)}}'>

        <label>Role</label>
        <div class="form-check">
            <input type="radio" name="role" id="role" value="Admin" @if(old('role', $employee->role) === 'Admin') checked @endif>
            <label class="form-check-label" for="roleAdmin">Admin</label>
        </div>
        <div class="form-check">
            <input type="radio" name="role" id="role" value="Kasir" @if(old('role', $employee->role) === 'Kasir') checked @endif>
            <label class="form-check-label" for="roleKasir">Kasir</label>
        </div>

        <label>Handphone</label>
        <input type="tel" name="handphone" class="form-control" id="handphone" value='{{old('handphone',$employee->phone)}}'>

        <label>alamat</label>
        <input type="text" name="alamat" class="form-control" id="alamat" value='{{old('alamat',$employee->alamat)}}'>

        <label>Hire Date</label>
        <input type="date" name="hiredate" class="form-control" id="hiredate" value='{{old('hiredate',$employee->hiredate)}}' readonly>

        <label>Foto</label>
        @if ($employee->foto != null && $employee->foto != "EmployeeNoImage.jpg")
            <div id="containerImg">
                <img id="tampilFoto" class="card-img-top" src="{{ asset('../assets/images/employees/'. $employee->foto) }}" alt="{{ $employee->nama}}"/>
            </div>
            <input type="button" id="btnGantiGambar" value="Ganti Foto" style="width: 200px; margin-bottom: 15px;" class="btn">
        @endif
        <div>
            <input class="form-control" type="file" id="foto" name="foto"  accept="image/*" style="display: {{ ($employee->foto != null && $employee->foto != "EmployeeNoImage.jpg") ? 'none' : 'block' }};" onchange="gantiGambar()">
        </div>
    </div>
    <button type="submit" class="btn btn-primary" style="margin-top: 20px; width: 100%;">Submit</button>
</form>
@endsection


@section('script')
<script type="text/javascript">
$("#btnGantiGambar").click(function () {
    var styleDisplay = $("#foto").css("display");
    if (styleDisplay === 'block') {
        $("#foto").css("display", 'none');
    } else {
        $("#foto").css("display", 'block');
    }
});

function gantiGambar() {
  var inputGambar = document.getElementById("foto");
  var tampilGambar = document.getElementById("tampilFoto");
  
  if (inputGambar.files && inputGambar.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
        tampilGambar.src = e.target.result;
    };
    reader.readAsDataURL(inputGambar.files[0]);
  }
}

$(document).ready(function() {
    // Menutup Pop up error selama 7detik
    $("#popUpError").delay(7000).slideUp(function() {
        $(this).alert('close');
    });
});



</script>
@endsection