@extends('template/admin/template')

@section('content')

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Tutorial</h1>
  <p class="mb-4">Tutorial atau petunjuk dalam mengerjakan tes.</p>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Tutorial</h6>
      @if(isset($tutorial))
      <a class="btn btn-sm btn-danger delete" href="#" data-id="{{ isset($tutorial) ? $tutorial->id_tutorial : '' }}">
        <i class="fas fa-trash fa-sm fa-fw text-gray-400"></i> Hapus Tutorial
      </a>
      @endif
    </div>
    <div class="card-body">
      <form method="post" action="/admin/tes/tipe/{{ $id_tes }}/paket/tutorial/save">
        {{ csrf_field() }}
        @if(Session::get('message') != null)
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
        <div class="form-group">
          <textarea name="tutorial" class="form-control" placeholder="Tuliskan sesuatu...">{!! isset($tutorial) ? $tutorial->tutorial : old('tutorial') !!}</textarea>
          @if($errors->has('tutorial'))
          <small class="text-danger">{{ ucfirst($errors->first('tutorial')) }}</small>
          @endif
        </div>
        <input type="hidden" name="id_paket" value="{{ isset($tutorial) ? $tutorial->id_paket : $id_paket }}">
        <input type="hidden" name="id_tes" value="{{ isset($tutorial) ? $tutorial->id_tes : $id_tes }}">
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
  .trumbowyg-box {margin-top: 0;}
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
          url: "/admin/tes/tipe/{{ $id_tes }}/paket/tutorial/delete",
          data: {_token: "{{ csrf_token() }}", id: id},
          success: function(response){
            if(response == "Berhasil menghapus data!"){
              alert(response);
              window.location.href = "/admin/tes/tipe/{{ $id_tes }}/paket/tutorial/{{ $id_paket }}";
            }
          }
        })
      }
    });
  })
</script>

@if($errors->has('tutorial'))
<script type="text/javascript">
  $(function(){
    $('.trumbowyg-box').addClass('border-danger');
  })
</script>
@endif

@endsection