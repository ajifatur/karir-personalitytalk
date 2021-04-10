@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Detail Karyawan</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/karyawan">Karyawan</a></li>
      <li class="breadcrumb-item active" aria-current="page">Detail Karyawan</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-body">
        <div class="form-row">
          <div class="col-auto p-3 border border-muted mb-2 mr-2">
            <img src="{{ $karyawan->user->foto != '' ? asset('assets/images/foto-karyawan/'.$karyawan->user->foto) : asset('assets/images/default/user.png') }}" class="img-fluid" width="200" alt="Foto">
          </div>
        </div>
        <div class="form-row">
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <td>Nama Lengkap</td>
                <td width="10">:</td>
                <td>{{ $karyawan->nama_lengkap }}</td>
              </tr>
              <tr>
                <td>Username</td>
                <td>:</td>
                <td>{{ $karyawan->user->username }}</td>
              </tr>
              <tr>
                <td>Tanggal Lahir</td>
                <td>:</td>
                <td>{{ generate_date($karyawan->tanggal_lahir) }} ({{ generate_age($karyawan->tanggal_lahir) }} tahun)</td>
              </tr>
              <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $karyawan->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
              </tr>
              <tr>
                <td>Email</td>
                <td>:</td>
                <td>{{ $karyawan->email }}</td>
              </tr>
              <tr>
                <td>Nomor HP</td>
                <td>:</td>
                <td>{{ $karyawan->nomor_hp }}</td>
              </tr>
              <tr>
                <td>Perusahaan</td>
                <td>:</td>
                <td>{{ get_perusahaan_name($karyawan->id_hrd) }}</td>
              </tr>
              <tr>
                <td>Kantor</td>
                <td>:</td>
                <td>{{ $karyawan->kantor ? $karyawan->kantor->nama_kantor : '-' }}</td>
              </tr>
              <tr>
                <td>Posisi</td>
                <td>:</td>
                <td>{{ $karyawan->posisi ? $karyawan->posisi->nama_posisi : '-' }}</td>
              </tr>
              <tr>
                <td>Status</td>
                <td>:</td>
                <td><span class="badge {{ $karyawan->user->status == 1 ? 'badge-success' : 'badge-danger' }}">{{ $karyawan->user->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</span></td>
              </tr>
              <tr>
                <td>Awal Bekerja</td>
                <td>:</td>
                <td>{{ $karyawan->awal_bekerja != '' ? generate_date($karyawan->awal_bekerja) : '-' }}</td>
              </tr>
              <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $karyawan->nik != '' ? $karyawan->nik : '-' }}</td>
              </tr>
<!--               <tr>
                <td>NIK CIS</td>
                <td>:</td>
                <td>{{ $karyawan->nik_cis != '' ? $karyawan->nik_cis : '-' }}</td>
              </tr> -->
              <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $karyawan->alamat }}</td>
              </tr>
              <tr>
                <td>Pendidikan Terakhir</td>
                <td>:</td>
                <td>{{ $karyawan->pendidikan_terakhir }}</td>
              </tr>
            </table>
          </div>
        </div>
    </div>
  </div>

@endsection

@section('css-extra')
<style type="text/css">
  .table {min-width: 600px;}
  .table tr td {padding: .5rem;}
  .table tr td:first-child {font-weight: bold; min-width: 200px; width: 200px;}
</style>

@endsection