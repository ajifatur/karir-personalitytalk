<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Registrasi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/assets/css/style.css">
  <style type="text/css">
    .card-header span {display: inline-block; height: 12px; width: 12px; margin: 0px 5px; border-radius: 50%; background: rgba(0,0,0,.2);}
    .card-header span.active {background: var(--primary)!important;}
    .wrapper{min-height: calc(100vh - 19rem)}
</style>
</head>
<body>
  <div id="sidebar-main"></div>
  <div id="navbar-main"></div>
  <div class="wrapper container py-lg-5 py-md-3 pt-1">
    <div class="row justify-content-center h-100">
      <div class="col-lg-8">
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
              <div class="row mb-3">
                <div class="form-group col-md-6">
                  <label>Nama Lengkap: <span class="text-danger">*</span></label>
                  <input name="nama_lengkap" type="text" class="form-control {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}" placeholder="Masukkan Nama Lengkap" value="{{ !empty($array) ? $array['nama_lengkap'] : old('nama_lengkap') }}">
                  @if($errors->has('nama_lengkap'))
                  <div class="invalid-feedback">
                    {{ ucfirst($errors->first('nama_lengkap')) }}
                  </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label>Email: <span class="text-danger">*</span></label>
                  <input name="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Masukkan Email" value="{{ !empty($array) ? $array['email'] : old('email') }}">
                  @if($errors->has('email'))
                  <div class="invalid-feedback">
                    {{ ucfirst($errors->first('email')) }}
                  </div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <div class="form-group col-md-6">
                  <label>Tempat Lahir: <span class="text-danger">*</span></label>
                  <input name="tempat_lahir" type="text" class="form-control {{ $errors->has('tempat_lahir') ? 'is-invalid' : '' }}" placeholder="Masukkan Tempat Lahir" value="{{ !empty($array) ? $array['tempat_lahir'] : old('tempat_lahir') }}">
                  @if($errors->has('tempat_lahir'))
                  <div class="invalid-feedback">
                    {{ ucfirst($errors->first('tempat_lahir')) }}
                  </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label>Tanggal Lahir: <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input name="tanggal_lahir" type="text" class="form-control {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }}" placeholder="Masukkan Tanggal Lahir" value="{{ !empty($array) ? $array['tanggal_lahir'] : old('tanggal_lahir') }}">
                  </div>
                  @if($errors->has('tanggal_lahir'))
                  <small class="text-danger">
                    {{ ucfirst($errors->first('tanggal_lahir')) }}
                  </small>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <div class="form-group col-md-6">
                  <label>Jenis Kelamin: <span class="text-danger">*</span></label>
                  <select name="jenis_kelamin" class="form-select {{ $errors->has('jenis_kelamin') ? 'is-invalid' : '' }}">
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
                <div class="form-group col-md-6">
                  <label>Agama: <span class="text-danger">*</span></label>
                  <select name="agama" class="form-select {{ $errors->has('agama') ? 'is-invalid' : '' }}">
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
              <div class="row mb-3">
                <div class="form-group col-md-6">
                  <label>Akun Sosial Media: <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <select name="sosmed" class="form-select {{ $errors->has('akun_sosmed') ? 'border-danger' : '' }}">
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
                    <input name="akun_sosmed" type="text" class="form-control w-50 {{ $errors->has('akun_sosmed') ? 'is-invalid' : '' }}" placeholder="Masukkan Akun Sosmed" value="{{ !empty($array) ? $array['akun_sosmed'] : old('akun_sosmed') }}">
                  </div>
                  @if($errors->has('akun_sosmed'))
                  <small class="text-danger">
                    {{ ucfirst($errors->first('akun_sosmed')) }}
                  </small>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label>No. HP: <span class="text-danger">*</span></label>
                  <input name="nomor_hp" type="text" class="form-control {{ $errors->has('nomor_hp') ? 'is-invalid' : '' }}" placeholder="Masukkan Nomor HP" value="{{ !empty($array) ? $array['nomor_hp'] : old('nomor_hp') }}">
                  @if($errors->has('nomor_hp'))
                  <div class="invalid-feedback">
                    {{ ucfirst($errors->first('nomor_hp')) }}
                  </div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <div class="form-group col-md-6">
                  <label>No. Telepon:</label>
                  <input name="nomor_telepon" type="text" class="form-control {{ $errors->has('nomor_telepon') ? 'is-invalid' : '' }}" placeholder="Masukkan Nomor Telepon" value="{{ !empty($array) ? $array['nomor_telepon'] : old('nomor_telepon') }}">
                  @if($errors->has('nomor_telepon'))
                  <div class="invalid-feedback">
                    {{ ucfirst($errors->first('nomor_telepon')) }}
                  </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label>No. KTP:</label>
                  <input name="nomor_ktp" type="text" class="form-control {{ $errors->has('nomor_ktp') ? 'is-invalid' : '' }}" placeholder="Masukkan Nomor KTP" value="{{ !empty($array) ? $array['nomor_ktp'] : old('nomor_ktp') }}">
                  @if($errors->has('nomor_ktp'))
                  <div class="invalid-feedback">
                    {{ ucfirst($errors->first('nomor_ktp')) }}
                  </div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <div class="form-group col-md-6">
                  <label>Alamat: <span class="text-danger">*</span></label>
                  <textarea name="alamat" class="form-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}" placeholder="Masukkan Alamat" rows="2">{{ !empty($array) ? $array['alamat'] : old('alamat') }}</textarea>
                  @if($errors->has('alamat'))
                  <div class="invalid-feedback">
                    {{ ucfirst($errors->first('alamat')) }}
                  </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label>Status Hubungan: <span class="text-danger">*</span></label>
                  <select name="status_hubungan" class="form-select {{ $errors->has('status_hubungan') ? 'is-invalid' : '' }}">
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
              <div class="row mb-3">
                <div class="form-group col-md-6">
                  <label>Pendidikan Terakhir: <span class="text-danger">*</span></label>
                  <textarea name="pendidikan_terakhir" class="form-control {{ $errors->has('pendidikan_terakhir') ? 'is-invalid' : '' }}" placeholder="Masukkan Pendidikan Terakhir" rows="2">{{ !empty($array) ? $array['pendidikan_terakhir'] : old('pendidikan_terakhir') }}</textarea>
                  @if($errors->has('pendidikan_terakhir'))
                  <div class="invalid-feedback">
                    {{ ucfirst($errors->first('pendidikan_terakhir')) }}
                  </div>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label>Riwayat Pekerjaan:</label>
                  <textarea name="riwayat_pekerjaan" class="form-control {{ $errors->has('riwayat_pekerjaan') ? 'is-invalid' : '' }}" placeholder="Masukkan Riwayat Pekerjaan" rows="2">{{ !empty($array) ? $array['riwayat_pekerjaan'] : old('riwayat_pekerjaan') }}</textarea>
                  <small class="text-muted">Kosongi saja jika Anda belum memiliki riwayat pekerjaan</small>
                </div>
              </div>
              <div class="form-group mt-3">
                <div class="row">
                  <div class="col-auto ms-auto">
                    <input type="hidden" name="url" value="{{ $url_form }}">
                    <button type="submit" class="btn btn-primary rounded">Selanjutnya &raquo;</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="footer-main"></div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
  <script src="http://psikologanda.com/assets/partials/template.js"></script>
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
</body>
</html>