@extends('layouts/admin/main')

@section('title', 'Edit Password')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit Password</h1>
</div>
<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center text-center">
                    @if(Auth::user()->avatar != '' && File::exists(public_path('assets/images/users/'.Auth::user()->avatar)))
                        <div class="btn-avatar rounded-circle me-2 text-center">
                            <div class="avatar-overlay"><i class="bi-camera"></i></div>
                            <img src="{{ asset('assets/images/users/'.Auth::user()->avatar) }}" class="rounded-circle" height="150" width="150" alt="Foto">
                        </div>
                    @else
                        <div class="btn-avatar rounded-circle me-2 text-center bg-dark">
                            <div class="avatar-overlay"><i class="bi-camera"></i></div>
                            <h2 class="text-white" style="line-height: 150px; font-size: 75px;">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</h2>
                        </div>
                    @endif
                </div>
            </div>
            <hr class="my-0">
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('admin.profile') }}" class="list-group-item list-group-item-action py-2 px-3 {{ is_int(strpos(Request::url(), route('admin.profile'))) && !is_int(strpos(Request::url(), route('admin.profile.edit'))) ? 'active' : '' }}">Profil</a>
                    <a href="{{ route('admin.profile.edit') }}" class="list-group-item list-group-item-action py-2 px-3 {{ is_int(strpos(Request::url(), route('admin.profile.edit'))) && !is_int(strpos(Request::url(), route('admin.profile.edit-password'))) ? 'active' : '' }}">Edit Profil</a>
                    <a href="{{ route('admin.profile.edit-password') }}" class="list-group-item list-group-item-action py-2 px-3 {{ is_int(strpos(Request::url(), route('admin.profile.edit-password'))) ? 'active' : '' }}">Edit Password</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-xl-9">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Edit Password</h5></div>
            <div class="card-body">
                @if(Session::get('message'))
                <div class="alert {{ Session::get('status') == 1 ? 'alert-success' : 'alert-danger' }} alert-dismissible fade show" role="alert">
                    <div class="alert-message">{{ Session::get('message') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <form method="post" action="{{ route('admin.profile.update-password') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Password Lama <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group">
                                <input type="password" name="old_password" class="form-control form-control-sm {{ $errors->has('old_password') ? 'border-danger' : '' }}">
                                <button type="button" class="btn btn-sm {{ $errors->has('old_password') ? 'btn-outline-danger' : 'btn-outline-secondary' }} btn-toggle-password"><i class="bi-eye"></i></button>
                            </div>
                            @if($errors->has('old_password'))
                            <div class="small text-danger">{{ $errors->first('old_password') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Password Baru <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group">
                                <input type="password" name="new_password" class="form-control form-control-sm {{ $errors->has('new_password') ? 'border-danger' : '' }}">
                                <button type="button" class="btn btn-sm {{ $errors->has('new_password') ? 'btn-outline-danger' : 'btn-outline-secondary' }} btn-toggle-password"><i class="bi-eye"></i></button>
                            </div>
                            @if($errors->has('new_password'))
                            <div class="small text-danger">{{ $errors->first('new_password') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group">
                                <input type="password" name="confirm_password" class="form-control form-control-sm {{ $errors->has('confirm_password') ? 'border-danger' : '' }}">
                                <button type="button" class="btn btn-sm {{ $errors->has('confirm_password') ? 'btn-outline-danger' : 'btn-outline-secondary' }} btn-toggle-password"><i class="bi-eye"></i></button>
                            </div>
                            @if($errors->has('confirm_password'))
                            <div class="small text-danger">{{ $errors->first('confirm_password') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-image" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Unggah / Pilih Foto</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="tab-user-avatar" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="upload-image-tab" data-bs-toggle="tab" data-bs-target="#upload-image" type="button" role="tab" aria-controls="upload-image" aria-selected="true">Unggah</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="choose-image-tab" data-bs-toggle="tab" data-bs-target="#choose-image" type="button" role="tab" aria-controls="choose-image" aria-selected="false">Pilih</button>
                    </li>
                </ul>
                <div class="tab-content my-3">
                    <div class="tab-pane fade show active" id="upload-image" role="tabpanel" aria-labelledby="upload-image-tab">
                        <p class="text-center">Ukuran 250 x 250 pixel.</p>
                        <div class="dropzone">
                            <div class="dropzone-description">
                                <i class="dropzone-icon bi-upload"></i>
                                <p>Pilih file gambar atau geser ke sini.</p>
                            </div>
                            <input type="file" name="file" class="dropzone-input croppie-input" accept="image/*">
                        </div>
                    </div>
                    <div class="tab-pane fade" id="choose-image" role="tabpanel" aria-labelledby="choose-image-tab">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-croppie" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sesuaikan Foto</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center">Ukuran 250 x 250 pixel.</p>
                <div class="table-responsive">
                    <div id="croppie"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary btn-croppie">Potong</button>
                <button class="btn btn-sm btn-danger" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<form class="form-upload-image" method="post" action="{{ route('admin.settings.avatar.update') }}">
    @csrf
    <input type="hidden" name="image">
</form>

<form class="form-choose-image" method="post" action="{{ route('admin.settings.avatar.update') }}">
    @csrf
    <input type="hidden" name="image">
    <input type="hidden" name="choose" value="1">
</form>

@endsection

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script>
    // Init Croppie
    var croppie = Spandiv.Croppie("#croppie", {
        width: 250,
        height: 250,
        type: 'circle'
    });

    // Button Avatar
    $(document).on("click", ".btn-avatar", function() {
        Spandiv.Tab("#tab-user-avatar [data-bs-target='#upload-image']").show();
        Spandiv.Modal("#modal-image").show();
    });

    // Change Croppie Input
    $(document).on("change", ".croppie-input", function() {
        Spandiv.CroppieBindFromURL(croppie, this);
        Spandiv.Modal("#modal-image").hide();
        Spandiv.Modal("#modal-croppie").show();
    });

    // Button Croppie
    $(document).on("click", ".btn-croppie", function(e) {
        e.preventDefault();
        Spandiv.CroppieSubmit(croppie, ".form-upload-image");
    });

    // Button Choose Image
    $(document).on("click", ".btn-choose-image", function(e) {
        e.preventDefault();
        var image = $(this).data("image");
        $(".form-choose-image").find("input[name=image]").val(image);
        Spandiv.SwalWarning("Anda yakin ingin mengganti dengan foto ini?", ".form-choose-image");
    });

    // Event Listener on Choose Image Tab
    document.querySelector("[data-bs-toggle='tab'][data-bs-target='#choose-image']").addEventListener("shown.bs.tab", function(e) {
        if($("#choose-image").find(".row").length === 0) {
            $.ajax({
                type: "get",
                url: "{{ route('admin.settings.avatar') }}",
                success: function(response) {
                    var html = '';
                    html += '<div class="row">';
                    for(var i=0; i<response.length; i++) {
                        html += '<div class="col-auto text-center">';
                        html += '<img src="{{ asset("assets/images/users") }}/' + response[i] + '" class="img-thumbnail rounded-circle btn-choose-image" data-image="' + response[i] + '" data-bs-toggle="tooltip" title="Pilih Foto" width="150" style="cursor: pointer;">';
                        html += '</div>';
                    }
                    html += '</div>';
                    $("#choose-image").html(html);
                    Spandiv.Tooltip();
                }
            });
        }
    });
</script>

@endsection

@section('css')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">

@endsection