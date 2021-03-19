@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Dashboard</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </ol>
  </div>

  <!-- Content Row -->
  <div class="row mb-3">
	  <div class="col-12">
		  <div class="alert alert-success text-center" role="alert">
			  Selamat datang di PersonalityTalk, <strong>{{ Auth::user()->nama_user }}</strong>. Anda masuk sebagai <strong>{{ get_role_name(Auth::user()->role) }}</strong>.
		  </div>
	  </div>
  </div>

  <!-- Content Row -->
  <div class="row" id="section-card">

    @if(Auth::user()->role == role_admin())
    <!-- HRD -->
    <a class="col-lg-3 col-md-4 col-sm-6 mb-4" href="/admin/hrd">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">HRD</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($hrd,0,'.','.') }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-shield fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </a>
    @endif

    @if(Auth::user()->role == role_admin())
    <!-- Perusahaan -->
    <a class="col-lg-3 col-md-4 col-sm-6 mb-4" href="/admin/hrd">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Perusahaan</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($perusahaan,0,'.','.') }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-layer-group fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </a>
    @endif

    <!-- Kantor -->
    <a class="col-lg-3 col-md-4 col-sm-6 mb-4" href="/admin/kantor">
      <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
          <div class="row no-gutters align-items-center">
              <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kantor</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($kantor,0,'.','.') }}</div>
              </div>
              <div class="col-auto">
              <i class="fas fa-building fa-2x text-gray-300"></i>
              </div>
          </div>
          </div>
      </div>
    </a>

    <!-- Jabatan -->
    <a class="col-lg-3 col-md-4 col-sm-6 mb-4" href="/admin/posisi">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jabatan</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($posisi,0,'.','.') }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-route fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </a>

    <!-- Karyawan -->
    <a class="col-lg-3 col-md-4 col-sm-6 mb-4" href="/admin/karyawan">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Karyawan</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($karyawan,0,'.','.') }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-tie fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </a>

    <!-- Pelamar -->
    <a class="col-lg-3 col-md-4 col-sm-6 mb-4" href="/admin/pelamar">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pelamar</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ number_format($pelamar,0,'.','.') }}</div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-check fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </a>

    <!-- Lowongan -->
    <a class="col-lg-3 col-md-4 col-sm-6 mb-4" href="/admin/lowongan">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Lowongan</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($lowongan,0,'.','.') }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-bolt fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </a>

    <!-- Hasil Tes Karyawan -->
    <a class="col-lg-3 col-md-4 col-sm-6 mb-4" href="/admin/hasil/karyawan">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Hasil Tes Karyawan</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ number_format($hasil_karyawan,0,'.','.') }}</div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-chart-line fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </a>

    <!-- Hasil Tes Pelamar -->
    <a class="col-lg-3 col-md-4 col-sm-6 mb-4" href="/admin/hasil/pelamar">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Hasil Tes Pelamar</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ number_format($hasil_pelamar,0,'.','.') }}</div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-chart-line fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </a>

    @if(Auth::user()->role == role_admin())
    <!-- Hasil Tes Magang -->
    <a class="col-lg-3 col-md-4 col-sm-6 mb-4" href="/admin/hasil/magang">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Hasil Tes Magang</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ number_format($hasil_magang,0,'.','.') }}</div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-chart-line fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </a>
    @endif
	  
  </div>

  <!-- Content Row -->

@endsection

@section('css-extra')

<!-- CSS -->
<style type="text/css">
    #section-card a:hover {text-decoration: none;}
    #section-card a:hover .card {background-color: #f5f5f5;}
</style>

@endsection