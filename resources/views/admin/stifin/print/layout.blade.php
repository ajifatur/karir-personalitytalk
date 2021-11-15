<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="shortcut icon" href="{{ asset('assets/images/1599633828-icon.png') }}">
	<title>STIFIn</title>
	<style>
	    @page, body {margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px;}
	    #header, #footer {position: fixed; left: 0; right: 0; color: #333; font-size: 0.9em;}
        #header {top: -20px; border-bottom: 0.1pt solid #aaa; text-align: right;}
        #header img {position: absolute; top: -20px; left: 0;}
	    #footer {bottom: 0; border-top: 0.1pt solid #aaa; text-align: right;}
	    .page-number {font-size: 12px;}
        .page-number:before {content: attr(data-site) " | Page " counter(page);}
		
		#body {margin-top: 0px;}
		#identity {font-size: 14px; line-height: 17.5px; margin-bottom: 25px;}
		#title {width: 100%; text-align: center; margin-bottom: 25px;}
		.description {font-size: 14px; line-height: 17.5px; text-align: justify;}
		.description table tr td {text-align: center;}
	</style>
</head>
<body>
    <script type="text/pdf">
        if(isset($pdf)){
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $size = 10;
            $font = $pdf->getDomPDF()->getFontMetrics()->getFont("helvetica");
            $width = $pdf->getDomPDF()->getFontMetrics()->getTextWidth($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->getDomPDF()->getCanvas()->text($x, $y, $text, $font, $size);
        }
    </script>
    <div id="header">
        <img src="{{ asset('assets/images/logo-2-black.png') }}" height="20">
    </div>
    <div id="footer">
        <div class="page-number" data-site="www.psikologanda.com"></div>
    </div>
	<div id="body">
		<div id="identity">
			<table width="100%">
				<tr valign="top">
					<td width="50%">
						<table width="100%">
							<tr>
								<td width="80">Nama</td>
								<td width="10">:</td>
								<td>{{ $stifin->name }}</td>
							</tr>
							<tr>
								<td width="80">Usia</td>
								<td width="10">:</td>
								<td>{{ diff_date($stifin->birthdate, $stifin->test_at) != '' ? diff_date($stifin->birthdate, $stifin->test_at).' tahun' : '-' }}</td>
							</tr>
							<tr>
								<td width="80">Jenis Kelamin</td>
								<td width="10">:</td>
								<td>{{ $stifin->gender == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
							</tr>
						</table>
					</td>
					<td width="50%">
						<table width="100%">
							<tr>
								<td width="80">Tanggal Tes</td>
								<td width="10">:</td>
								<td>{{ $stifin->test_at != null ? setFullDate($stifin->test_at) : '-' }}</td>
							</tr>
							<tr>
								<td width="80">Tujuan Tes</td>
								<td width="10">:</td>
								<td>{{ $stifin->aims ? $stifin->aims->aim : '-' }}</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<div id="title">
			<p class="mb-1 h5">Deskripsi Tipe Kepribadian STIFIn</p>
			<p class="mb-1 h5 font-weight-bold">{{ strtoupper($stifin->name) }}</p>
			<p class="mb-1 h5">"{{ $stifin->tests->test_name }}" ({{ $stifin->tests->test_code }})</p>
		</div>
		@yield('description')
		<div class="description">
			<table width="100%">
				<tr>
					<td style="text-align: left;">
						Salam Pencerahan,<br/><br/><br/><br/><b>{{ $stifin->hrd->nama_lengkap }}</b><br/>Personality Genetic Consultant
					</td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>