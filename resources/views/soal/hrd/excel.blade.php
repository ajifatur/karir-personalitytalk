<table border="1">
	<tr>
		<td align="center" width="5"><strong>No.</strong></td>
		<td align="center" width="50"><strong>Karakteristik A</strong></td>
		<td align="center" width="50"><strong>Karakteristik B</strong></td>
		<td align="center" width="50"><strong>Karakteristik C</strong></td>
		<td align="center" width="50"><strong>Karakteristik D</strong></td>
		<td align="center" width="10"><strong>DISC A</strong></td>
		<td align="center" width="10"><strong>DISC B</strong></td>
		<td align="center" width="10"><strong>DISC C</strong></td>
		<td align="center" width="10"><strong>DISC D</strong></td>
	</tr>
	@foreach($soal as $data)
	<tr>
		<td>{{ $data->nomor }}</td>
		<td>{{ $data->soal[0]['pilihan']['A'] }}</td>
		<td>{{ $data->soal[0]['pilihan']['B'] }}</td>
		<td>{{ $data->soal[0]['pilihan']['C'] }}</td>
		<td>{{ $data->soal[0]['pilihan']['D'] }}</td>
		<td align="center">{{ $data->soal[0]['disc']['A'] }}</td>
		<td align="center">{{ $data->soal[0]['disc']['B'] }}</td>
		<td align="center">{{ $data->soal[0]['disc']['C'] }}</td>
		<td align="center">{{ $data->soal[0]['disc']['D'] }}</td>
	</tr>
	@endforeach
</table>