<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />
  <title>Registrasi</title>
  <style type="text/css">
    body {height: calc(100vh); background-repeat: no-repeat; background-size: cover; background-position: center;}
    .wrapper {background: rgba(0,0,0,.6);}
    .card {width: 700px; border-radius: 0;}
    .card-header span {display: inline-block; height: 12px; width: 12px; margin: 0px 5px; border-radius: 50%; background: rgba(0,0,0,.2);}
    .card-header span.active {background: #007bff!important;}
    .input-group-text {width: 40px;}
    .preloader {display: none; position: fixed; height: 100%; width: 100%; top: 0; right: 0; left: 0; bottom: 0; z-index: 9999; background: rgba(0,0,0,.55);}
    .preloader-animation {background-position: center; background-repeat: no-repeat; height: 100%;}
</style>
</head>
<body class="small" background="{{ asset('assets/images/background/applicant.jpg') }}">
  <div class="preloader">
      <div class="preloader-animation" style="background-image: url({{ asset('assets/loader/preloader.gif') }});"></div>
  </div>
  <div class="wrapper h-100">
    <div class="d-flex justify-content-center h-100">
      <div class="card my-auto">
        <div class="card-body">
          <div class="text-center">
            <h1 class="h4 text-gray-900 mb-5 text-uppercase">Form Registrasi</h1>
          </div>
          <form id="form" method="post" action="/register">
            {{ csrf_field() }}
            <div class="form-row">
              <div class="form-group col-sm-6">
                <label>Nama Lengkap:</label>
                <input name="nama_lengkap" type="text" class="form-control form-control-sm {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}" placeholder="Masukkan Nama Lengkap" value="{{ old('nama_lengkap') }}">
                @if($errors->has('nama_lengkap'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('nama_lengkap')) }}
                </div>
                @endif
              </div>
              <div class="form-group col-sm-6">
                <label>Email:</label>
                <input name="email" type="email" class="form-control form-control-sm {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Masukkan Email" value="{{ old('email') }}">
                @if($errors->has('email'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('email')) }}
                </div>
                @endif
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-sm-6">
                <label>Jenis Kelamin:</label>
                <select name="jenis_kelamin" class="form-control form-control-sm {{ $errors->has('jenis_kelamin') ? 'is-invalid' : '' }}">
                  <option value="" disabled selected>--Pilih--</option>
                  <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                  <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @if($errors->has('jenis_kelamin'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('jenis_kelamin')) }}
                </div>
                @endif
              </div>
              <div class="form-group col-sm-6">
                <label>Tanggal Lahir:</label>
                <div class="input-group">
                  <input name="tanggal_lahir" type="text" class="form-control form-control-sm {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }}" placeholder="Masukkan Tanggal Lahir" value="{{ old('tanggal_lahir') }}">
                  <div class="input-group-append">
                    <button class="btn btn-sm {{ $errors->has('tanggal_lahir') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-show-datepicker" type="button"><i class="fa fa-calendar"></i></button>
                  </div>
                </div>
                @if($errors->has('tanggal_lahir'))
                <small class="text-danger">
                  {{ ucfirst($errors->first('tanggal_lahir')) }}
                </small>
                @endif
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-sm-6">
                <label>Username:</label>
                <input name="username" type="text" class="form-control form-control-sm {{ $errors->has('username') ? 'is-invalid' : '' }}" placeholder="Masukkan Username" value="{{ old('username') }}">
                @if($errors->has('username'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('username')) }}
                </div>
                @endif
              </div>
              <div class="form-group col-sm-6">
                <label>Password:</label>
                <div class="input-group">
                  <input name="password" type="password" class="form-control form-control-sm {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Masukkan Password" value="">
                  <div class="input-group-append">
                    <button class="btn btn-sm {{ $errors->has('password') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-toggle-password show" type="button"><i class="fa fa-eye"></i></button>
                  </div>
                </div>
                @if($errors->has('password'))
                <small class="text-danger">
                  {{ ucfirst($errors->first('password')) }}
                </small>
                @endif
              </div>
            </div>
            <div class="form-group mt-3">
              <div class="row">
                <div class="col-auto ml-auto">
                  <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
  <script type="text/javascript">
    $(function(){
        // Show datepicker
        $('input[name=tanggal_lahir]').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        });
        
        // Button show datepicker
        $(document).on("click", ".btn-show-datepicker", function(e){
            e.preventDefault();
            $('input[name=tanggal_lahir]').focus();
        });
    
        // Button toggle password
        $(document).on("click", ".btn-toggle-password", function(e){
          e.preventDefault();
          $(this).hasClass("show") ? $("input[name=password]").attr("type","text") : $("input[name=password]").attr("type","password");
          $(this).hasClass("show") ? $(this).find(".fa").removeClass("fa-eye").addClass("fa-eye-slash") : $(this).find(".fa").removeClass("fa-eye-slash").addClass("fa-eye");
          $(this).hasClass("show") ? $(this).removeClass("show").addClass("hide") : $(this).removeClass("hide").addClass("show");
        });
      
        // Show loader on submit
        $(document).on("submit", "#form", function(e){
            e.preventDefault();
            $(".preloader").show();
            $("#form")[0].submit();
        });
    });
  </script>
</body>
</html>