<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/templates/qbs/bootstrap/style.min.css">
  <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/templates/qbs/bootstrap/homev2.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css" integrity="sha256-8g4waLJVanZaKB04tvyhKu2CZges6pA5SUelZAux/1U=" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/assets/css/login.css">
  <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />
  <title>Registrasi</title>
  <style type="text/css">
    .card {width: 700px; border-radius: 0;}
    .card-header span {display: inline-block; height: 12px; width: 12px; margin: 0px 5px; border-radius: 50%; background: rgba(0,0,0,.2);}
    .card-header span.active {background: #007bff!important;}
    .input-group-text {width: 40px;}
    .preloader {display: none; position: fixed; height: 100%; width: 100%; top: 0; right: 0; left: 0; bottom: 0; z-index: 9999; background: rgba(0,0,0,.55);}
    .preloader-animation {background-position: center; background-repeat: no-repeat; height: 100%;}
</style>
</head>
<body>
  <div id="sidebar-main"></div>
  <div id="navbar-main"></div>
  <div class="preloader">
      <div class="preloader-animation" style="background-image: url({{ asset('assets/loader/preloader.gif') }});"></div>
  </div>
  <div class="wrapper py-lg-5 py-md-3 pt-1">
    <div class="d-flex justify-content-center h-100">
      <div class="card border-0 shadow-sm" style="border-radius: .5em">
        <div class="card-header bg-transparent text-center">
          <span data-step="1" class="{{ $step == 1 ? 'active' : '' }}"></span>
          <span data-step="2" class="{{ $step == 2 ? 'active' : '' }}"></span>
          <span data-step="3" class="{{ $step == 3 ? 'active' : '' }}"></span>
          <span data-step="4" class="{{ $step == 4 ? 'active' : '' }}"></span> 
          <span data-step="5" class="{{ $step == 5 ? 'active' : '' }}"></span> 
        </div>
        <div class="card-body">
          <div class="text-center">
            <h1 class="h4 text-gray-900 mb-5 text-uppercase">Form Identitas</h1>
          </div>
          <!-- <form id="form" method="post" action="/applicant/register/step-1"> -->
          <form id="form" method="post" action="/lowongan/{{ $url_form }}/daftar/step-1">
            {{ csrf_field() }}
            <div class="form-row">
              <div class="form-group col-sm-6">
                <label>Nama Lengkap: <span class="text-danger">*</span></label>
                <input name="nama_lengkap" type="text" class="form-control form-control-sm {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}" placeholder="Masukkan Nama Lengkap" value="{{ !empty($array) ? $array['nama_lengkap'] : old('nama_lengkap') }}">
                @if($errors->has('nama_lengkap'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('nama_lengkap')) }}
                </div>
                @endif
              </div>
              <div class="form-group col-sm-6">
                <label>Email: <span class="text-danger">*</span></label>
                <input name="email" type="email" class="form-control form-control-sm {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Masukkan Email" value="{{ !empty($array) ? $array['email'] : old('email') }}">
                @if($errors->has('email'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('email')) }}
                </div>
                @endif
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-sm-6">
                <label>Tempat Lahir: <span class="text-danger">*</span></label>
                <input name="tempat_lahir" type="text" class="form-control form-control-sm {{ $errors->has('tempat_lahir') ? 'is-invalid' : '' }}" placeholder="Masukkan Tempat Lahir" value="{{ !empty($array) ? $array['tempat_lahir'] : old('tempat_lahir') }}">
                @if($errors->has('tempat_lahir'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('tempat_lahir')) }}
                </div>
                @endif
              </div>
              <div class="form-group col-sm-6">
                <label>Tanggal Lahir: <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input name="tanggal_lahir" type="text" class="form-control form-control-sm {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }}" placeholder="Masukkan Tanggal Lahir" value="{{ !empty($array) ? $array['tanggal_lahir'] : old('tanggal_lahir') }}">
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
                <label>Jenis Kelamin: <span class="text-danger">*</span></label>
                <select name="jenis_kelamin" class="form-control form-control-sm {{ $errors->has('jenis_kelamin') ? 'is-invalid' : '' }}">
                  <option value="" disabled selected>--Pilih--</option>
                  @if(!empty($array))
                  <option value="L" {{ $array['jenis_kelamin'] == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                  <option value="P" {{ $array['jenis_kelamin'] == 'P' ? 'selected' : '' }}>Perempuan</option>
                  @else
                  <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                  <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                  @endif
                </select>
                @if($errors->has('jenis_kelamin'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('jenis_kelamin')) }}
                </div>
                @endif
              </div>
              <div class="form-group col-sm-6">
                <label>Agama: <span class="text-danger">*</span></label>
                <select name="agama" class="form-control form-control-sm {{ $errors->has('agama') ? 'is-invalid' : '' }}">
                  <option value="" disabled selected>--Pilih--</option>
                  @if(!empty($array))
                    @foreach($agama as $data)
                    <option value="{{ $data->id_agama }}" {{ $array['agama'] == $data->id_agama ? 'selected' : '' }}>{{ $data->nama_agama }}</option>
                    @endforeach
                    <!--<option value="99" {{ $array['agama'] == '99' ? 'selected' : '' }}>Lain-Lain</option>-->
                  @else
                    @foreach($agama as $data)
                    <option value="{{ $data->id_agama }}" {{ old('agama') == $data->id_agama ? 'selected' : '' }}>{{ $data->nama_agama }}</option>
                    @endforeach
                    <!--<option value="99" {{ old('agama') == '99' ? 'selected' : '' }}>Lain-Lain</option>-->
                  @endif
                </select>
                @if($errors->has('agama'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('agama')) }}
                </div>
                @endif
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-sm-6">
                <label>Akun Sosial Media: <span class="text-danger">*</span></label>
                <div class="input-group">
                  <select name="sosmed" class="col-4 form-control form-control-sm {{ $errors->has('akun_sosmed') ? 'border-danger' : '' }}">
                    @if(!empty($array))
                    <option value="Facebook" {{ $array['sosmed'] == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                    <option value="Twitter" {{ $array['sosmed'] == 'Twitter' ? 'selected' : '' }}>Twitter</option>
                    <option value="Instagram" {{ $array['sosmed'] == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                    <option value="YouTube" {{ $array['sosmed'] == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                    @else
                    <option value="Facebook" {{ old('sosmed') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                    <option value="Twitter" {{ old('sosmed') == 'Twitter' ? 'selected' : '' }}>Twitter</option>
                    <option value="Instagram" {{ old('sosmed') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                    <option value="YouTube" {{ old('sosmed') == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                    @endif
                  </select>
                  <input name="akun_sosmed" type="text" class="form-control form-control-sm {{ $errors->has('akun_sosmed') ? 'is-invalid' : '' }}" placeholder="Masukkan Akun Sosmed" value="{{ !empty($array) ? $array['akun_sosmed'] : old('akun_sosmed') }}">
                </div>
                @if($errors->has('akun_sosmed'))
                <small class="text-danger">
                  {{ ucfirst($errors->first('akun_sosmed')) }}
                </small>
                @endif
              </div>
              <div class="form-group col-sm-6">
                <label>No. HP: <span class="text-danger">*</span></label>
                <input name="nomor_hp" type="text" class="form-control form-control-sm {{ $errors->has('nomor_hp') ? 'is-invalid' : '' }}" placeholder="Masukkan Nomor HP" value="{{ !empty($array) ? $array['nomor_hp'] : old('nomor_hp') }}">
                @if($errors->has('nomor_hp'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('nomor_hp')) }}
                </div>
                @endif
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-sm-6">
                <label>No. Telepon:</label>
                <input name="nomor_telepon" type="text" class="form-control form-control-sm {{ $errors->has('nomor_telepon') ? 'is-invalid' : '' }}" placeholder="Masukkan Nomor Telepon" value="{{ !empty($array) ? $array['nomor_telepon'] : old('nomor_telepon') }}">
                @if($errors->has('nomor_telepon'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('nomor_telepon')) }}
                </div>
                @endif
              </div>
              <div class="form-group col-sm-6">
                <label>No. KTP:</label>
                <input name="nomor_ktp" type="text" class="form-control form-control-sm {{ $errors->has('nomor_ktp') ? 'is-invalid' : '' }}" placeholder="Masukkan Nomor KTP" value="{{ !empty($array) ? $array['nomor_ktp'] : old('nomor_ktp') }}">
                @if($errors->has('nomor_ktp'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('nomor_ktp')) }}
                </div>
                @endif
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-sm-6">
                <label>Alamat: <span class="text-danger">*</span></label>
                <textarea name="alamat" class="form-control form-control-sm {{ $errors->has('alamat') ? 'is-invalid' : '' }}" placeholder="Masukkan Alamat" rows="1">{{ !empty($array) ? $array['alamat'] : old('alamat') }}</textarea>
                @if($errors->has('alamat'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('alamat')) }}
                </div>
                @endif
              </div>
              <div class="form-group col-sm-6">
                <label>Status Hubungan: <span class="text-danger">*</span></label>
                <select name="status_hubungan" class="form-control form-control-sm {{ $errors->has('status_hubungan') ? 'is-invalid' : '' }}">
                  <option value="" disabled selected>--Pilih--</option>
                  @if(!empty($array))
                  <option value="1" {{ $array['status_hubungan'] == '1' ? 'selected' : '' }}>Lajang</option>
                  <option value="2" {{ $array['status_hubungan'] == '2' ? 'selected' : '' }}>Menikah</option>
                  <option value="3" {{ $array['status_hubungan'] == '3' ? 'selected' : '' }}>Janda / Duda</option>
                  @else
                  <option value="1" {{ old('status_hubungan') == '1' ? 'selected' : '' }}>Lajang</option>
                  <option value="2" {{ old('status_hubungan') == '2' ? 'selected' : '' }}>Menikah</option>
                  <option value="3" {{ old('status_hubungan') == '3' ? 'selected' : '' }}>Janda / Duda</option>
                  @endif
                </select>
                @if($errors->has('status_hubungan'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('status_hubungan')) }}
                </div>
                @endif
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-sm-6">
                <label>Pendidikan Terakhir: <span class="text-danger">*</span></label>
                <textarea name="pendidikan_terakhir" class="form-control form-control-sm {{ $errors->has('pendidikan_terakhir') ? 'is-invalid' : '' }}" placeholder="Masukkan Pendidikan Terakhir" rows="2">{{ !empty($array) ? $array['pendidikan_terakhir'] : old('pendidikan_terakhir') }}</textarea>
                @if($errors->has('pendidikan_terakhir'))
                <div class="invalid-feedback">
                  {{ ucfirst($errors->first('pendidikan_terakhir')) }}
                </div>
                @endif
              </div>
              <div class="form-group col-sm-6">
                <label>Riwayat Pekerjaan:</label>
                <textarea name="riwayat_pekerjaan" class="form-control form-control-sm {{ $errors->has('riwayat_pekerjaan') ? 'is-invalid' : '' }}" placeholder="Masukkan Riwayat Pekerjaan" rows="2">{{ !empty($array) ? $array['riwayat_pekerjaan'] : old('riwayat_pekerjaan') }}</textarea>
                <small class="text-muted">Kosongi saja jika Anda belum memiliki riwayat pekerjaan</small>
              </div>
            </div>
            <div class="form-group mt-3">
              <div class="row">
                <div class="col-auto ml-auto">
                  <input type="hidden" name="url" value="{{ $url_form }}">
                  <button type="submit" class="btn btn-sm btn-primary rounded">Selanjutnya &raquo;</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div id="footer-main"></div>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
  <script src="https://psikologanda.com/assets/partials/template.js"></script>
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
      
        // Show loader on submit
        $(document).on("submit", "#form", function(e){
            e.preventDefault();
            $(".preloader").show();
            $("#form")[0].submit();
        });
    });
  </script>
  <script type="text/javascript">
    $(document).on('click','#sidebarCollapse',function(e){
      e.preventDefault();
      $('#sidebar').hasClass('active') 
        ? $('#sidebar').removeClass('active') 
        : $('#sidebar').addClass('active');
      $(this).find('i').hasClass('ti-menu') 
        ? $(this).find('i').removeClass('ti-menu').addClass('ti-close') 
        : $(this).find('i').addClass('ti-menu').removeClass('ti-close');
    })

    $(document).on("click", "#sidebar > .sidebar-menu > .menu-label.sidebar-dropdown > a", function(e){
      e.preventDefault();
      $(this).parent(".menu-label").hasClass("active") 
        ? $(this).parent(".menu-label").removeClass("active") 
        : $(this).parent(".menu-label").addClass("active");
    })
  </script>
</body>
</html>