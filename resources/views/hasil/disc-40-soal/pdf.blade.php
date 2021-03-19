<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />
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
            <td align="center" valign="bottom">
                <strong>
                    Skor DISC
                </strong>
            </td>
            <td align="center" valign="bottom">
                <strong>
                    Response to the Environment
                    <br>MOST<br>
                    Adapted: ({{ $most }})
                </strong>
            </td>
            <td align="center" valign="bottom">
                <strong>
                    Basic Style
                    <br>LEAST<br>
                    Natural: ({{ $least }})
                </strong>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <table border="1" style="margin-top: 10px; margin-left: auto; margin-right: auto;">
                    <tr>
                        <td align="center" width="60" height="20" bgcolor="#bbb"><strong>#</strong></td>
                        <td align="center" width="60" height="20" bgcolor="#bebebe"><strong>MOST</strong></td>
                        <td align="center" width="60" height="20" bgcolor="#bebebe"><strong>LEAST</strong></td>
                    </tr>
                    @foreach($disc as $letter)
                    <tr>
                        <td align="center" height="20" bgcolor="#bebebe"><strong>{{ $letter }}</strong></td>
                        <td align="center" height="20" bgcolor="#eeeeee">{{ array_key_exists($letter, $disc_score_m) ? $disc_score_m[$letter]['score'] : 0 }}</td>
                        <td align="center" height="20" bgcolor="#eeeeee">{{ array_key_exists($letter, $disc_score_l) ? $disc_score_l[$letter]['score'] : 0 }}</td>
                    </tr>
                    @endforeach
                </table>
            </td>
            <td align="center" valign="top"><img src="{{ $mostChartImage }}" width="200"></td>
            <td align="center" valign="top"><img src="{{ $leastChartImage }}" width="200"></td>
        </tr>
    </table>
    <div class="title-deskripsi"><strong>Deskripsi</strong></div>
    <div class="deskripsi">{!! html_entity_decode($deskripsi) !!}</div>
</body>
</html>