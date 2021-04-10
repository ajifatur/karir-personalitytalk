<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/templates/qbs/bootstrap/style.min.css">
  <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/templates/qbs/bootstrap/homev2.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css" integrity="sha256-8g4waLJVanZaKB04tvyhKu2CZges6pA5SUelZAux/1U=" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/assets/css/login.css">
  <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />
  <title>Registrasi</title>
  <style type="text/css">
    .card {width: 700px; border-radius: 0;}
    .card-header span {display: inline-block; height: 12px; width: 12px; margin: 0px 5px; border-radius: 50%; background: rgba(0,0,0,.2);}
    .card-header span.active {background: #007bff!important;}
    .input-group-text {width: 40px;}
    .preloader {display: none; position: fixed; height: 100%; width: 100%; top: 0; right: 0; left: 0; bottom: 0; z-index: 9999; background: rgba(0,0,0,.55);}
    .preloader-animation {background-position: center; background-repeat: no-repeat; height: 100%;}
</style>
</head>
<body>
  <div id="sidebar-main"></div>
  <div id="navbar-main"></div>
  <div class="preloader">
      <div class="preloader-animation" style="background-image: url({{ asset('assets/loader/preloader.gif') }});"></div>
  </div>
  <div class="wrapper py-lg-5 py-md-3 pt-1">
    <div class="d-flex justify-content-center h-100">
      <div class="card border-0 shadow-sm" style="border-radius: .5em">
        <div class="card-header bg-transparent text-center">
          <span data-step="1" class="{{ $step == 1 ? 'active' : '' }}"></span>
          <span data-step="2" class="{{ $step == 2 ? 'active' : '' }}"></span>
          <span data-step="3" class="{{ $step == 3 ? 'active' : '' }}"></span>
          <span data-step="4" class="{{ $step == 4 ? 'active' : '' }}"></span> 
          <span data-step="5" class="{{ $step == 5 ? 'active' : '' }}"></span> 
        </div>
        <div class="card-body">
          <div class="text-center">
            <h1 class="h4 text-gray-900 mb-5 text-uppercase">Upload Pas Foto</h1>
          </div>
          <!-- <form id="form" method="post" action="/applicant/register/step-2" enctype="multipart/form-data"> -->
          <form id="form" method="post" action="/lowongan/{{ $url_form }}/daftar/step-2" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-row">
              <div class="form-group col-sm-12">
                <label>Pas Foto: <span class="text-danger">*</span></label>
                <input type="file" name="file_pas_foto" id="pas_foto" class="d-none" accept="image/*">
                <input name="pas_foto" type="hidden" class="form-control form-control-sm {{ $errors->has('file_pas_foto') ? 'is-invalid' : '' }}" value="{{ !empty($array) ? $array['pas_foto'] : old('pas_foto') }}">
                <button class="btn btn-sm btn-block rounded {{ $errors->has('file_pas_foto') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-upload" data-id="pas_foto" type="button"><i class="fa fa-upload"></i> Upload Pas Foto</button>
                @if($errors->has('file_pas_foto'))
                <small class="text-danger">
                  {{ ucfirst($errors->first('file_pas_foto')) }}
                </small>
                @endif
                <div class="row">
                  <div class="col text-center">
                    <img name="img_pas_foto" class="img-thumbnail {{ !empty($array) ? '' : 'd-none' }} mt-3" width="200" src="{{ !empty($array) ? asset('assets/images/pas-foto/'.$array['pas_foto']) : '' }}">
                    <input type="hidden" name="src_pas_foto">
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group mt-3">
              <div class="row">
                <div class="col-auto ml-auto">
                  <input type="hidden" name="url" value="{{ $url_form }}">
                  <!-- <a href="/applicant/register/step-1" class="btn btn-sm btn-danger">&laquo; Sebelumnya</a> -->
                  <a href="/lowongan/{{ $url_form }}/daftar/step-1" class="btn btn-sm btn-danger rounded">&laquo; Sebelumnya</a>
                  <button type="submit" class="btn btn-sm btn-primary rounded">Selanjutnya &raquo;</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div id="footer-main"></div>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://psikologanda.com/assets/partials/template.js"></script>
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
  <script type="text/javascript">
    $(document).on('click','#sidebarCollapse',function(e){
      e.preventDefault();
      $('#sidebar').hasClass('active') 
        ? $('#sidebar').removeClass('active') 
        : $('#sidebar').addClass('active');
      $(this).find('i').hasClass('ti-menu') 
        ? $(this).find('i').removeClass('ti-menu').addClass('ti-close') 
        : $(this).find('i').addClass('ti-menu').removeClass('ti-close');
    })

    $(document).on("click", "#sidebar > .sidebar-menu > .menu-label.sidebar-dropdown > a", function(e){
      e.preventDefault();
      $(this).parent(".menu-label").hasClass("active") 
        ? $(this).parent(".menu-label").removeClass("active") 
        : $(this).parent(".menu-label").addClass("active");
    })
  </script>
</body>
</html>