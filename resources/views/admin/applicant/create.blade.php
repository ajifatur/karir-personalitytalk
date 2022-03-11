@extends('layouts/admin/main')

@section('title', 'Tambah Pelamar')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Tambah Pelamar</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.applicant.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if(Auth::user()->role->is_global === 1)
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Perusahaan <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="company" class="form-select form-select-sm {{ $errors->has('company') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('company'))
                            <div class="small text-danger">{{ $errors->first('company') }}</div>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Lowongan yang Dilamar <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="vacancy" class="form-select form-select-sm {{ $errors->has('vacancy') ? 'border-danger' : '' }}" {{ Auth::user()->role->is_global === 1 ? 'disabled' : '' }}>
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($vacancies as $vacancy)
                                    <option value="{{ $vacancy->id }}">{{ $vacancy->name }}, sebagai {{ $vacancy->position->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('vacancy'))
                            <div class="small text-danger">{{ $errors->first('vacancy') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="name" class="form-control form-control-sm {{ $errors->has('name') ? 'border-danger' : '' }}" value="{{ old('name') }}" autofocus>
                            @if($errors->has('name'))
                            <div class="small text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tempat Lahir</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="birthplace" class="form-control form-control-sm {{ $errors->has('birthplace') ? 'border-danger' : '' }}" value="{{ old('birthplace') }}">
                            @if($errors->has('birthplace'))
                            <div class="small text-danger">{{ $errors->first('birthplace') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group input-group-sm">
                                <input type="text" name="birthdate" class="form-control form-control-sm {{ $errors->has('birthdate') ? 'border-danger' : '' }}" value="{{ old('birthdate') }}" autocomplete="off">
                                <span class="input-group-text {{ $errors->has('birthdate') ? 'border-danger' : '' }}"><i class="bi-calendar2"></i></span>
                            </div>
                            @if($errors->has('birthdate'))
                            <div class="small text-danger">{{ $errors->first('birthdate') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            @foreach(gender() as $gender)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="gender-{{ $gender['key'] }}" value="{{ $gender['key'] }}" {{ old('gender') == $gender['key'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender-{{ $gender['key'] }}">
                                    {{ $gender['name'] }}
                                </label>
                            </div>
                            @endforeach
                            @if($errors->has('gender'))
                            <div class="small text-danger">{{ $errors->first('gender') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Agama <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="religion" class="form-select form-select-sm {{ $errors->has('religion') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach(religion() as $religion)
                                <option value="{{ $religion['key'] }}" {{ old('religion') == $religion['key'] ? 'selected' : '' }}>{{ $religion['name'] }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('religion'))
                            <div class="small text-danger">{{ $errors->first('religion') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="email" name="email" class="form-control form-control-sm {{ $errors->has('email') ? 'border-danger' : '' }}" value="{{ old('email') }}">
                            @if($errors->has('email'))
                            <div class="small text-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">No. HP <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="phone_number" class="form-control form-control-sm {{ $errors->has('phone_number') ? 'border-danger' : '' }}" value="{{ old('phone_number') }}">
                            @if($errors->has('phone_number'))
                            <div class="small text-danger">{{ $errors->first('phone_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">NIK</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="identity_number" class="form-control form-control-sm {{ $errors->has('identity_number') ? 'border-danger' : '' }}" value="{{ old('identity_number') }}">
                            @if($errors->has('identity_number'))
                            <div class="small text-danger">{{ $errors->first('identity_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Alamat <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <textarea name="address" class="form-control form-control-sm {{ $errors->has('address') ? 'border-danger' : '' }}" rows="3">{{ old('address') }}</textarea>
                            @if($errors->has('address'))
                            <div class="small text-danger">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Pendidikan Terakhir</label>
                        <div class="col-lg-10 col-md-9">
                            <textarea name="latest_education" class="form-control form-control-sm {{ $errors->has('latest_education') ? 'border-danger' : '' }}" rows="3">{{ old('latest_education') }}</textarea>
                            @if($errors->has('latest_education'))
                            <div class="small text-danger">{{ $errors->first('latest_education') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Riwayat Pekerjaan</label>
                        <div class="col-lg-10 col-md-9">
                            <textarea name="job_experience" class="form-control form-control-sm {{ $errors->has('job_experience') ? 'border-danger' : '' }}" rows="3">{{ old('job_experience') }}</textarea>
                            @if($errors->has('job_experience'))
                            <div class="small text-danger">{{ $errors->first('job_experience') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Status Hubungan <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="relationship" class="form-select form-select-sm {{ $errors->has('relationship') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach(relationship() as $relationship)
                                <option value="{{ $relationship['key'] }}" {{ old('relationship') == $relationship['key'] ? 'selected' : '' }}>{{ $relationship['name'] }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('relationship'))
                            <div class="small text-danger">{{ $errors->first('relationship') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('admin.applicant.index') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
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

    // Change company
    $(document).on("change", "select[name=company]", function() {
        var company = $(this).val();
        $.ajax({
            type: "get",
            url: "{{ route('admin.vacancy.index') }}",
            data: {company: company},
            success: function(response) {
                var html = '';
                html += '<option value="" disabled selected>--Pilih--</option>';
                for(i=0; i<response.length; i++) {
                    html += '<option value="' + response[i].id + '">' + response[i].name + '</option>';
                }
                $("select[name=vacancy]").removeAttr("disabled").html(html);
            }
        });
    });
</script>

@endsection