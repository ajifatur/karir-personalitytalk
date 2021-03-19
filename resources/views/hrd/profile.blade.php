@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Profil</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item active" aria-current="page">Profil</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="row">
    @include('hrd/_sidebar-profil')
    <div class="col-md col-12">
      <div class="card shadow mb-4">
        <div class="card-body">
          <h5 class="mb-3"><i class="fa fa-tag mr-2"></i>Identitas</h5>
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Nama Lengkap:</span>
              <span>{{ $user->nama_user }}</span>
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Tanggal Lahir:</span>
              <span>{{ $user->tanggal_lahir != null && $user->tanggal_lahir != '0000-00-00' ? generate_date($user->tanggal_lahir) : '-' }}</span>
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Jenis Kelamin:</span>
              <span>{{ $user->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Email:</span>
              <span>{{ $user->email }}</span>
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Username:</span>
              <span>{{ $user->username }}</span>
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Status:</span>
              <span><span class="badge {{ $user->status == 1 ? 'badge-success' : 'badge-danger' }}">{{ $user->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</span></span>
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Kunjungan Terakhir:</span>
              @if($user->last_visit != '')
              <span>{{ generate_date($user->last_visit) }}, {{ date('H:i', strtotime($user->last_visit)) }} WIB</span>
              @else
              <span>-</span>
              @endif
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Registrasi:</span>
              <span>{{ generate_date($user->created_at) }}, {{ date('H:i', strtotime($user->created_at)) }} WIB</span>
            </li>
          </ul>
          @if(Auth::user()->role == role_hrd())
          <h5 class="mb-3 mt-5"><i class="fa fa-tag mr-2"></i>Perusahaan</h5>
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Nama Perusahaan:</span>
              <span>{{ $hrd->perusahaan }}</span>
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Kode:</span>
              <span>{{ $hrd->kode }}</span>
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Alamat:</span>
              <span>{{ $hrd->alamat_perusahaan }}</span>
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">No. Telp:</span>
              <span>{{ $hrd->telepon_perusahaan }}</span>
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Jumlah Kantor:</span>
              <span>{{ number_format(count_kantor_by_perusahaan($hrd->id_hrd),0,'.','.') }}</span>
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Jumlah Jabatan:</span>
              <span>{{ number_format(count_jabatan_by_perusahaan($hrd->id_hrd),0,'.','.') }}</span>
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Jumlah Karyawan:</span>
              <span>{{ number_format(count_karyawan_by_perusahaan($hrd->id_hrd),0,'.','.') }}</span>
            </li>
            <li class="list-group-item d-sm-flex justify-content-between">
              <span class="font-weight-bold">Jumlah Pelamar:</span>
              <span>{{ number_format(count_pelamar_by_perusahaan($hrd->id_hrd),0,'.','.') }}</span>
            </li>
          </ul>
          @endif
        </div>
      </div>
    </div>
  </div>

@endsection

@section('js-extra')

<script type="text/javascript">
</script>

@endsection