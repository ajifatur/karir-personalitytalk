<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />
	<title>Hasil Tes {{ $tes }}</title>
	<link rel="shortcut icon" href="{{ asset('assets/images/icon.png') }}">
	<style>
	    @page, body {margin-bottom: 10px;, padding-top: 10px; padding-bottom: 10px;}
	    table {font-size: 13px; margin-bottom: 20px;}
	    .tipe-title {font-weight: bold; margin: 10px 0px; font-size: 14px;}
	    .tipe-deskripsi {text-align: justify; margin-bottom: 4px; font-size: 13px;}
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
            <td align="center">Usia : {{ $usia }}</td>
            <td align="center">Jenis Kelamin : {{ $jenis_kelamin }}</td>
            <td align="center">Posisi : {{ $posisi }}</td>
        </tr>
    </table>
    <table width="100%" border="1">
        <tr>
            <td align="center" width="30"><strong>No.</strong></td>
            <td align="center" width="40"><strong>Huruf</strong></td>
            <td align="center" width="40"><strong>Skor</strong></td>
            <td align="center"><strong>Deskripsi</strong></td>
        </tr>
        @php
          $data = array();
        @endphp
        
        @foreach($huruf as $key=>$h)
          <tr>
              <td align="center">{{ $key+1 }}.</td>
              <td align="center">{{ $h }}</td>
              <td align="center">{{ $hasil->hasil[$h] }}</td>
              <td style="padding-left: 10px; padding-right: 10px;">{{ analisisPapikostick($hasil->hasil[$h], $keterangan->keterangan[$h]) }}</td>
              @php array_push($data, array('letter' => $h, 'number' => $hasil->hasil[$h])); @endphp
          </tr>
        @endforeach
    </table>
    <table width="100%">
        <tr>
            <td align="center"><img src="{{ $image }}" width="400"></td>
        </tr>
    </table>
</body>
</html>