@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Edit Pelamar</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/pelamar">Pelamar</a></li>
      <li class="breadcrumb-item active" aria-current="page">Edit Pelamar</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <form method="post" action="/admin/pelamar/update">
        {{ csrf_field() }}
        <div class="form-row">
          <div class="col-auto p-3 border border-muted mb-2 mr-2">
            <img src="{{ asset('assets/images/pas-foto/'.$pelamar->pas_foto) }}" class="img-fluid" width="200">
          </div>
          <div class="col">
            <div class="row">
              <div class="col-sm-auto ml-sm-auto">
                <p class="font-weight-bold text-md-right">
                  <small>Melamar tanggal {{ generate_date($pelamar->pelamar_at) }}, pukul {{ date('H:i:s', strtotime($pelamar->pelamar_at)) }}</small>
                  <br>
                  Untuk Jabatan: {{ $pelamar->posisi->nama_posisi }}
                </p>
              </div>
            </div>
          </div>
        </div>
        @if(count($errors)>0)
        <div class="form-row mt-2">
          <div class="col">
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Masih terdapat beberapa error dalam menyimpan data.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
          </div>
        </div>
        @endif
        <div class="form-row">
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <td>Nama Lengkap <span class="text-danger">*</span></td>
                <td width="10">:</td>
                <td>
                  <input type="text" name="nama_lengkap" class="form-control {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}" value="{{ $pelamar->nama_lengkap }}">
                  @if($errors->has('nama_lengkap'))
                  <div class="text-danger">
                    {{ ucfirst($errors->first('nama_lengkap')) }}
                  </small>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Tempat, Tanggal Lahir <span class="text-danger">*</span></td>
                <td>:</td>
                <td>
                  <div class="d-md-flex">
                    <input type="text" name="tempat_lahir" class="form-control {{ $errors->has('tempat_lahir') ? 'is-invalid' : '' }} col-md" value="{{ $pelamar->tempat_lahir }}">
                    <input type="text" name="tanggal_lahir" class="form-control {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }} col-md ml-md-2 mt-2 mt-md-0" value="{{ generate_date_format($pelamar->tanggal_lahir, 'd/m/y') }}">
                  </div>
                </td>
              </tr>
              <tr>
                <td>Jenis Kelamin <span class="text-danger">*</span></td>
                <td>:</td>
                <td>
                  <select name="jenis_kelamin" class="form-control {{ $errors->has('jenis_kelamin') ? 'is-invalid' : '' }} custom-select col-lg-4">
                    <option value="" disabled>--Pilih--</option>
                    <option value="L" {{ $pelamar->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                    <option value="P" {{ $pelamar->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                  @if($errors->has('jenis_kelamin'))
                  <div class="text-danger">
                    {{ ucfirst($errors->first('jenis_kelamin')) }}
                  </small>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Agama <span class="text-danger">*</span></td>
                <td>:</td>
                <td>
                  <select name="agama" class="form-control {{ $errors->has('agama') ? 'is-invalid' : '' }} custom-select col-lg-4">
                    <option value="" disabled>--Pilih--</option>
                    @foreach($agama as $data)
                    <option value="{{ $data->id_agama }}" {{ $pelamar->agama == $data->id_agama ? 'selected' : '' }}>{{ $data->nama_agama }}</option>
                    @endforeach
                    <option value="99" {{ $pelamar->agama == 99 ? 'selected' : '' }}>Lain-Lain</option>
                  </select>
                  @if($errors->has('agama'))
                  <div class="text-danger">
                    {{ ucfirst($errors->first('agama')) }}
                  </div>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Email <span class="text-danger">*</span></td>
                <td>:</td>
                <td>
                  <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $pelamar->email }}">
                  @if($errors->has('email'))
                  <div class="text-danger">
                    {{ ucfirst($errors->first('email')) }}
                  </div>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Nomor HP <span class="text-danger">*</span></td>
                <td>:</td>
                <td>
                  <input type="text" name="nomor_hp" class="form-control {{ $errors->has('nomor_hp') ? 'is-invalid' : '' }}" value="{{ $pelamar->nomor_hp }}">
                  @if($errors->has('nomor_hp'))
                  <div class="text-danger">
                    {{ ucfirst($errors->first('nomor_hp')) }}
                  </div>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Nomor Telepon</td>
                <td>:</td>
                <td>
                  <input type="text" name="nomor_telepon" class="form-control {{ $errors->has('nomor_telepon') ? 'is-invalid' : '' }}" value="{{ $pelamar->nomor_telepon }}">
                  @if($errors->has('nomor_telepon'))
                  <div class="text-danger">
                    {{ ucfirst($errors->first('nomor_telepon')) }}
                  </div>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Nomor KTP</td>
                <td>:</td>
                <td>
                  <input type="text" name="nomor_ktp" class="form-control {{ $errors->has('nomor_ktp') ? 'is-invalid' : '' }}" value="{{ $pelamar->nomor_ktp }}">
                  @if($errors->has('nomor_ktp'))
                  <div class="text-danger">
                    {{ ucfirst($errors->first('nomor_ktp')) }}
                  </div>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Alamat <span class="text-danger">*</span></td>
                <td>:</td>
                <td>
                  <textarea name="alamat" class="form-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}" rows="2">{{ $pelamar->alamat }}</textarea>
                  @if($errors->has('alamat'))
                  <div class="text-danger">
                    {{ ucfirst($errors->first('alamat')) }}
                  </div>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Pendidikan Terakhir <span class="text-danger">*</span></td>
                <td>:</td>
                <td>
                  <textarea name="pendidikan_terakhir" class="form-control {{ $errors->has('pendidikan_terakhir') ? 'is-invalid' : '' }}" rows="2">{{ $pelamar->pendidikan_terakhir }}</textarea>
                  @if($errors->has('pendidikan_terakhir'))
                  <div class="text-danger">
                    {{ ucfirst($errors->first('pendidikan_terakhir')) }}
                  </div>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Riwayat Pekerjaan</td>
                <td>:</td>
                <td>
                  <textarea name="riwayat_pekerjaan" class="form-control {{ $errors->has('riwayat_pekerjaan') ? 'is-invalid' : '' }}" rows="2">{{ $pelamar->riwayat_pekerjaan }}</textarea>
                  @if($errors->has('riwayat_pekerjaan'))
                  <div class="text-danger">
                    {{ ucfirst($errors->first('riwayat_pekerjaan')) }}
                  </div>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Akun Sosial Media</td>
                <td>:</td>
                <td>
                  <table class="table table-bordered mb-0">
                    @foreach($pelamar->akun_sosmed as $sosmed=>$akun)
                    <tr>
                      <td width="150">{{ $sosmed }}</td>
                      <td width="10">:</td>
                      <td><input type="text" name="akun_sosmed[{{ $sosmed }}]" class="form-control" value="{{ $akun }}"></td>
                    </tr>
                    @endforeach
                  </table>
                </td>
              </tr>
              @foreach($pelamar->data_darurat as $key=>$value)
              <tr>
                <td>{{ replaceJsonKey($key) }}</td>
                <td>:</td>
                <td>
                  <input type="text" name="{{ $key }}" class="form-control {{ $errors->has($key) ? 'is-invalid' : '' }}" value="{{ $value }}">
                  @if($errors->has($key))
                  <div class="text-danger">
                    {{ ucfirst($errors->first($key)) }}
                  </div>
                  @endif
                </td>
              </tr>
              @endforeach
              <tr>
                <td>Keahlian <span class="text-danger">*</span></td>
                <td>:</td>
                <td>
                  <table class="table table-bordered mb-0">
                    @foreach($pelamar->keahlian as $key=>$array)
                    <tr>
                      <td width="150">{{ $array['jenis'] }}</td>
                      <td width="10">:</td>
                      <td>
                          <select name="keahlian[{{ $key }}][skor]" class="form-control {{ $errors->has('keahlian.'.$key.'.skor') ? 'is-invalid' : '' }} custom-select col-lg-4">
                            <option value="" disabled>--Pilih--</option>
                            <option value="3" {{ $array['skor'] == '3' ? 'selected' : '' }}>Baik</option>
                            <option value="2" {{ $array['skor'] == '2' ? 'selected' : '' }}>Cukup</option>
                            <option value="1" {{ $array['skor'] == '1' ? 'selected' : '' }}>Kurang</option>
                          </select>
                          @if($errors->has('keahlian.'.$key.'.skor'))
                          <br>
                          <div class="text-danger">
                            Tingkat penguasaan {{ $array['jenis'] }} wajib diisi.
                          </div>
                          @endif
                      </td>
                    </tr>
                    @endforeach
                  </table>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="form-group mt-3">
          <input type="hidden" name="id" value="{{ $pelamar->id_pelamar }}">
          <input type="hidden" name="id_user" value="{{ $pelamar->id_user }}">
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="/admin/pelamar" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>

@endsection

@section('js-extra')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(function(){
    $('input[name=tanggal_lahir]').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true,
      todayHighlight: true,
    });

    $(document).on("click", ".btn-show-datepicker", function(e){
      e.preventDefault();
      $('input[name=tanggal_lahir]').focus();
    });
  });
</script>

@endsection

@section('css-extra')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />
<style type="text/css">
  .table {min-width: 600px;}
  .table tr td {padding: .5rem;}
  .table tr td:first-child {font-weight: bold; min-width: 200px; width: 200px}
</style>

@endsection