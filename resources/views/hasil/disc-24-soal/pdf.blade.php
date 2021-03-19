<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<title>Hasil Tes {{ $tes }}</title>
	<link rel="shortcut icon" href="{{ asset('assets/images/icon.png') }}">
	<style>
	    @page, body {margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px;}
	    table {font-size: 13px; margin-bottom: 20px;}
	    .title-deskripsi {width: 100%; text-align: center; margin-top: 15px; margin-bottom: 15px;}
	    .deskripsi p {text-align: justify; margin-bottom: 4px; font-size: 12px;}
	    #header, #footer {position: fixed; left: 0; right: 0; color: #333; font-size: 0.9em;}
        #header {top: -20px; border-bottom: 0.1pt solid #aaa; text-align: right;}
        #header img {position: absolute; top: -3px; left: 0;}
	    #footer {bottom: 0; border-top: 0.1pt solid #aaa; text-align: right;}
	    .page-number {font-size: 12px;}
        .page-number:before {content: attr(data-nama) " | " attr(data-site) " | Page " counter(page);}
	</style>
</head>
<body>
	@php
		$hasil = json_decode($hasil, true);
		$array_selisih = json_decode($array_selisih, true);
		$index = json_decode($index, true);
	@endphp
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
        <div class="page-number" data-nama="{{ $nama }}" data-site="www.psikologanda.com"></div>
    </div>
    <h5 class="text-center font-weight-bold mt-3 mb-4">Hasil Tes {{ $tes }}</h5>
    <table width="100%" border="1" style="margin-top: 20px;">
        <tr>
            <td align="center">Nama : {{ $nama }}</td>
			@if($jenis_kelamin != '-')
            <td align="center">Usia : {{ $usia }}</td>
            <td align="center">Jenis Kelamin : {{ $jenis_kelamin }}</td>
			@endif
            <td align="center">Posisi : {{ $posisi }}</td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td colspan="3" align="center" valign="top">
                <table border="1" style="margin-top: 10px; margin-left: auto; margin-right: auto;">
                    <tr>
                        <td align="center" width="20" height="20" bgcolor="#bbb"><strong>#</strong></td>
                        @foreach($disc as $letter)
                        <td align="center" width="30" height="20" bgcolor="#bebebe"><strong>{{ $letter }}</strong></td>
                        @endforeach
                        <td align="center" width="30" height="20" bgcolor="#bebebe"><strong>*</strong></td>
                        <td align="center" width="30" height="20" bgcolor="#bebebe"><strong>Total</strong></td>
                    </tr>
                    <tr>
                        <td align="center" height="20" bgcolor="#bebebe"><strong>1</strong></td>
                        <td align="center" height="20">{{ $hasil['dm'] }}</td>
                        <td align="center" height="20">{{ $hasil['im'] }}</td>
                        <td align="center" height="20">{{ $hasil['sm'] }}</td>
                        <td align="center" height="20">{{ $hasil['cm'] }}</td>
                        <td align="center" height="20">{{ $hasil['bm'] }}</td>
                        <td align="center" height="20">24</td>
                    </tr>
                    <tr>
                        <td align="center" height="20" bgcolor="#bebebe"><strong>2</strong></td>
                        <td align="center" height="20">{{ $hasil['dl'] }}</td>
                        <td align="center" height="20">{{ $hasil['il'] }}</td>
                        <td align="center" height="20">{{ $hasil['sl'] }}</td>
                        <td align="center" height="20">{{ $hasil['cl'] }}</td>
                        <td align="center" height="20">{{ $hasil['bl'] }}</td>
                        <td align="center" height="20">24</td>
                    </tr>
                    <tr>
                        <td align="center" height="20" bgcolor="#bebebe"><strong>3</strong></td>
                        <td align="center" height="20">{{ $array_selisih['D'] }}</td>
                        <td align="center" height="20">{{ $array_selisih['I'] }}</td>
                        <td align="center" height="20">{{ $array_selisih['S'] }}</td>
                        <td align="center" height="20">{{ $array_selisih['C'] }}</td>
                        <td align="center" height="20" bgcolor="#333"></td>
                        <td align="center" height="20" bgcolor="#333"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="bottom">
                <strong>
                    Mask Public Self
                    <br>MOST<br>
                </strong>
            </td>
            <td align="center" valign="bottom">
                <strong>
                    Core Private Self
                    <br>LEAST<br>
                </strong>
            </td>
            <td align="center" valign="bottom">
                <strong>
                    Mirror Perceived Self
                    <br>CHANGE<br>
                </strong>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top"><img src="{{ $mostChartImage }}" width="200"></td>
            <td align="center" valign="top"><img src="{{ $leastChartImage }}" width="200"></td>
            <td align="center" valign="top"><img src="{{ $changeChartImage }}" width="200"></td>
        </tr>
        <tr>
            <td valign="top">
				@php
					$karakteristik1 = explode(', ', $most['karakteristik']);
				@endphp
				<strong>{{ $most['tipe'] }}</strong>
				<ul>
					@foreach($karakteristik1 as $karakter)
					<li>{{ $karakter }}</li>
					@endforeach
				</ul>
			</td>
            <td valign="top">
				@php
					$karakteristik2 = explode(', ', $least['karakteristik']);
				@endphp
				<strong>{{ $least['tipe'] }}</strong>
				<ul>
					@foreach($karakteristik2 as $karakter)
					<li>{{ $karakter }}</li>
					@endforeach
				</ul>
			</td>
            <td valign="top">
				@php
					$karakteristik3 = explode(', ', $change['karakteristik']);
				@endphp
				<strong>{{ $change['tipe'] }}</strong>
				<ul>
					@foreach($karakteristik3 as $karakter)
					<li>{{ $karakter }}</li>
					@endforeach
				</ul>
			</td>
        </tr>
        <tr>
            <td valign="top" colspan="3">
				<strong>Deskripsi Kepribadian</strong><br>
				{{ $change['deskripsi'] }}
			</td>
        </tr>
        <tr>
            <td valign="top" colspan="3">
				<strong>Job Match</strong><br>
				{{ $change['job'] }}
			</td>
		</tr>
    </table>
</body>
</html>