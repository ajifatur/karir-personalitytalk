@extends('auth.register-layout')

@section('content')

<div class="text-center">
    <h1 class="h4 text-gray-900 mb-5 text-uppercase">Form Identitas</h1>
</div>
<form id="form" method="post" action="/lowongan/{{ $url_form }}/daftar/step-1">
@csrf
    <div class="row mb-3">
        <div class="form-group col-md-12">
            <label>Nama Lengkap: <span class="text-danger">*</span></label>
            <input name="name" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Masukkan Nama Lengkap" value="{{ !empty($array) ? $array['name'] : old('name') }}">
            @if($errors->has('name'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('name')) }}</div>
            @endif
        </div>
    </div>
    <div class="row mb-3">
        <div class="form-group col-md-6">
            <label>Tempat Lahir: <span class="text-danger">*</span></label>
            <input name="birthplace" type="text" class="form-control {{ $errors->has('birthplace') ? 'is-invalid' : '' }}" placeholder="Masukkan Tempat Lahir" value="{{ !empty($array) ? $array['birthplace'] : old('birthplace') }}">
            @if($errors->has('birthplace'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('birthplace')) }}</div>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label>Tanggal Lahir: <span class="text-danger">*</span></label>
            <div class="input-group">
                <input name="birthdate" type="text" class="form-control {{ $errors->has('birthdate') ? 'is-invalid' : '' }}" placeholder="Masukkan Tanggal Lahir" value="{{ !empty($array) ? $array['birthdate'] : old('birthdate') }}" autocomplete="off">
            </div>
            @if($errors->has('birthdate'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('birthplace')) }}</div>
            @endif
        </div>
    </div>
    <div class="row mb-3">
        <div class="form-group col-md-6">
            <label>Jenis Kelamin: <span class="text-danger">*</span></label>
            <select name="gender" class="form-select {{ $errors->has('gender') ? 'is-invalid' : '' }}">
                <option value="" disabled selected>--Pilih--</option>
                @if(!empty($array))
                    @foreach(gender() as $gender)
                        <option value="{{ $gender['key'] }}" {{ $array['gender'] == $gender['key'] ? 'selected' : '' }}>{{ $gender['name'] }}</option>
                    @endforeach
                @else
                    @foreach(gender() as $gender)
                        <option value="{{ $gender['key'] }}" {{ old('gender') == $gender['key'] ? 'selected' : '' }}>{{ $gender['name'] }}</option>
                    @endforeach
                @endif
            </select>
            @if($errors->has('gender'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('gender')) }}</div>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label>Agama: <span class="text-danger">*</span></label>
            <select name="religion" class="form-select {{ $errors->has('religion') ? 'is-invalid' : '' }}">
                <option value="" disabled selected>--Pilih--</option>
                @if(!empty($array))
                    @foreach(religion() as $religion)
                        <option value="{{ $religion['key'] }}" {{ $array['religion'] == $religion['key'] ? 'selected' : '' }}>{{ $religion['name'] }}</option>
                    @endforeach
                @else
                    @foreach(religion() as $religion)
                        <option value="{{ $religion['key'] }}" {{ old('religion') == $religion['key'] ? 'selected' : '' }}>{{ $religion['name'] }}</option>
                    @endforeach
                @endif
            </select>
            @if($errors->has('religion'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('religion')) }}</div>
            @endif
        </div>
    </div>
    <div class="row mb-3">
        <div class="form-group col-md-6">
            <label>Email: <span class="text-danger">*</span></label>
            <input name="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Masukkan Email" value="{{ !empty($array) ? $array['email'] : old('email') }}">
            @if($errors->has('email'))
            <div class="invalid-feedback">
                {{ ucfirst($errors->first('email')) }}
            </div>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label>No. HP: <span class="text-danger">*</span></label>
            <input name="phone_number" type="text" class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" placeholder="Masukkan Nomor HP" value="{{ !empty($array) ? $array['phone_number'] : old('phone_number') }}">
            @if($errors->has('phone_number'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('phone_number')) }}</div>
            @endif
        </div>
    </div>
    <div class="row mb-3">
        <div class="form-group col-md-6">
            <label>No. KTP:</label>
            <input name="identity_number" type="text" class="form-control {{ $errors->has('identity_number') ? 'is-invalid' : '' }}" placeholder="Masukkan Nomor KTP" value="{{ !empty($array) ? $array['identity_number'] : old('identity_number') }}">
            @if($errors->has('identity_number'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('identity_number')) }}</div>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label>Akun Sosial Media: <span class="text-danger">*</span></label>
            <div class="input-group">
                <select name="platform" class="form-select {{ $errors->has('socmed') ? 'border-danger' : '' }}">
                    @if(!empty($array))
                        @foreach(platform() as $platform)
                            <option value="{{ $platform['key'] }}" {{ $array['platform'] == $platform['key'] ? 'selected' : '' }}>{{ $platform['name'] }}</option>
                        @endforeach
                    @else
                        @foreach(platform() as $platform)
                            <option value="{{ $platform['key'] }}" {{ old('platform') == $platform['key'] ? 'selected' : '' }}>{{ $platform['name'] }}</option>
                        @endforeach
                    @endif
                </select>
                <input name="socmed" type="text" class="form-control w-50 {{ $errors->has('socmed') ? 'is-invalid' : '' }}" placeholder="Masukkan Akun Sosmed" value="{{ !empty($array) ? $array['socmed'] : old('socmed') }}">
            </div>
            @if($errors->has('socmed'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('socmed')) }}</div>
            @endif
        </div>
    </div>
    <div class="row mb-3">
        <div class="form-group col-md-6">
            <label>Alamat: <span class="text-danger">*</span></label>
            <textarea name="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" placeholder="Masukkan Alamat" rows="2">{{ !empty($array) ? $array['address'] : old('address') }}</textarea>
            @if($errors->has('address'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('address')) }}</div>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label>Status Hubungan: <span class="text-danger">*</span></label>
            <select name="relationship" class="form-select {{ $errors->has('relationship') ? 'is-invalid' : '' }}">
                <option value="" disabled selected>--Pilih--</option>
                @if(!empty($array))
                    @foreach(relationship() as $relationship)
                        <option value="{{ $relationship['key'] }}" {{ $array['relationship'] == $relationship['key'] ? 'selected' : '' }}>{{ $relationship['name'] }}</option>
                    @endforeach
                @else
                    @foreach(relationship() as $relationship)
                        <option value="{{ $relationship['key'] }}" {{ old('relationship') == $relationship['key'] ? 'selected' : '' }}>{{ $relationship['name'] }}</option>
                    @endforeach
                @endif
            </select>
            @if($errors->has('relationship'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('relationship')) }}</div>
            @endif
        </div>
    </div>
    <div class="row mb-3">
        <div class="form-group col-md-6">
            <label>Pendidikan Terakhir: <span class="text-danger">*</span></label>
            <textarea name="latest_education" class="form-control {{ $errors->has('latest_education') ? 'is-invalid' : '' }}" placeholder="Masukkan Pendidikan Terakhir" rows="2">{{ !empty($array) ? $array['latest_education'] : old('latest_education') }}</textarea>
            @if($errors->has('latest_education'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('latest_education')) }}</div>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label>Riwayat Pekerjaan:</label>
            <textarea name="job_experience" class="form-control {{ $errors->has('job_experience') ? 'is-invalid' : '' }}" placeholder="Masukkan Riwayat Pekerjaan" rows="2">{{ !empty($array) ? $array['job_experience'] : old('job_experience') }}</textarea>
            <small class="text-muted">Kosongi saja jika Anda belum memiliki riwayat pekerjaan</small>
        </div>
    </div>
    <div class="form-group mt-3">
        <div class="row">
        <div class="col-auto ms-auto">
            <input type="hidden" name="url" value="{{ $url_form }}">
            <button type="submit" class="btn btn-primary rounded">Selanjutnya &raquo;</button>
        </div>
        </div>
    </div>
</form>

@endsection

@section('js')

<script type="text/javascript">
    // Datepicker
    Spandiv.DatePicker("input[name=birthdate]");
</script>

@endsection