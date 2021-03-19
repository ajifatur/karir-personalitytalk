@extends('template/admin/template')

@section('content')

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Edit Soal {{ $tes->nama_tes }}</h1>
  <p class="mb-4">Soal tes {{ $tes->nama_tes }} yang digunakan untuk mengetes calon karyawan.</p>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Edit Soal {{ $tes->nama_tes }}</h6>
    </div>
    <div class="card-body">
      <form method="post" action="/admin/tes/tipe/{{ $id_tes }}/soal/update">
        {{ csrf_field() }}
        <div class="form-row">
          <div class="form-group col-auto">
            <label>No. Soal:</label>
            <select name="nomor" class="form-control custom-select {{ $errors->has('nomor') ? 'is-invalid' : '' }}">
              <option value="" disabled selected>--Pilih--</option>
              @foreach($no_soal_tersedia as $no)
              <option value="{{ $no }}" {{ $soal->nomor == $no ? 'selected' : '' }}>{{ $no }}</option>
              @endforeach
            </select>
            <input type="hidden" name="nomor_old" value="{{ $soal->nomor }}">
            @if($errors->has('nomor'))
            <div class="invalid-feedback">
              {{ ucfirst($errors->first('nomor')) }}
            </div>
            @endif
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-row">
              <div class="form-group col-12 col-sm">
                <label>Pilihan A:</label>
                <input type="text" name="pilihan[A]" class="form-control pilihan {{ $errors->has('pilihan.A') ? 'is-invalid' : '' }}" placeholder="Masukkan Pilihan A" value="{{ $soal->soal[0]['pilihan']['A'] }}">
                @if($errors->has('pilihan.A'))
                <div class="invalid-feedback">
                  {{ str_replace(".", " ", ucfirst($errors->first('pilihan.A'))) }}
                </div>
                @endif
              </div>
              <div class="form-group col-12 col-sm-auto">
                <label>DISC A:</label>
                <select name="disc[A]" class="form-control custom-select disc {{ $errors->has('disc.A') ? 'is-invalid' : '' }}" data-id="disc-a">
                  <option value="">--Pilih--</option>
                  <option value="D" {{ $soal->soal[0]['disc']['A'] == 'D' ? 'selected' : '' }}>D</option>
                  <option value="I" {{ $soal->soal[0]['disc']['A'] == 'I' ? 'selected' : '' }}>I</option>
                  <option value="S" {{ $soal->soal[0]['disc']['A'] == 'S' ? 'selected' : '' }}>S</option>
                  <option value="C" {{ $soal->soal[0]['disc']['A'] == 'C' ? 'selected' : '' }}>C</option>
                </select>
                @if($errors->has('disc.A'))
                <div class="invalid-feedback">
                  {{ str_replace(".", " ", ucfirst($errors->first('disc.A'))) }}
                </div>
                @endif
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-row">
              <div class="form-group col-12 col-sm">
                <label>Pilihan B:</label>
                <input type="text" name="pilihan[B]" class="form-control pilihan {{ $errors->has('pilihan.B') ? 'is-invalid' : '' }}" placeholder="Masukkan Pilihan B" value="{{ $soal->soal[0]['pilihan']['B'] }}">
                @if($errors->has('pilihan.B'))
                <div class="invalid-feedback">
                  {{ str_replace(".", " ", ucfirst($errors->first('pilihan.B'))) }}
                </div>
                @endif
              </div>
              <div class="form-group col-12 col-sm-auto">
                <label>DISC B:</label>
                <select name="disc[B]" class="form-control custom-select disc {{ $errors->has('disc.B') ? 'is-invalid' : '' }}" data-id="disc-b">
                  <option value="">--Pilih--</option>
                  <option value="D" {{ $soal->soal[0]['disc']['B'] == 'D' ? 'selected' : '' }}>D</option>
                  <option value="I" {{ $soal->soal[0]['disc']['B'] == 'I' ? 'selected' : '' }}>I</option>
                  <option value="S" {{ $soal->soal[0]['disc']['B'] == 'S' ? 'selected' : '' }}>S</option>
                  <option value="C" {{ $soal->soal[0]['disc']['B'] == 'C' ? 'selected' : '' }}>C</option>
                </select>
                @if($errors->has('disc.B'))
                <div class="invalid-feedback">
                  {{ str_replace(".", " ", ucfirst($errors->first('disc.B'))) }}
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-row">
              <div class="form-group col-12 col-sm">
                <label>Pilihan C:</label>
                <input type="text" name="pilihan[C]" class="form-control pilihan {{ $errors->has('pilihan.C') ? 'is-invalid' : '' }}" placeholder="Masukkan Pilihan C" value="{{ $soal->soal[0]['pilihan']['C'] }}">
                @if($errors->has('pilihan.C'))
                <div class="invalid-feedback">
                  {{ str_replace(".", " ", ucfirst($errors->first('pilihan.C'))) }}
                </div>
                @endif
              </div>
              <div class="form-group col-12 col-sm-auto">
                <label>DISC C:</label>
                <select name="disc[C]" class="form-control custom-select disc {{ $errors->has('disc.C') ? 'is-invalid' : '' }}" data-id="disc-c">
                  <option value="">--Pilih--</option>
                  <option value="D" {{ $soal->soal[0]['disc']['C'] == 'D' ? 'selected' : '' }}>D</option>
                  <option value="I" {{ $soal->soal[0]['disc']['C'] == 'I' ? 'selected' : '' }}>I</option>
                  <option value="S" {{ $soal->soal[0]['disc']['C'] == 'S' ? 'selected' : '' }}>S</option>
                  <option value="C" {{ $soal->soal[0]['disc']['C'] == 'C' ? 'selected' : '' }}>C</option>
                </select>
                @if($errors->has('disc.C'))
                <div class="invalid-feedback">
                  {{ str_replace(".", " ", ucfirst($errors->first('disc.C'))) }}
                </div>
                @endif
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-row">
              <div class="form-group col-12 col-sm">
                <label>Pilihan D:</label>
                <input type="text" name="pilihan[D]" class="form-control pilihan {{ $errors->has('pilihan.D') ? 'is-invalid' : '' }}" placeholder="Masukkan Pilihan D" value="{{ $soal->soal[0]['pilihan']['D'] }}">
                @if($errors->has('pilihan.D'))
                <div class="invalid-feedback">
                  {{ str_replace(".", " ", ucfirst($errors->first('pilihan.D'))) }}
                </div>
                @endif
              </div>
              <div class="form-group col-12 col-sm-auto">
                <label>DISC D:</label>
                <select name="disc[D]" class="form-control custom-select disc {{ $errors->has('disc.D') ? 'is-invalid' : '' }}" data-id="disc-d">
                  <option value="">--Pilih--</option>
                  <option value="D" {{ $soal->soal[0]['disc']['D'] == 'D' ? 'selected' : '' }}>D</option>
                  <option value="I" {{ $soal->soal[0]['disc']['D'] == 'I' ? 'selected' : '' }}>I</option>
                  <option value="S" {{ $soal->soal[0]['disc']['D'] == 'S' ? 'selected' : '' }}>S</option>
                  <option value="C" {{ $soal->soal[0]['disc']['D'] == 'C' ? 'selected' : '' }}>C</option>
                </select>
                @if($errors->has('disc.D'))
                <div class="invalid-feedback">
                  {{ str_replace(".", " ", ucfirst($errors->first('disc.D'))) }}
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <input type="hidden" name="id" value="{{ $soal->id_soal }}">
          <input type="hidden" name="id_paket" value="{{ $id_paket }}">
          <input type="hidden" name="id_tes" value="{{ $id_tes }}">
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="/admin/tes/tipe/{{ $id_tes }}/paket/soal/{{ $id_paket }}" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>

@endsection

@section('css-extra')

<!-- Custom styles for this page -->
<link href="{{ asset('templates/sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

@endsection

@section('js-extra')

<!-- Page level plugins -->
<script src="{{ asset('templates/sb-admin-2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('templates/sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- JavaScripts -->
<script type="text/javascript">
  $(document).ready(function() {
    // Call the dataTables jQuery plugin
    $('#dataTable').DataTable();

    // Tooltip
    $('[data-toggle="tooltip"]').tooltip();

    // Change value on select
    $(document).on("change", ".disc", function(){
      var id = $(this).data("id");
      var value = $(this).val();
      var arrayValue = [];
      var arrayId = [];

      $(".disc").each(function(key,elem){
        if($(elem).data("id") != id){
          arrayValue.push($(elem).val());
          arrayId.push($(elem).data("id"));
        }
      });

      if($.inArray(value, arrayValue)>=0){
        var index = arrayValue.indexOf(value);
        $(".disc[data-id=" + arrayId[index] + "]").val("");
      }
    });
  });
</script>

@endsection