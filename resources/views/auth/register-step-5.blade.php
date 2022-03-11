@extends('auth.register-layout')

@section('content')

<div class="text-center">
    <h1 class="h4 text-gray-900 mb-5 text-uppercase">Form Data Keahlian</h1>
</div>
<form id="form" method="post" action="/lowongan/{{ $url_form }}/daftar/step-5" enctype="multipart/form-data">
    @csrf
    @if(count($errors)>0)
    <div class="alert alert-danger m-0 mb-3">
        Keahlian harus diisi semua.
    </div>
    @endif
    <div class="row">
        <div class="form-group col-sm-12">
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
                        <td>
                            {{ $skill->name }}
                            <input type="hidden" name="skills[{{ $key }}][id]" value="{{ $skill->id }}">
                        </td>
                        <td align="center" width="100">
                            <div class="form-check">
                                <input type="radio" id="{{ $key }}-1" name="skills[{{ $key }}][score]" value="3" class="form-check-input">
                                <label class="form-check-label" for="{{ $key }}-1">Baik</label>
                            </div>
                        </td>
                        <td align="center" width="100">
                            <div class="form-check">
                                <input type="radio" id="{{ $key }}-2" name="skills[{{ $key }}][score]" value="2" class="form-check-input">
                                <label class="form-check-label" for="{{ $key }}-2">Cukup</label>
                            </div>
                        </td>
                        <td align="center" width="100">
                            <div class="form-check">
                                <input type="radio" id="{{ $key }}-3" name="skills[{{ $key }}][score]" value="1" class="form-check-input">
                                <label class="form-check-label" for="{{ $key }}-3">Kurang</label>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="form-group mt-3">
        <div class="row">
            <div class="col-auto ms-auto">
                <input type="hidden" name="url" value="{{ $url_form }}">
                <a href="/lowongan/{{ $url_form }}/daftar/step-4" class="btn btn-secondary">&laquo; Sebelumnya</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Submit</button>
            </div>
        </div>
    </div>
</form>

@endsection