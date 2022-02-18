@extends('layouts/admin/main')

@section('title', 'Tambah Jabatan')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Tambah Jabatan</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.position.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="name" class="form-control form-control-sm {{ $errors->has('name') ? 'border-danger' : '' }}" value="{{ old('name') }}" autofocus>
                            @if($errors->has('name'))
                            <div class="small text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>
                    @if(Auth::user()->role_id == role('admin'))
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Perusahaan <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="hrd" class="form-select form-select-sm {{ $errors->has('hrd') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($hrds as $hrd)
                                <option value="{{ $hrd->id_hrd }}">{{ $hrd->perusahaan }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('hrd'))
                            <div class="small text-danger">{{ $errors->first('hrd') }}</div>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tes</label>
                        <div class="col-lg-10 col-md-9">
                            <div>
                                @foreach($tests as $test)
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" name="tests[]" type="checkbox" value="{{ $test->id_tes }}">
                                    <span class="form-check-label">{{ $test->nama_tes }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Keahlian</label>
                        <div class="col-lg-10 col-md-9">
                            <div class="row">
                                <div class="col-12 mb-2 input-skill" data-id="1">
                                    <div class="input-group">
                                        <input name="skills[]" type="text" class="form-control form-control-sm" placeholder="Masukkan Keahlian">
                                        <button class="btn btn-sm btn-success btn-add" type="button" data-id="1" title="Tambah"><i class="bi-plus"></i></button>
                                        <button class="btn btn-sm btn-danger btn-delete" type="button" data-id="1" title="Hapus"><i class="bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('admin.position.index') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

@endsection

@section('js')

<script>
    // Button Add
    $(document).on("click", ".btn-add", function(e){
        e.preventDefault();
        var id = $(this).data("id");
        var input = $(".input-skill");
        var html = '';
        html += '<div class="col-12 mb-2 input-skill" data-id="' + (input.length+1) + '">';
        html += '<div class="input-group">';
        html += '<input name="skills[]" type="text" class="form-control form-control-sm" placeholder="Masukkan Keahlian">';
        html += '<button class="btn btn-sm btn-success btn-add" type="button" data-id="' + (input.length+1) + '" title="Tambah"><i class="bi-plus"></i></button>';
        html += '<button class="btn btn-sm btn-danger btn-delete" type="button" data-id="' + (input.length+1) + '" title="Hapus"><i class="bi-trash"></i></button>';
        html += '</div>';
        html += '</div>';
        $(".input-skill[data-id=" + input.length + "]").after(html);
    });

    // Button Delete
    $(document).on("click", ".btn-delete", function(e){
        e.preventDefault();
        var id = $(this).data("id");
        var input = $(".input-skill");
        if(input.length <= 1) {
            $(".input-skill[data-id=" + id + "]").find("input[type=text]").val("");
        }
        else{
            $(".input-skill[data-id=" + id + "]").remove();
            var inputAfter = $(".input-skill");
            inputAfter.each(function(key,elem) {
                $(elem).attr("data-id", (key+1));
                $(elem).find(".btn-add").attr("data-id", (key+1));
                $(elem).find(".btn-delete").attr("data-id", (key+1));
            });
        }
    });
</script>

@endsection