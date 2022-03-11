@extends('auth.register-layout')

@section('content')

<div class="text-center">
    <h1 class="h4 text-gray-900 mb-5 text-uppercase">Upload Foto Ijazah</h1>
</div>
<form id="form" method="post" action="/lowongan/{{ $url_form }}/daftar/step-3" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="form-group col-sm-12">
            <label>Foto Ijazah: <span class="text-danger">*</span></label>
            <input type="file" name="file_certificate" id="certificate" class="d-none" accept="image/*">
            <input name="certificate" type="hidden" class="form-control {{ $errors->has('file_certificate') ? 'is-invalid' : '' }}" value="{{ !empty($array) ? $array['certificate'] : old('certificate') }}">
            <button class="btn d-grid w-100 {{ $errors->has('file_certificate') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-upload" data-id="certificate" type="button"><i class="bi bi-upload"></i>Upload Foto Ijazah</button>
            @if($errors->has('file_certificate'))
            <small class="text-danger">{{ ucfirst($errors->first('file_certificate')) }}</small>
            @endif
            <div class="row">
                <div class="col text-center">
                <img name="img_certificate" class="img-thumbnail {{ !empty($array) ? '' : 'd-none' }} mt-3" width="200" src="{{ !empty($array) ? asset('assets/images/foto-ijazah/'.$array['certificate']) : '' }}">
                <input type="hidden" name="src_certificate">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group mt-3">
        <div class="row">
        <div class="col-auto ms-auto">
            <input type="hidden" name="url" value="{{ $url_form }}">
            <a href="/lowongan/{{ $url_form }}/daftar/step-2" class="btn btn-secondary">&laquo; Sebelumnya</a>
            <button type="submit" class="btn btn-primary">Selanjutnya &raquo;</button>
        </div>
        </div>
    </div>
</form>

@endsection

@section('js')

<script type="text/javascript">
    $(document).on("click", ".btn-upload", function(e) {
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
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).on("change", "input[type=file]", function() {
        readURL(this);
    });
</script>

@endsection