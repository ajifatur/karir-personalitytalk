@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Data Seleksi</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/seleksi">Seleksi</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Seleksi</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <div>
        <select id="hasil-tes" class="form-control form-control-sm">
          <option value="-1" {{ isset($_GET) && isset($_GET['hasil']) && $_GET['hasil'] == -1 ? 'selected' : '' }}>Semua Hasil</option>
          <option value="1" {{ isset($_GET) && isset($_GET['hasil']) && $_GET['hasil'] == 1 ? 'selected' : '' }}>Lolos</option>
          <option value="0" {{ isset($_GET) && isset($_GET['hasil']) && $_GET['hasil'] == 0 ? 'selected' : '' }}>Tidak Lolos</option>
          <option value="99" {{ isset($_GET) && isset($_GET['hasil']) && $_GET['hasil'] == 99 ? 'selected' : '' }}>Belum Dites</option>
        </select>
      </div>
      @if(Auth::user()->role == role_admin())
      <div>
        <select id="hrd" class="form-control form-control-sm">
          <option value="0">Semua Perusahaan</option>
          @foreach(get_hrd() as $data)
          <option value="{{ $data->id_hrd }}" {{ isset($_GET) && isset($_GET['hrd']) && $_GET['hrd'] == $data->id_hrd ? 'selected' : '' }}>{{ $data->perusahaan }}</option>
          @endforeach
        </select>
      </div>
      @endif
    </div>
    <div class="card-body">
      @if(Session::get('message') != null)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ Session::get('message') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th width="20"><input type="checkbox"></th>
              <th>Identitas</th>
              <th width="100">Username</th>
              <th width="150">Posisi</th>
              <th width="100">Hasil</th>
              <th width="100">Waktu Tes</th>
              <th width="80">Opsi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($seleksi as $data)
            <tr>
              <td><input type="checkbox"></td>
              <td>
                <a href="/admin/pelamar/detail/{{ $data->id_pelamar }}">{{ ucwords($data->nama_lengkap) }}</a>
                <br>
                <small class="text-muted"><i class="fa fa-envelope mr-2"></i>{{ $data->email }}</small>
                <br>
                <small class="text-muted"><i class="fa fa-phone mr-2"></i>{{ $data->nomor_hp }}</small>
              </td>
              <td>{{ $data->username }}</td>
              <td>{{ $data->nama_posisi }}<br><small class="text-muted">{{ get_perusahaan_name($data->id_hrd) }}</small></td>
              <td>
                @if($data->hasil == 1)
                  <span class="badge badge-success">Lolos</span>
                @elseif($data->hasil == 0)
                  <span class="badge badge-danger">Tidak Lolos</span>
                @elseif($data->hasil == 99)
				          <span class="badge badge-warning">Belum Dites</span>
                @endif
              </td>
              <td>
                <span class="d-none">{{ $data->waktu_wawancara != null ? $data->waktu_wawancara : '' }}</span>
                {{ $data->waktu_wawancara != null ? date('d/m/Y', strtotime($data->waktu_wawancara)) : '-' }}
                <br>
                <small class="text-muted">{{ $data->waktu_wawancara != null ? date('H:i', strtotime($data->waktu_wawancara)).' WIB' : '' }}</small>
              </td>
              <td>
                <div class="btn-group">
                  @if($data->hasil == 1 && $data->isKaryawan == false)
                  <a href="#" class="btn btn-sm btn-success convert" data-id="{{ $data->id_seleksi }}" data-toggle="tooltip" data-placement="top" title="Lantik Menjadi Karyawan"><i class="fa fa-check"></i></a>
                  @endif
                  <a href="#" class="btn btn-sm btn-warning {{ $data->isKaryawan ? 'not-allowed' : 'edit' }}" data-id="{{ $data->id_seleksi }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                  <a href="#" class="btn btn-sm btn-danger {{ $data->isKaryawan ? 'not-allowed' : 'btn-delete' }}" data-id="{{ $data->id_seleksi }}" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <form id="form-delete" class="d-none" method="post" action="/admin/seleksi/delete">
            {{ csrf_field() }}
            <input type="hidden" name="id">
        </form>
      </div>
    </div>
  </div>

  <!-- Set Test Time Modal-->
  <div class="modal fade" id="TimeTestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Data Seleksi</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="/admin/seleksi/update">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <label>Hasil:</label>
              <select name="hasil" id="hasil" class="form-control custom-select">
                <option value="" disabled selected>--Pilih--</option>
                <option value="1" {{ old('hasil') == 1 ? 'selected' : '' }}>Lolos</option>
                <option value="0" {{ old('hasil') == 0 ? 'selected' : '' }}>Tidak Lolos</option>
                <option value="99" {{ old('hasil') == 99 ? 'selected' : '' }}>Belum Dites</option>
              </select>
              @if($errors->has('hasil'))
                <small class="text-danger">{{ ucfirst($errors->first('hasil')) }}</small>
              @endif
            </div>
            <div class="form-group">
              <label>Tanggal:</label>
              <div class="input-group">
                <input type="text" id="tanggal" name="tanggal" class="form-control {{ $errors->has('tanggal') ? 'border-danger' : '' }}" value="{{ old('tanggal') }}" placeholder="Format: dd/mm/yyyy">
                <div class="input-group-append">
                  <button class="btn {{ $errors->has('tanggal') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-show-datepicker" type="button" data-toggle="tooltip" data-placement="top" title="Atur Tanggal"><i class="fa fa-calendar"></i></button>
                </div>
              </div>
              @if($errors->has('tanggal'))
                <small class="text-danger">{{ ucfirst($errors->first('tanggal')) }}</small>
              @endif
            </div>
            <div class="form-group">
              <label>Jam:</label>
              <div class="input-group">
                <input type="text" id="jam" name="jam" class="form-control {{ $errors->has('jam') ? 'border-danger' : '' }}" value="{{ old('jam') }}" placeholder="Format: H:m" readonly data-placement="bottom" data-align="top" data-autoclose="true">
                <div class="input-group-append">
                  <button class="btn {{ $errors->has('jam') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-show-clockpicker" type="button" data-toggle="tooltip" data-placement="top" title="Atur Jam"><i class="fa fa-clock"></i></button>
                </div>
              </div>
              @if($errors->has('jam'))
                <small class="text-danger">{{ ucfirst($errors->first('jam')) }}</small>
              @endif
            </div>
            <div class="form-group">
              <label>Tempat:</label>
              <div class="input-group">
                <input type="text" id="tempat" name="tempat" class="form-control {{ $errors->has('tempat') ? 'border-danger' : '' }}" value="{{ old('tempat') }}" placeholder="Tempat Tes">
              </div>
              @if($errors->has('tempat'))
                <small class="text-danger">{{ ucfirst($errors->first('tempat')) }}</small>
              @endif
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="id" id="id" value="{{ old('id') }}">
            <button class="btn btn-primary" type="submit">Simpan</button>
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Set Test Time Modal-->
  <div class="modal fade" id="ConvertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Lantik Sebagai Karyawan</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="/admin/seleksi/convert">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <label>Tanggal Awal Bekerja:</label>
              <div class="input-group">
                <input type="text" id="awal_bekerja" name="awal_bekerja" class="form-control" placeholder="Format: dd/mm/yyyy">
                <div class="input-group-append">
                  <button class="btn btn-outline-primary btn-show-datepicker-2" type="button" data-toggle="tooltip" data-placement="top" title="Atur Tanggal"><i class="fa fa-calendar"></i></button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Kantor:</label>
              <select name="kantor" class="form-control custom-select">
				@foreach($kantor as $data)
                <option value="{{ $data->id_kantor }}" {{ old('kantor') == $data->id_kantor ? 'selected' : '' }}>{{ $data->nama_kantor }}</option>
				@endforeach
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="id" id="id-2">
            <button class="btn btn-primary" type="submit" disabled>Simpan</button>
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
@endsection

@section('css-extra')

<!-- Custom styles for this page -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css" integrity="sha256-lBtf6tZ+SwE/sNMR7JFtCyD44snM3H2FrkB/W400cJA=" crossorigin="anonymous" />

@endsection

@section('js-extra')

<!-- Page level plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js" integrity="sha256-LPgEyZbedErJpp8m+3uasZXzUlSl9yEY4MMCEN9ialU=" crossorigin="anonymous"></script>

<!-- JavaScripts -->
<script type="text/javascript">
  $(document).ready(function() {
    // Call the dataTables jQuery plugin
    generate_datatable("#dataTable");

    $("input[name=jam]").clockpicker();

    $(document).on("click", ".btn-show-datepicker", function(e){
      e.preventDefault();
      $('input[name=tanggal]').focus();
    });

    $(document).on("click", ".btn-show-datepicker-2", function(e){
      e.preventDefault();
      $('input[name=awal_bekerja]').focus();
    });

    $(document).on("click", ".btn-show-clockpicker", function(e){
      e.preventDefault();
      $('input[name=jam]').focus();
    })

    // Button Not Allowed
    $(document).on("click", ".not-allowed", function(e){
      e.preventDefault();
	  alert("Anda tidak diizinkan untuk melakukan aksi pada data ini!");
    });

    // Edit
    $(document).on("click", ".edit", function(e){
      e.preventDefault();
      var id = $(this).data("id");
      $.ajax({
        type: "post",
        url: "/admin/seleksi/data",
        data: {_token: "{{ csrf_token() }}", id: id},
        success: function(response){
          var result = JSON.parse(response);
          if(result.hasil == 1 || result.hasil == 99){
            $("input[name=tanggal]").datepicker({
              format: 'dd/mm/yyyy',
              autoclose: true,
              todayHighlight: true
            });

            $("#hasil").val(result.hasil);
            $("#tanggal").val(result.tanggal_wawancara);
            $("#jam").val(result.waktu_wawancara.split(" ")[1].substr(0,5));
            $("#tempat").val(result.tempat_wawancara);
            $("#id").val(result.id_seleksi);
          }
          else if(result.hasil == 0){
            $("#hasil").val(result.hasil);
            $("#tanggal").val(null);
            $("#jam").val(null);
            $("#tempat").val(null);
            $("#id").val(result.id_seleksi);
          }
          $("#TimeTestModal").modal("show");
        }
      });
    });
    
    // Change value on select: hasil
    $(document).on("change", "#hasil", function(){
        var hasil = $(this).val();
        if(hasil == 1 || hasil == 0){
            $("#tanggal").attr("disabled","disabled");
            $("#jam").attr("disabled","disabled");
            $("#tempat").attr("disabled","disabled");
            $(".btn-show-datepicker").attr("disabled","disabled");
            $(".btn-show-clockpicker").attr("disabled","disabled");
        }
        else if(hasil == 99){
            $("#tanggal").removeAttr("disabled");
            $("#jam").removeAttr("disabled");
            $("#tempat").removeAttr("disabled");
            $(".btn-show-datepicker").removeAttr("disabled");
            $(".btn-show-clockpicker").removeAttr("disabled");
        }
    });

    // Convert
    $(document).on("click", ".convert", function(e){
      e.preventDefault();
      var id = $(this).data("id");
      $("#id-2").val(id);
      $("input[name=awal_bekerja]").datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true
      });
      $("#ConvertModal").modal("show");
    });
    
    // Change value on input: awal_bekerja
    $(document).on("change", "#awal_bekerja", function(){
		  if($(this).val() != null) $("#ConvertModal").find("button[type=submit]").removeAttr("disabled");
    });
	  
  });
  
  // Change HRD dan Hasil Tes
  $(document).on("change", "#hrd, #hasil-tes", function(){
    var hasil = $("#hasil-tes").val();
    var hrd = $("#hrd").length == 1 ? $("#hrd").val() : null;

    if(hrd != null){
      if(hasil == -1 && hrd == 0) window.location.href = '/admin/seleksi';
      else window.location.href = '/admin/seleksi?hrd='+hrd+'&hasil='+hasil;
    }
    else{
      if(hasil == -1) window.location.href = '/admin/seleksi';
      else window.location.href = '/admin/seleksi?hasil='+hasil;
    }
  });
</script>

@if(count($errors) > 0)
<script type="text/javascript">
  $(function(){
    // Show modal when the page is loaded
    $("#TimeTestModal").modal("toggle");
  });
</script>
@endif

@endsection