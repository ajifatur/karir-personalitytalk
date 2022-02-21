<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Registrasi Pekerjaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="https://psikologanda.com/assets/css/style.css">
	<link rel="shortcut icon" href="https://psikologanda.com/assets/images/logo/1598935068-icon.png" type="image/x-icon">
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
                    <div class="card-body">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-5 text-uppercase">Formulir Registrasi</h1>
                        </div>
                        <form method="post" action="{{ route('auth.register') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="url" value="{{ $url_form }}">
                            <p class="small mb-3"><span class="text-danger">*</span>) Wajib diisi</p>
                            <p class="h5 mb-3">Identitas Diri</p>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input name="name" type="text" class="form-control form-control-sm {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" autofocus>
                                    @if($errors->has('name'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('name')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <div class="input-group input-group-sm">
                                        <input name="birthdate" type="text" class="form-control form-control-sm {{ $errors->has('birthdate') ? 'is-invalid' : '' }}" value="{{ old('birthdate') }}" autocomplete="off">
                                        <span class="input-group-text"><i class="bi-calendar2"></i></span>
                                    </div>
                                    @if($errors->has('birthdate'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('birthdate')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input name="birthplace" type="text" class="form-control form-control-sm {{ $errors->has('birthplace') ? 'is-invalid' : '' }}" value="{{ old('birthplace') }}">
                                    @if($errors->has('birthplace'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('birthplace')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    @foreach(gender() as $gender)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="gender-{{ $gender['key'] }}" value="{{ $gender['key'] }}" {{ $gender['key'] == old('gender') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gender-{{ $gender['key'] }}">{{ $gender['name'] }}</label>
                                    </div>
                                    @endforeach
                                    @if($errors->has('gender'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('gender')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Agama <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <select name="religion" class="form-select form-select-sm {{ $errors->has('religion') ? 'is-invalid' : '' }}">
                                        <option value="" disabled selected>--Pilih--</option>
                                        @foreach(religion() as $religion)
                                        <option value="{{ $religion['key'] }}" {{ $religion['key'] == old('religion') ? 'selected' : '' }}>{{ $religion['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('religion'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('religion')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input name="email" type="email" class="form-control form-control-sm {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}">
                                    @if($errors->has('email'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('email')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Nomor HP <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input name="phone_number" type="text" class="form-control form-control-sm {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" value="{{ old('phone_number') }}">
                                    @if($errors->has('phone_number'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('phone_number')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Nomor KTP</label>
                                <div class="col-md-9">
                                    <input name="identity_number" type="text" class="form-control form-control-sm {{ $errors->has('identity_number') ? 'is-invalid' : '' }}" value="{{ old('identity_number') }}">
                                    @if($errors->has('identity_number'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('identity_number')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Akun Sosial Media <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select name="platform" class="form-select form-select-sm {{ $errors->has('platform') ? 'is-invalid' : '' }}">
                                            <option value="" disabled selected>--Pilih--</option>
                                            <option value="Facebook" {{ old('platform') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                                            <option value="Instagram" {{ old('platform') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                                            <option value="YouTube" {{ old('platform') == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                                            <option value="LinkedIn" {{ old('platform') == 'LinkedIn' ? 'selected' : '' }}>LinkedIn</option>
                                        </select>
                                        <input name="socmed" type="text" class="form-control form-control-sm w-50 {{ $errors->has('socmed') ? 'is-invalid' : '' }}" value="{{ old('socmed') }}">
                                    </div>
                                    @if($errors->has('socmed'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('socmed')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Alamat <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <textarea name="address" class="form-control form-control-sm {{ $errors->has('address') ? 'is-invalid' : '' }}" rows="2">{{ old('address') }}</textarea>
                                    @if($errors->has('address'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('address')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <textarea name="latest_education" class="form-control form-control-sm {{ $errors->has('latest_education') ? 'is-invalid' : '' }}" rows="2">{{ old('latest_education') }}</textarea>
                                    @if($errors->has('latest_education'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('latest_education')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Riwayat Pekerjaan</label>
                                <div class="col-md-9">
                                    <textarea name="job_experience" class="form-control form-control-sm {{ $errors->has('job_experience') ? 'is-invalid' : '' }}" rows="2">{{ old('job_experience') }}</textarea>
                                    @if($errors->has('job_experience'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('job_experience')) }}</div>
                                    @endif
                                    <div class="small text-muted">Kosongi saja jika Anda belum memiliki riwayat pekerjaan.</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Status Hubungan <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    @foreach(relationship() as $relationship)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="relationship" id="relationship-{{ $relationship['key'] }}" value="{{ $relationship['key'] }}" {{ $relationship['key'] == old('relationship') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="relationship-{{ $relationship['key'] }}">{{ $relationship['name'] }}</label>
                                    </div>
                                    @endforeach
                                    @if($errors->has('relationship'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('relationship')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <p class="h5 mb-3">Pas Foto dan Ijazah</p>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Pas Foto <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="file" name="photo" accept="image/*">
                                    <br>
                                    <img class="mt-3 d-none" height="150" data-name="photo">
                                    @if($errors->has('photo'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('photo')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Ijazah <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="file" name="certificate" accept="image/*">
                                    <br>
                                    <img class="mt-3 d-none" height="150" data-name="certificate">
                                    @if($errors->has('certificate'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('certificate')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <p class="h5 mb-3">Data Darurat</p>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Nama Orang Tua / Wali <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input name="guardian_name" type="text" class="form-control form-control-sm {{ $errors->has('guardian_name') ? 'is-invalid' : '' }}" value="{{ old('guardian_name') }}">
                                    @if($errors->has('guardian_name'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('guardian_name')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Nomor HP Orang Tua / Wali <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input name="guardian_phone_number" type="text" class="form-control form-control-sm {{ $errors->has('guardian_phone_number') ? 'is-invalid' : '' }}" value="{{ old('guardian_phone_number') }}">
                                    @if($errors->has('guardian_phone_number'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('guardian_phone_number')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Alamat Orang Tua / Wali <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <textarea name="guardian_address" class="form-control form-control-sm {{ $errors->has('guardian_address') ? 'is-invalid' : '' }}" rows="2">{{ old('guardian_address') }}</textarea>
                                    @if($errors->has('guardian_address'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('guardian_address')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Pekerjaan Orang Tua / Wali <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input name="guardian_occupation" type="text" class="form-control form-control-sm {{ $errors->has('guardian_occupation') ? 'is-invalid' : '' }}" value="{{ old('guardian_occupation') }}">
                                    @if($errors->has('guardian_occupation'))
                                    <div class="invalid-feedback">{{ ucfirst($errors->first('guardian_occupation')) }}</div>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <p class="h5 mb-3">Data Keahlian</p>
                            <div class="mb-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="30">No.</th>
                                            <th>Jenis</th>
                                            <th colspan="3">Keahlian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($skills as $key=>$skill)
                                        <tr>
                                            <td align="center">{{ $key+1 }}</td>
                                            <td>{{ $skill }}<input type="hidden" name="skills[{{ $key }}][type]" value="{{ $skill }}"></td>
                                            <td align="center" width="100">
                                                <div class="form-check">
                                                    <input type="radio" id="skill-{{ $key }}-3" name="skills[{{ $key }}][score]" value="3" class="form-check-input" {{ old('skills.*.score.'.$key) == '3' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="skill-{{ $key }}-3">Baik</label>
                                                </div>
                                            </td>
                                            <td align="center" width="100">
                                                <div class="form-check">
                                                    <input type="radio" id="skill-{{ $key }}-2" name="skills[{{ $key }}][score]" value="2" class="form-check-input" {{ old('skills.*.score.'.$key) == '2' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="skill-{{ $key }}-2">Cukup</label>
                                                </div>
                                            </td>
                                            <td align="center" width="100">
                                                <div class="form-check">
                                                    <input type="radio" id="skill-{{ $key }}-1" name="skills[{{ $key }}][score]" value="1" class="form-check-input" {{ old('skills.*.score.'.$key) == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="skill-{{ $key }}-1">Kurang</label>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ var_dump(old('gender'))}}
                            <hr>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary rounded">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 order-1 order-lg-2">
                <!-- Sticky Card -->
                <div class="card rounded-2 border-0 shadow-sm mb-3 mb-lg-0 sticky-top top">
                    <img class="card-img-top d-none d-lg-block" data-pt="image" src="" alt="Gambar">
                    <div class="card-body">		          	
                        <h5 class="mb-1" data-pt="title"></h5>
                        <p class="mb-1" data-pt="status"></p>
                        <p class="mb-0"><i class="bi-person"></i> <span data-pt="author"></span></p>
                        <p class="mb-0"><i class="bi-building"></i> <span data-pt="company"></span></p>
                        <p class="mb-0"><i class="bi-calendar2"></i> <span data-pt="date"></span></p>
                    </div>
		        </div>
            </div>
        </div>
    </div>
    <div id="footer-main"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
    <script src="https://psikologanda.com/assets/partials/template.js"></script>
    <script type="text/javascript">
        // Show datepicker
        $('input[name=birthdate]').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        });
        
        // Get the vacancy
        $.ajax({
            type: "get",
            url: "https://karir.psikologanda.com/api/vacancy/{{ $url_form }}",
            success: function(response) {
                $("[data-pt=title]").text(response.title);
                $("[data-pt=image]").attr("src",response.image);
                $("[data-pt=url]").attr("href","https://karir.psikologanda.com/lowongan/" + response.url);
                $("[data-pt=author]").text(response.author);
                $("[data-pt=company]").text(response.company);
                $("[data-pt=date]").text(response.date);
			    $("[data-pt=status]").html(response.status == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>');
            }
        });

        // Change image
        $(document).on("change", "input[type=file]", function() {
            var name = $(this).attr('name');
            if(this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("img[data-name=" + name + "]").attr("src", e.target.result).removeClass("d-none");
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>
</html>