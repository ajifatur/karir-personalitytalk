@extends('template/admin/template')

@section('content')

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Keterangan</h1>
  <p class="mb-4">Keterangan yang dihasilkan dari mengerjakan tes.</p>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Keterangan</h6>
      @if($keterangan)
      <a class="btn btn-sm btn-danger delete" href="#" data-id="{{ $keterangan ? $keterangan->id_keterangan : '' }}">
        <i class="fas fa-trash fa-sm fa-fw text-gray-400"></i> Hapus Keterangan
      </a>
      @endif
    </div>
    <div class="card-body">
      <form method="post" action="/admin/tes/tipe/{{ $id_tes }}/paket/keterangan/save">
        {{ csrf_field() }}
        @if(Session::get('message') != null)
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Keterangan D</label>
              <textarea name="d" class="form-control" placeholder="Tuliskan sesuatu..." required>{!! $keterangan ? $keterangan->keterangan[searchIndex($keterangan->keterangan, 'disc', 'D')]['keterangan'] : old('d') !!}</textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Keterangan I</label>
              <textarea name="i" class="form-control" placeholder="Tuliskan sesuatu..." required>{!! $keterangan ? $keterangan->keterangan[searchIndex($keterangan->keterangan, 'disc', 'I')]['keterangan'] : old('i') !!}</textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Keterangan S</label>
              <textarea name="s" class="form-control" placeholder="Tuliskan sesuatu..." required>{!! $keterangan ? $keterangan->keterangan[searchIndex($keterangan->keterangan, 'disc', 'S')]['keterangan'] : old('s') !!}</textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Keterangan C</label>
              <textarea name="c" class="form-control" placeholder="Tuliskan sesuatu..." required>{!! $keterangan ? $keterangan->keterangan[searchIndex($keterangan->keterangan, 'disc', 'C')]['keterangan'] : old('c') !!}</textarea>
            </div>
          </div>
        </div>
        <input type="hidden" name="id_tes" value="{{ $keterangan ? $keterangan->id_tes : $id_tes }}">
        <input type="hidden" name="id_paket" value="{{ $keterangan ? $keterangan->id_paket : $id_paket }}">
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="/admin/tes/tipe/{{ $id_tes }}" class="btn btn-secondary">Kembali</a>
      </form>
    </div>
  </div>

@endsection

@section('css-extra')

<!-- Custom styles for this page -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/ui/trumbowyg.min.css">

<!-- CSS -->
<style type="text/css">
  .trumbowyg-box {margin-top: 0; margin-bottom: 0;}
</style>

@endsection

@section('js-extra')

<!-- Page level plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/trumbowyg.min.js"></script>

<!-- JavaScripts -->
<script type="text/javascript">
  $(function(){
    // Trumbowyg
    $('textarea').trumbowyg();

    // Delete
    $(document).on("click", ".delete", function(e){
      e.preventDefault();
      var id = $(this).data("id");
      var ask = confirm("Anda yakin ingin menghapus data ini?");
      if(ask){
        $.ajax({
          type: "post",
          url: "/admin/tes/tipe/{{ $id_tes }}/paket/keterangan/delete",
          data: {_token: "{{ csrf_token() }}", id: id},
          success: function(response){
            if(response == "Berhasil menghapus data!"){
              alert(response);
              window.location.href = "/admin/tes/tipe/{{ $id_tes }}/paket/keterangan/{{ $id_paket }}";
            }
          }
        })
      }
    });
  })
</script>
@endsection