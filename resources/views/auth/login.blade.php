<!DOCTYPE HTML>
<html lang="en">
  <head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Sistem Rekruitmen | Promosi | Penjajakan Karyawan</title>
</head>

<body>
    <div class="main-wrapper">
      <div class="wrapper">
        <div id="content">
          <div id="sidebar-main"></div>
          <div id="navbar-main"></div>
          <div class="main-wrapper mt-5 mt-lg-0">
              <div class="auth-wrapper d-flex no-block justify-content-center align-items-center">
                  <div class="container">
                      <div class="row">
                          <div class="col-lg-6 d-none d-lg-block">
                              <div class="d-flex align-items-center h-100">
                                  <img class="img-fluid" src="https://www.psikologanda.com/assets/images/ilustrasi/undraw_Login_re_4vu2.svg">
                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="wrapper">
                                  <div class="card border-0 shadow-sm rounded-1">
                                      <div class="card-header text-center pt-4 bg-transparent mx-4">
                                          <img width="200" class="mb-3" src="https://www.psikologanda.com/assets/images/logo/1617422635-logo.png">
                                          <h5 class="h2 mb-0">Selamat Datang</h5>
                                          <p class="m-0">Untuk tetap terhubung dengan kami, silakan login dengan informasi pribadi Anda melalui Username dan Password 🔔</p>
                                      </div>
                                      <div class="card-body">
                                          <form class="login-form" action="/login" method="post">
                                              {{ csrf_field() }}
                                              @if(isset($message))
                                              <div class="alert alert-danger">
                                                  {{ $message }}
                                              </div>
                                              @endif
                                              <div class="form-group ">
                                                  <label class="control-label">Username</label>
                                                  <div class="input-group input-group-lg">
                                                      <div class="input-group-prepend">
                                                          <span class="input-group-text" id="basic-addon1"><i class="ti-email"></i></span>
                                                      </div>
                                                      <input class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" name="username" type="text" placeholder="Username" autofocus>
                                                  </div>
                                                  @if($errors->has('username'))
                                                  <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('username')) }}</div>
                                                  @endif
                                              </div>
                                              <div class="form-group">
                                                  <label class="control-label">Password</label>
                                                  <div class="input-group input-group-lg">
                                                      <div class="input-group-prepend">
                                                          <span class="input-group-text" id="basic-addon1"><i class="ti-key"></i></span>
                                                      </div>
                                                      <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'border-danger' : '' }}" placeholder="Password">
                                                      <div class="input-group-append">
                                                          <a href="#" class="input-group-text text-dark {{ $errors->has('password') ? 'border-danger bg-danger' : '' }}" id="btn-toggle-password"><i class="fa fa-eye"></i></a>
                                                      </div>
                                                  </div>
                                                  @if($errors->has('password'))
                                                  <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('password')) }}</div>
                                                  @endif
                                              </div>
                                              <div class="text-right">
                                                <div class="form-group">
                                                  <a href="/recovery-password" class="text-body">Lupa Password?</a>
                                                </div>
                                              </div>
                                              <div class="form-group btn-container">
                                                  <button type="submit" class="btn btn-primary btn-lg rounded px-4 shadow-sm btn-block">Masuk</button>
                                                  <a href="/register{{ Session::get('ref') != null ? '?ref='.Session::get('ref') : '' }}" class="btn btn-light btn-lg rounded px-4 shadow-sm btn-block">Daftar</a>
                                              </div>
                                          </form>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>
    <div id="footer-main"></div>
  @include('template/admin/_js')
  <script src="https://psikologanda.com/assets/partials/template.js"></script>
</body>

</html>
