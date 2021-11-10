<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts/admin/_head')
    @yield('css')

    <title>@yield('title') :: Sistem Rekruitmen | Promosi | Penjajakan Karyawan</title>
</head>
<body>
	<div class="wrapper">
        @include('layouts/admin/_sidebar')
        
		<div class="main">
            @include('layouts/admin/_header')

			<main class="content">
				<div class="container-fluid p-0">
                    @yield('content')
				</div>
			</main>

            @include('layouts/admin/_footer')

		</div>
	</div>

    @include('layouts/admin/_js')
    @yield('js')

</body>
</html>