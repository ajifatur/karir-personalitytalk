@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Update Sistem</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item active" aria-current="page">Update Sistem</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="row">
        @if(count(get_data_update())>0)
          @foreach(get_data_update() as $data)
          <div class="col-12 mb-3">
            <div class="card">
              <h5 class="card-header">
                {{ $data->judul_update }}
              </h5>
              <div class="card-body">
                <p class="card-text">{!! nl2br($data->deskripsi_update) !!}</p>
              </div>
              <div class="card-footer small text-muted">
                {{ generate_date($data->update_at) }}, {{ date('H:i', strtotime($data->update_at)) }} WIB
              </div>
            </div>
          </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>
  
@endsection