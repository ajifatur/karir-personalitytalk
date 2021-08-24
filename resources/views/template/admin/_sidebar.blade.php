
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="/admin">
	<!--<div class="sidebar-brand-icon rotate-n-15">-->
	<!--<i class="fas fa-laugh-wink"></i>-->
	<div class="sidebar-brand-icon">
	<img src="{{ asset('assets/images/1599633828-icon.png') }}" width="32" class="img-fluid">
	</div>
	<div class="sidebar-brand-text">
	<img src="{{ asset('assets/images/logo-2.png') }}" class="img-fluid">
	</div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item {{ Request::path() == 'admin' ? 'active' : '' }}">
	<a class="nav-link" href="/admin">
	<i class="fas fa-fw fa-tachometer-alt"></i>
	<span>Dashboard</span></a>
</li>
<li class="nav-item {{ strpos(Request::url(), '/admin/profil') ? 'active' : '' }}">
	<a class="nav-link" href="/admin/profil">
	<i class="fas fa-fw fa-user"></i>
	<span>Profil</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
	Data
</div>

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item {{ strpos(Request::url(), '/admin/kantor') ? 'active' : '' }}">
	<a class="nav-link" href="/admin/kantor">
	<i class="fas fa-fw fa-building"></i>
	<span>Kantor</span></a>
</li>
<li class="nav-item {{ strpos(Request::url(), '/admin/posisi') ? 'active' : '' }}">
	<a class="nav-link" href="/admin/posisi">
	<i class="fas fa-fw fa-route"></i>
	<span>Jabatan</span></a>
</li>
<li class="nav-item {{ strpos(Request::url(), '/admin/lowongan') ? 'active' : '' }}">
	<a class="nav-link" href="/admin/lowongan">
	<i class="fas fa-fw fa-bolt"></i>
	<span>Lowongan</span>
	@if(count_pelamar_belum_diseleksi() > 0)
	<span class="float-md-right badge badge-warning text-dark" data-toggle="tooltip" title="Ada {{ count_pelamar_belum_diseleksi() }} pelamar yang belum diseleksi">{{ count_pelamar_belum_diseleksi() }}</span>
	@endif
	</a>
</li>
<li class="nav-item {{ strpos(Request::url(), '/admin/seleksi') ? 'active' : '' }}">
	<a class="nav-link" href="/admin/seleksi">
	<i class="fas fa-fw fa-user-check"></i>
	<span>Seleksi</span>
	@if(count_pelamar_belum_dites() > 0)
	<span class="float-md-right badge badge-warning text-dark" data-toggle="tooltip" title="Ada {{ count_pelamar_belum_dites() }} pelamar yang belum dites">{{ count_pelamar_belum_dites() }}</span>
	@endif
	</a>
</li>
<li class="nav-item {{ strpos(Request::url(), '/admin/hasil') ? 'active' : '' }}">
	<a class="nav-link {{ strpos(Request::url(), '/admin/hasil') ? '' : '' }}" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
	<i class="fas fa-fw fa-chart-line"></i>
	<span>Hasil Tes</span>
	</a>
	<div id="collapseTwo" class="collapse {{ strpos(Request::url(), '/admin/hasil') ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
	<div class="bg-white py-2 collapse-inner rounded">
		<h6 class="collapse-header">Hasil Tes:</h6>
		<a class="collapse-item {{ strpos(Request::url(), '/admin/hasil/karyawan') ? 'active' : '' }}" href="/admin/hasil/karyawan">Karyawan</a>
		<a class="collapse-item {{ strpos(Request::url(), '/admin/hasil/pelamar') ? 'active' : '' }}" href="/admin/hasil/pelamar">Pelamar</a>
		@if(Auth::user()->role == role_admin())
		<a class="collapse-item {{ strpos(Request::url(), '/admin/hasil/magang') ? 'active' : '' }}" href="/admin/hasil/magang">Magang</a>
		@endif
	</div>
	</div>
</li>
@if(stifin_access())
<li class="nav-item {{ strpos(Request::url(), '/admin/stifin') ? 'active' : '' }}">
	<a class="nav-link" href="/admin/stifin">
	<i class="fas fa-fw fa-clipboard"></i>
	<span>STIFIn</span>
	</a>
</li>
@endif

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
	User
</div>

<!-- Nav Item - Pages Collapse Menu -->
@if(Auth::user()->role == role_admin())
<!--<li class="nav-item {{ strpos(Request::url(), '/admin/list') ? 'active' : '' }}">
	<a class="nav-link" href="/admin/list">
	<i class="fas fa-fw fa-user-secret"></i>
	<span>Admin</span></a>
</li>-->
<li class="nav-item {{ strpos(Request::url(), '/admin/hrd') ? 'active' : '' }}">
	<a class="nav-link" href="/admin/hrd">
	<i class="fas fa-fw fa-user-shield"></i>
	<span>HRD</span></a>
</li>
@endif
<li class="nav-item {{ strpos(Request::url(), '/admin/karyawan') ? 'active' : '' }}">
	<a class="nav-link" href="/admin/karyawan">
	<i class="fas fa-fw fa-user-cog"></i>
	<span>Karyawan</span></a>
</li>
<li class="nav-item {{ strpos(Request::url(), '/admin/pelamar') ? 'active' : '' }}">
	<a class="nav-link" href="/admin/pelamar">
	<i class="fas fa-fw fa-user-tie"></i>
	<span>Pelamar</span></a>
</li>
@if(Auth::user()->username == 'ajifatur')
<li class="nav-item {{ strpos(Request::url(), '/admin/umum') ? 'active' : '' }}">
	<a class="nav-link" href="/admin/umum">
	<i class="fas fa-fw fa-users"></i>
	<span>Umum</span></a>
</li>
@endif

@if(Auth::user()->role == role_admin())
<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
Master
</div>

<li class="nav-item {{ strpos(Request::url(), '/admin/agama') ? 'active' : '' }}">
<a class="nav-link" href="/admin/agama">
	<i class="fas fa-fw fa-mosque"></i>
	<span>Agama</span></a>
</li>
<li class="nav-item {{ strpos(Request::url(), '/admin/tes') ? 'active' : '' }}">
<a class="nav-link" href="/admin/tes">
	<i class="fas fa-fw fa-clipboard"></i>
	<span>Tes</span></a>
</li>
@endif  

<!-- Divider -->
<!--<hr class="sidebar-divider">-->

<!-- Heading -->
<!--<div class="sidebar-heading">-->
<!--  Test-->
<!--</div>-->

<!-- Nav Item - Pages Collapse Menu -->
<!--@foreach($global_tes as $data)-->
<!--<li class="nav-item">-->
<!--  <a class="nav-link" href="/admin/tes/tipe/{{ $data->id_tes }}">-->
<!--    <i class="fas fa-fw fa-clipboard"></i>-->
<!--    <span>{{ $data->nama_tes }}</span></a>-->
<!--</li>-->
<!--@endforeach-->

<!--       <li class="nav-item">
	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
	<i class="fas fa-fw fa-cog"></i>
	<span>DISC</span>
	</a>
	<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
	<div class="bg-white py-2 collapse-inner rounded">
		<h6 class="collapse-header">Custom DISC:</h6>
		<a class="collapse-item" href="/admin/disc">List Soal</a>
		<a class="collapse-item" href="/admin/disc/create">Tambah Soal</a>
		<a class="collapse-item" href="/admin/disc/tutorial">Tutorial</a>
	</div>
	</div>
</li> -->

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<!--       <div class="text-center d-none d-md-inline">
	<button class="rounded-circle border-0" id="sidebarToggle" title="Show/Hide Sidebar"></button>
</div> -->

</ul>
<!-- End of Sidebar -->