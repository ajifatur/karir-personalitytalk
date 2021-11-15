
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="{{ route('home') }}" target="_blank">
                    <span class="align-middle">PersonalityTalk</span>
                </a>
				<ul class="sidebar-nav">
					<li class="sidebar-item {{ Request::url() == route('admin.dashboard') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                            <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                        </a>
					</li>

					<li class="sidebar-header">Data</li>
					<li class="sidebar-item {{ is_int(strpos(Request::url(), route('admin.office.index'))) ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('admin.office.index') }}">
							<i class="align-middle" data-feather="briefcase"></i> <span class="align-middle">Kantor</span>
						</a>
					</li>
					<li class="sidebar-item {{ is_int(strpos(Request::url(), route('admin.position.index'))) ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('admin.position.index') }}">
							<i class="align-middle" data-feather="paperclip"></i> <span class="align-middle">Jabatan</span>
						</a>
					</li>
					<li class="sidebar-item {{ is_int(strpos(Request::url(), route('admin.vacancy.index'))) ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('admin.vacancy.index') }}">
							<i class="align-middle" data-feather="wind"></i> <span class="align-middle">Lowongan</span>
						</a>
					</li>
					@if(stifin_access())
					<li class="sidebar-item {{ is_int(strpos(Request::url(), route('admin.stifin.index'))) ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('admin.stifin.index') }}">
							<i class="align-middle" data-feather="clipboard"></i> <span class="align-middle">STIFIn</span>
						</a>
					</li>
					@endif

					<li class="sidebar-header">Pengguna</li>
					<li class="sidebar-item {{ is_int(strpos(Request::url(), route('admin.hrd.index'))) ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('admin.hrd.index') }}">
							<i class="align-middle" data-feather="user-check"></i> <span class="align-middle">HRD</span>
						</a>
					</li>
					<li class="sidebar-item {{ is_int(strpos(Request::url(), route('admin.employee.index'))) ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('admin.employee.index') }}">
							<i class="align-middle" data-feather="user-x"></i> <span class="align-middle">Karyawan</span>
						</a>
					</li>
					<li class="sidebar-item {{ is_int(strpos(Request::url(), route('admin.applicant.index'))) ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('admin.applicant.index') }}">
							<i class="align-middle" data-feather="user-plus"></i> <span class="align-middle">Pelamar</span>
						</a>
					</li>
				</ul>
			</div>
		</nav>