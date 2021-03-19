@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Data Hasil Tes</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/hasil">Hasil Tes</a></li>
      <li class="breadcrumb-item active" aria-current="page">MSDT</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <div></div>
      @if(Auth::user()->role == role_admin())
      <div>
          <a class="btn btn-sm btn-primary btn-print" href="#">
            <i class="fas fa-print fa-sm fa-fw text-gray-400"></i> Cetak
          </a>
      </div>
      @endif
    </div>
      <div class="card-body">
        <form id="form" class="d-none" method="post" action="/admin/hasil/print" target="_blank">
            {{ csrf_field() }}
            <input type="hidden" name="id_hasil" value="{{ $hasil->id_hasil }}">
            <input type="hidden" name="nama" value="{{ $user->nama_user }}">
            <input type="hidden" name="usia" value="{{ generate_age($user->tanggal_lahir, $hasil->created_at).' tahun' }}">
            <input type="hidden" name="jenis_kelamin" value="{{ $user->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}">
            <input type="hidden" name="posisi" value="{{ !empty($user_desc) ? $user_desc->nama_posisi.' ('.$role->nama_role.')' : $role->nama_role }}">
            <input type="hidden" name="tes" value="{{ $hasil->nama_tes }}">
            <input type="hidden" name="path" value="{{ $hasil->path }}">
        </form>
        <div class="row">
          <div class="col-auto mx-auto">
            <div class="table-responsive">
                <table class="table table-borderless table-identity">
                  <tr>
                    <td width="200">Nama: {{ $user->nama_user }}</td>
                    <td width="200">Usia: {{ generate_age($user->tanggal_lahir, $hasil->created_at).' tahun' }}</td>
                    <td width="200">Jenis Kelamin: {{ $user->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                    <td width="200">Posisi: {{ !empty($user_desc) ? $user_desc->nama_posisi.' ('.$role->nama_role.')' : $role->nama_role }}</td>
                    <td width="200">Tes: {{ $hasil->nama_tes }}</td>
                  </tr>
                </table>
            </div>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col">
            <div class="row">
              <div class="col">
                <p class="h4 text-center font-weight-bold mb-5">Tipe: {{ $hasil->hasil['tipe'] }}</p>
                @foreach($keterangan->keterangan as $ket)
                    @if($ket['tipe'] == strtolower($hasil->hasil['tipe']))
                        {!! html_entity_decode($ket['keterangan']) !!}
                    @endif
                @endforeach
          </div>
            </div>
          </div>
        </div>
      </div>
  </div>
  
@endsection

@section('css-extra')

<style type="text/css">
  table tr th, table tr td {padding: .25rem .5rem; text-align: center;}
  .table-identity {min-width: 1000px};
  .h6.mt-4 {text-align: justify!important;}
</style>

@endsection

@section('js-extra')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
<script type="text/javascript">
$(document).on("click", ".btn-print", function(e){
  e.preventDefault();
  $("#form")[0].submit();
});
</script>

@endsection