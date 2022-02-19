@extends('layouts/admin/main')

@section('title', 'Edit Password')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit Password</h1>
</div>
<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ Auth::user()->avatar != '' ? asset('assets/images/users/'.Auth::user()->avatar) : asset('assets/images/default/user.png') }}" class="rounded-circle" height="150" width="150" alt="Foto">
            </div>
            <hr class="my-0">
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('admin.profile.detail') }}" class="list-group-item list-group-item-action py-2 px-3 {{ is_int(strpos(Request::url(), route('admin.profile.detail'))) && !is_int(strpos(Request::url(), route('admin.profile.edit'))) ? 'active' : '' }}">Profil</a>
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

@endsection

@section('js')

<script type="text/javascript">
    // Datepicker
    Spandiv.DatePicker("input[name=birthdate]");
</script>

@endsection