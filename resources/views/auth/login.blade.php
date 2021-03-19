<!DOCTYPE html>
<html lang="en">

<head>

  @include('template/admin/_head')

  <title>Sistem Rekruitmen | Promosi | Penjajakan Karyawan</title>

  <style type="text/css">
    body {height: calc(100vh); background-repeat: no-repeat; background-size: cover; background-position: center;}
    .wrapper {background: rgba(0,0,0,.3);}
    .card {width: 500px; background-color: rgba(0,0,0,.6);}
    .form-control, .form-control:focus {background-color: transparent; color: #fff;}
    .input-group .form-control {border-right-width: 0;}
    .input-group-append .btn {color: #fff; border: 1px solid #d1d3e2; border-left-width: 0; border-radius: 10rem;}
    .custom-checkbox .custom-control-label::before {background-color: transparent;}
  </style>

</head>

<body background="{{ asset('assets/images/background/hrd.jpg') }}">

  <div class="wrapper h-100">
    <div class="d-flex justify-content-center h-100">
      <div class="card my-auto">
        <div class="card-body">
          <div class="col px-sm-5 px-4 mb-5">
            <a href="https://psikologanda.com">
              <img class="img-fluid" src="{{ asset('assets/images/logo-2.png') }}">
            </a>
          </div>
          @if(isset($message))
          <div class="alert alert-danger">
            {{ $message }}
          </div>
          @endif
          <form class="user" method="post" action="/login">
            {{ csrf_field() }}
            <div class="form-group">
              <input type="username" class="form-control form-control-user {{ $errors->has('username') ? 'border-danger' : '' }}" name="username" placeholder="Masukkan Username..." value="{{ old('username') }}">
              @if($errors->has('username'))
                <small class="text-danger">{{ $errors->first('username') }}</small>
              @endif
            </div>
            <div class="form-group">
              <div class="input-group">
                <input type="password" class="form-control form-control-user {{ $errors->has('password') ? 'border-danger' : '' }}" name="password" placeholder="Masukkan Password...">
                <div class="input-group-append">
                  <button class="btn btn-toggle-password" type="button"><i class="fa fa-eye"></i></button>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-outline-primary btn-user btn-block">
              Login
            </button>
          </form>
          <hr>
        </div>
      </div>
    </div>
  </div>

  @include('template/admin/_js')

</body>

</html>
