
<nav id="sidebar" class="sidebar js-sidebar">
	<div class="sidebar-content js-simplebar">
		<a class="sidebar-brand" href="/" target="_blank">
			<span class="align-middle">PersonalityTalk</span>
		</a>
		<ul class="sidebar-nav">

			@foreach(menu() as $menu)
				@if($menu['header'] != '')
					<li class="sidebar-header">{{ $menu['header'] }}</li>
				@endif
				@if(count($menu['items']) > 0)
					@foreach($menu['items'] as $key=>$item)
						@if(count($item['children']) > 0)
							<li class="sidebar-item {{ eval_sidebar($item['conditions'], 'active') }}">
								<a data-bs-target="#sidebar-subitem-{{ $key }}" data-bs-toggle="collapse" class="sidebar-link {{ eval_sidebar($item['conditions'], '', 'collapsed') }}">
									<i class="align-middle {{ $item['icon'] }}" style="font-size: 1rem;"></i> <span class="align-middle">{{ $item['name'] }}</span>
								</a>
								<ul id="sidebar-subitem-{{ $key }}" class="sidebar-dropdown list-unstyled collapse {{ eval_sidebar($item['conditions'], 'show') }}" data-bs-parent="#sidebar">
									@foreach($item['children'] as $subitem)
									<li class="sidebar-item {{ eval_sidebar($subitem['conditions'], 'active') }}"><a class="sidebar-link" href="{{ $subitem['route'] }}">{{ $subitem['name'] }}</a></li>
									@endforeach
								</ul>
							</li>
						@else
							<li class="sidebar-item {{ eval_sidebar($item['conditions'], 'active') }}">
								<a class="sidebar-link" href="{{ $item['route'] }}">
									<i class="align-middle {{ $item['icon'] }}" style="font-size: 1rem;"></i> <span class="align-middle">{{ $item['name'] }}</span>
								</a>
							</li>
						@endif
					@endforeach
				@endif
			@endforeach
			
			<li class="sidebar-header">Admin</li>
			@if(Auth::user()->role == role('admin'))
			<li class="sidebar-item {{ is_int(strpos(Request::url(), route('admin.hrd.index'))) ? 'active' : '' }}">
				<a class="sidebar-link" href="{{ route('admin.hrd.index') }}">
					<i class="align-middle bi-person-check" style="font-size: 1rem;"></i> <span class="align-middle">HRD</span>
				</a>
			</li>
			@endif
			@if(Auth::user()->role == role('admin'))
			<li class="sidebar-item {{ is_int(strpos(Request::url(), route('admin.test.index'))) ? 'active' : '' }}">
				<a class="sidebar-link" href="{{ route('admin.test.index') }}">
					<i class="align-middle bi-clipboard" style="font-size: 1rem;"></i> <span class="align-middle">Tes</span>
				</a>
			</li>
			@endif
			@if(stifin_access())
			<li class="sidebar-item {{ is_int(strpos(Request::url(), route('admin.stifin.index'))) ? 'active' : '' }}">
				<a class="sidebar-link" href="{{ route('admin.stifin.index') }}">
					<i class="align-middle bi-bullseye" style="font-size: 1rem;"></i> <span class="align-middle">STIFIn</span>
				</a>
			</li>
			@endif
		</ul>
	</div>
</nav>