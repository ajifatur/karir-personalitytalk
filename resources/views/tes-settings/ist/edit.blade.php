@extends('template/admin/main')

@section('content')

    <!-- Page Heading -->
    <div class="page-heading shadow d-flex justify-content-between align-items-center">
        <h1 class="h3 text-gray-800">Edit Pengaturan Tes</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
            <li class="breadcrumb-item"><a href="#">Pengaturan Tes</a></li>
            <li class="breadcrumb-item"><a href="#">IST</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </div>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="post" action="/admin/tes/settings/{{ $tes->path }}/{{ $paket->id_paket }}/update" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id_paket" value="{{ $paket->id_paket }}">
                <input type="hidden" name="id_tes" value="{{ $tes->id_tes }}">
                <div class="form-group row">
                    <label class="col-lg-2 col-md-3 col-form-label">Waktu Pengerjaan (detik): <span class="text-danger">*</span></label>
                    <div class="col-lg-10 col-md-9">
                        <input name="waktu_pengerjaan" type="number" class="form-control {{ $errors->has('waktu_pengerjaan') ? 'is-invalid' : '' }}" value="{{ tes_settings($paket->id_paket, 'waktu_pengerjaan') }}">
                        @if($errors->has('waktu_pengerjaan'))
                        <div class="invalid-feedback">
                            {{ ucfirst($errors->first('waktu_pengerjaan')) }}
                        </div>
                        @endif
                    </div>
                </div>
                @if($paket->tipe_soal === 'choice-memo')
                <div class="form-group row">
                    <label class="col-lg-2 col-md-3 col-form-label">Waktu Hafalan (detik): <span class="text-danger">*</span></label>
                    <div class="col-lg-10 col-md-9">
                        <input name="waktu_hafalan" type="number" class="form-control {{ $errors->has('waktu_hafalan') ? 'is-invalid' : '' }}" value="{{ tes_settings($paket->id_paket, 'waktu_hafalan') }}">
                        @if($errors->has('waktu_hafalan'))
                        <div class="invalid-feedback">
                            {{ ucfirst($errors->first('waktu_hafalan')) }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                <div class="form-group row">
                    <label class="col-lg-2 col-md-3 col-form-label">Perlu Autentikasi: <span class="text-danger">*</span></label>
                    <div class="col-lg-10 col-md-9">
                        <select name="is_auth" class="form-control {{ $errors->has('is_auth') ? 'is-invalid' : '' }} custom-select">
                            <option value="" disabled selected>--Pilih--</option>
                            <option value="1" {{ tes_settings($paket->id_paket, 'is_auth') == 1 ? 'selected' : '' }}>Ya</option>
                            <option value="0" {{ tes_settings($paket->id_paket, 'is_auth') == 0 ? 'selected' : '' }}>Tidak</option>
                        </select>
                        @if($errors->has('is_auth'))
                        <small class="text-danger">{{ ucfirst($errors->first('is_auth')) }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ tes_settings($paket->id_paket, 'is_auth') == 1 ? '' : 'd-none' }}" id="access-token">
                    <label class="col-lg-2 col-md-3 col-form-label">Kode Autentikasi: <span class="text-danger">*</span></label>
                    <div class="col-lg-10 col-md-9">
                        <input name="access_token" type="text" class="form-control {{ $errors->has('access_token') ? 'is-invalid' : '' }}" value="{{ tes_settings($paket->id_paket, 'access_token') }}" readonly>
                        <div class="mt-1"><a id="generate-token" href="#">Generate Kode</a></div>
                        @if($errors->has('access_token'))
                        <div class="invalid-feedback">
                            {{ ucfirst($errors->first('access_token')) }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-2 col-md-3"></div>
                    <div class="col-lg-10 col-md-9">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
  
@endsection

@section('js-extra')

<!-- JavaScripts -->
<script type="text/javascript">
    // Select is auth
    $(document).on("change", "select[name=is_auth]", function() {
        var value = $(this).val();
        if(value === "1") $("#access-token").removeClass("d-none");
        else $("#access-token").addClass("d-none");
    });

    // Button generate token
    $(document).on("click", "#generate-token", function(e) {
        e.preventDefault();
        $("input[name=access_token]").val(generate_token(8));
    });

    function generate_token(length) {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }
</script>

@endsection