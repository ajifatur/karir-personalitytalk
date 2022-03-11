@extends('layouts/admin/main')

@section('title', 'Pelamar Lowongan: '.$vacancy->name)

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Pelamar Lowongan: {{ $vacancy->name }}</h1>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
            <div class="card-body">
                @if(Session::get('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-message">{{ Session::get('message') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered" id="datatable">
                        <thead class="bg-light">
                            <tr>
                                <th>Identitas</th>
                                <th width="100">Username</th>
                                <th width="100">Waktu</th>
                                <th width="80">Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applicants as $applicant)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.applicant.detail', ['id' => $applicant->id]) }}">{{ ucwords($applicant->name) }}</a>
                                    <br>
                                    <small class="text-muted"><i class="bi-envelope me-2"></i>{{ $applicant->email }}</small>
                                    <br>
                                    <small class="text-muted"><i class="bi-phone me-2"></i>{{ $applicant->attribute->phone_number }}</small>
                                </td>
                                <td>{{ $applicant->username }}</td>
                                <td>
                                    <span class="d-none">{{ $applicant->created_at }}</span>
                                    {{ date('d/m/Y', strtotime($applicant->created_at)) }}
                                    <br>
                                    <small class="text-muted">{{ date('H:i', strtotime($applicant->created_at)) }} WIB</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $applicant->badge_color }}">{{ $applicant->status }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
		</div>
	</div>
</div>

@endsection

@section('js')

<script type="text/javascript">
    // DataTable
    Spandiv.DataTable("#datatable", {
        orderAll: true
    });

    // Button Delete
    Spandiv.ButtonDelete(".btn-delete", ".form-delete");
</script>

@endsection
