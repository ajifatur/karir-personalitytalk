<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <title>Registrasi</title>
  <style type="text/css">
    body {height: calc(100vh); background-repeat: no-repeat; background-size: cover; background-position: center;}
    .wrapper {background: rgba(0,0,0,.6);}
    .card {width: 700px; border-radius: 0;}
    .card-header span {display: inline-block; height: 12px; width: 12px; margin: 0px 5px; border-radius: 50%; background: rgba(0,0,0,.2);}
    .card-header span.active {background: #007bff!important;}
    .input-group-text {width: 40px;}
    .preloader {display: none; position: fixed; height: 100%; width: 100%; top: 0; right: 0; left: 0; bottom: 0; z-index: 9999; background: rgba(0,0,0,.55);}
    .preloader-animation {background-position: center; background-repeat: no-repeat; height: 100%;}
</style>
</head>
<body class="small" background="{{ asset('assets/images/background/applicant.jpg') }}">
  <div class="preloader">
      <div class="preloader-animation" style="background-image: url({{ asset('assets/loader/preloader.gif') }});"></div>
  </div>
  <div class="wrapper h-100">
    <div class="d-flex justify-content-center h-100">
      <div class="card my-auto">
        <div class="card-header text-center">
          <span data-step="1" class="{{ $step == 1 ? 'active' : '' }}"></span>
          <span data-step="2" class="{{ $step == 2 ? 'active' : '' }}"></span>
          <span data-step="3" class="{{ $step == 3 ? 'active' : '' }}"></span>
          <span data-step="4" class="{{ $step == 4 ? 'active' : '' }}"></span> 
          <span data-step="5" class="{{ $step == 5 ? 'active' : '' }}"></span> 
        </div>
        <div class="card-body">
          <div class="text-center">
            <h1 class="h4 text-gray-900 mb-5 text-uppercase">Upload Foto Ijazah</h1>
          </div>
          <!-- <form id="form" method="post" action="/applicant/register/step-3" enctype="multipart/form-data"> -->
          <form id="form" method="post" action="/lowongan/{{ $url_form }}/daftar/step-3" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-row">
              <div class="form-group col-sm-12">
                <label>Foto Ijazah: <span class="text-danger">*</span></label>
                <input type="file" name="file_foto_ijazah" id="foto_ijazah" class="d-none" accept="image/*">
                <input name="foto_ijazah" type="hidden" class="form-control form-control-sm {{ $errors->has('file_foto_ijazah') ? 'is-invalid' : '' }}" value="{{ !empty($array) ? $array['foto_ijazah'] : old('foto_ijazah') }}">
                <button class="btn btn-sm btn-block {{ $errors->has('file_foto_ijazah') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-upload" data-id="foto_ijazah" type="button"><i class="fa fa-upload"></i> Upload Foto Ijazah</button>
                @if($errors->has('file_foto_ijazah'))
                <small class="text-danger">
                  {{ ucfirst($errors->first('file_foto_ijazah')) }}
                </small>
                @endif
                <div class="row">
                  <div class="col text-center">
                    <img name="img_foto_ijazah" class="img-thumbnail {{ !empty($array) ? '' : 'd-none' }} mt-3" width="200" src="{{ !empty($array) ? asset('assets/images/foto-ijazah/'.$array['foto_ijazah']) : '' }}">
                    <input type="hidden" name="src_foto_ijazah">
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group mt-3">
              <div class="row">
                <div class="col-auto ml-auto">
                  <input type="hidden" name="url" value="{{ $url_form }}">
                  <!-- <a href="/applicant/register/step-2" class="btn btn-sm btn-danger">&laquo; Sebelumnya</a> -->
                  <a href="/lowongan/{{ $url_form }}/daftar/step-2" class="btn btn-sm btn-danger">&laquo; Sebelumnya</a>
                  <button type="submit" class="btn btn-sm btn-primary">Selanjutnya &raquo;</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script type="text/javascript">
    $(document).on("click", ".btn-upload", function(e){
      e.preventDefault();
      var id = $(this).data("id");
      $("#" + id).trigger("click");
    });

    function readURL(input) {
      if(input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          var id = $(input).attr("id");
          var today = new Date();
          $("input[name=" + id + "]").val(today.getTime() + ".jpg");
          $("img[name=img_" + id + "]").attr('src', e.target.result).removeClass("d-none");
          // $("input[name=src_" + id + "]").val(e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    $(document).on("change", "input[type=file]", function(){
      readURL(this);
    });
      
    // Show loader on submit
    $(document).on("submit", "#form", function(e){
        e.preventDefault();
        $(".preloader").show();
        $("#form")[0].submit();
    });
  </script>
</body>
</html>