<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />
	<title>Hasil Tes {{ $test }}</title>
	<link rel="shortcut icon" href="{{ asset('assets/images/icon.png') }}">
	<style>
	    @page, body {margin-bottom: 10px;, padding-top: 10px; padding-bottom: 10px;}
	    table {font-size: 13px;}
		table tr td {vertical-align: top;}
		.content {font-size: 13px;}
	    #header, #footer {position: fixed; left: 0; right: 0; color: #333; font-size: 0.9em;}
        #header {top: -20px; border-bottom: 0.1pt solid #aaa; text-align: right;}
        #header img {position: absolute; top: -3px; left: 0;}
	    #footer {bottom: 0; border-top: 0.1pt solid #aaa; text-align: right;}
	    .page-number {font-size: 12px;}
        .page-number:before {content: attr(data-nama) " | " attr(data-site) " | Page " counter(page);}
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
        <div class="page-number" data-nama="{{ $name }}" data-site="www.psikologanda.com"></div>
    </div>
    <h5 class="text-center font-weight-bold mt-3 mb-4">Hasil Tes {{ $test }}</h5>
    <table width="100%" border="0" style="margin-top: 20px;">
		<tr>
			<td width="50%">
				<table width="100%" border="0">
					<tr>
						<td width="80">Nama</td>
						<td width="5">:</td>
						<td>{{ $name }}</td>
					</tr>
					<tr>
						<td width="80">Jenis Kelamin</td>
						<td width="5">:</td>
						<td>{{ $gender }}</td>
					</tr>
				</table>
			</td>
			<td width="50%">
				<table width="100%" border="0">
					<tr>
						<td width="80">Usia</td>
						<td width="5">:</td>
						<td>{{ $age }}</td>
					</tr>
					<tr>
						<td width="80">Posisi</td>
						<td width="5">:</td>
						<td>{{ $position }}</td>
					</tr>
				</table>
			</td>
		</tr>
    </table>
	<hr class="my-2">
	<div class="content">
		<h6>Arah Minat:</h6>
		<ol>
			@foreach($interests as $key=>$interest)
			<li>
				<span class="font-weight-bold">{{ $interest['name'] }}</span>
				<br>
				{{ $interest['description'] }}
				<br>
				Contoh: {{ $interest['example'] }}
			</li>
			@endforeach
		</ol>
		@if(array_key_exists('occupations', $result->result))
		<h6>Pekerjaan yang paling diinginkan:</h6>
		<ol>
			@foreach($result->result['occupations'] as $occupation)
			<li>{{ $occupation }}</li>
			@endforeach
		</ol>
		@endif
	</div>
	<hr class="my-2">
	<div class="content">
		<table class="table table-sm table-bordered">
			<thead bgcolor="#bebebe">
				<tr>
					<th width="20">No.</th>
					<th width="80">Kategori</th>
					@foreach($letters as $letter)
					<th width="20">{{ $letter }}</th>
					@endforeach
					<th width="20">Jumlah</th>
					<th width="20">Rank</th>
					<th width="20">%</th>
				</tr>
			</thead>
			<tbody>
				@foreach($categories as $keyc=>$category)
					<tr bgcolor="{{ $category_ranks[$keyc] <= 3 ? '#e5e5e5' : '' }}">
						<td>{{ ($keyc+1) }}</td>
						<td>{{ $category }}</td>
						@for($i=0; $i<=8; $i++)
							<td class="{{ $keyc == $i ? 'text-primary fw-bold' : '' }}">{{ $sheets[$keyc][$i] }}</td>
						@endfor
						<td>{{ $sums[$keyc] }}</td>
						<td>{{ $category_ranks[$keyc] }}</td>
						<td></td>
					</tr>
				@endforeach
				<tr>
					<td colspan="11"></td>
					<td class="fw-bold">{{ array_sum($sums) }}</td>
					<td colspan="2"></td>
				</tr>
		</tbody>
	</table>
	</div>
</body>
</html>