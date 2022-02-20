<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Registrasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="https://www.psikologanda.com/assets/css/style.css">
    <style type="text/css">
        .card-header span {display: inline-block; height: 12px; width: 12px; margin: 0px 5px; border-radius: 50%; background: rgba(0,0,0,.2);}
        .card-header span.active {background: var(--primary)!important;}
        .wrapper{min-height: calc(100vh - 19rem)}
        .top.sticky-top {top: 80px;}
    </style>
</head>
<body>
    <div id="sidebar-main"></div>
    <div id="navbar-main"></div>
    <div class="wrapper container py-lg-5 py-md-3 pt-1">
        <div class="row justify-content-center h-100">
            <div class="col-lg-8 order-2 order-lg-1">
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
                    <form id="form" method="post" action="/lowongan/{{ $url_form }}/daftar/step-2" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-sm-12">
                        <label>Pas Foto: <span class="text-danger">*</span></label>
                        <input type="file" name="file_pas_foto" id="pas_foto" class="d-none" accept="image/*">
                        <input name="pas_foto" type="hidden" class="form-control {{ $errors->has('file_pas_foto') ? 'is-invalid' : '' }}" value="{{ !empty($array) ? $array['pas_foto'] : old('pas_foto') }}">
                        <button class="btn d-grid w-100 {{ $errors->has('file_pas_foto') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-upload" data-id="pas_foto" type="button"><i class="bi bi-upload"></i>Upload Pas Foto</button>
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
                        <div class="col-auto ms-auto">
                            <input type="hidden" name="url" value="{{ $url_form }}">
                            <a href="/lowongan/{{ $url_form }}/daftar/step-1" class="btn btn-secondary">&laquo; Sebelumnya</a>
                            <button type="submit" class="btn btn-primary">Selanjutnya &raquo;</button>
                        </div>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
            </div>
            <div class="col-lg-4 order-1 order-lg-2">
                <div class="card rounded-2 border-0 shadow-sm mb-3 mb-lg-0 sticky-top top">
                    <img class="card-img-top" data-pt="image" src="">
                    <div class="card-body">		          	
                        <h5 class="mb-3" data-pt="title"></h5>
                        <p class="mb-1" data-pt="status"></p>
                        <p class="m-0">Oleh : <span data-pt="author"></span> <br> Terakhir diperbarui pada <span data-pt="date"></span></p>
                    </div>
		        </div>
            </div>
        </div>
    </div>
    <div id="footer-main"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
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
    <script>
        $.ajax({
            type: "get",
            url: "https://karir.psikologanda.com/api/vacancy/{{ $url_form }}",
            success: function(response) {
                $("[data-pt=title]").text(response.title);
                $("[data-pt=image]").attr("src",response.image);
                $("[data-pt=url]").attr("href","https://karir.psikologanda.com/lowongan/" + response.url);
                $("[data-pt=author]").text(response.author);
                $("[data-pt=date]").text(response.date);
			    $("[data-pt=status]").html(response.status == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>');
            }
        });
    </script>
</body>
</html>