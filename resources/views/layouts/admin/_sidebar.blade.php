
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
				</ul>
			</div>
		</nav>