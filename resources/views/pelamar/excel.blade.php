<table border="1">
	<tr>
		<td align="center" width="5" style="background-color: #f88315;"><strong>No.</strong></td>
		<td align="center" width="40" style="background-color: #f88315;"><strong>Nama</strong></td>
		<td align="center" width="30" style="background-color: #f88315;"><strong>Tempat Lahir</strong></td>
		<td align="center" width="30" style="background-color: #f88315;"><strong>Tanggal Lahir</strong></td>
		<td align="center" width="20" style="background-color: #f88315;"><strong>Jenis Kelamin</strong></td>
		<td align="center" width="30" style="background-color: #f88315;"><strong>Agama</strong></td>
		<td align="center" width="40" style="background-color: #f88315;"><strong>Email</strong></td>
		<td align="center" width="30" style="background-color: #f88315;"><strong>Nomor HP</strong></td>
		<td align="center" width="40" style="background-color: #f88315;"><strong>Alamat</strong></td>
		<td align="center" width="40" style="background-color: #f88315;"><strong>Riwayat Pekerjaan</strong></td>
		<td align="center" width="40" style="background-color: #f88315;"><strong>Posisi</strong></td>
		<td align="center" width="40" style="background-color: #f88315;"><strong>Perusahaan</strong></td>
	</tr>
	@foreach($pelamar as $key=>$data)
	<tr>
		<td>{{ ($key+1) }}</td>
        <td>{{ strtoupper($data->nama_lengkap) }}</td>
        <td>{{ $data->tempat_lahir }}</td>
        <td>{{ $data->tanggal_lahir != null ? generate_date($data->tanggal_lahir) : '-' }}</td>
        <td>{{ $data->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
        <td>{{ $data->nama_agama }}</td>
        <td>{{ $data->email }}</td>
        <td>{{ $data->nomor_hp }}</td>
        <td>{{ $data->alamat }}</td>
        <td>{{ $data->riwayat_pekerjaan }}</td>
        <td>{{ get_posisi_name($data->posisi) }}</td>
        <td>{{ get_perusahaan_name($data->id_hrd) }}</td>
	</tr>
	@endforeach
</table>