<!DOCTYPE html>
<html lang="en">
<head>
	@include('template/admin/_head')
	@include('template/admin/_css')
	@yield('css-extra')
	<title>Sistem Rekruitmen | Promosi | Penjajakan Karyawan</title>
</head>
<body id="page-top">
	<!-- Page Wrapper -->
	<div id="wrapper">
		@include('template/admin/_sidebar')
		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">
			<!-- Main Content -->
			<div id="content">
				@include('template/admin/_topbar')
		        <!-- Begin Page Content -->
		        <div class="container-fluid">
		        	@yield('content')
		        </div>
		        <!-- /.container-fluid -->
			</div>
			<!-- End of Main Content -->
	  		@include('template/admin/_footer')
	    </div>
	    <!-- End of Content Wrapper -->
	</div>
	<!-- End of Page Wrapper -->
	@include('template/admin/_scroll-to-top-button')
	@include('template/admin/_js')
	@yield('js-extra')
</body>
</html>
