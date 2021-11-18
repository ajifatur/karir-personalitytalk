@extends('layouts/admin/main')

@section('title', 'Dashboard')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Dashboard</h1>
</div>
<div class="alert alert-success text-center fade show" role="alert">
    <div class="alert-message">Selamat datang <strong>{{ Auth::user()->nama_user }}</strong> di PersonalityTalk.</div>
</div>

@endsection