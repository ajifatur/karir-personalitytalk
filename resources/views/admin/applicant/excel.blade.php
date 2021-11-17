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
	@foreach($applicants as $key=>$applicant)
	<tr>
		<td>{{ ($key+1) }}</td>
        <td>{{ strtoupper($applicant->nama_lengkap) }}</td>
        <td>{{ $applicant->tempat_lahir }}</td>
        <td>{{ $applicant->tanggal_lahir != null ? generate_date($applicant->tanggal_lahir) : '-' }}</td>
        <td>{{ gender($applicant->jenis_kelamin) }}</td>
        <td>{{ $applicant->nama_agama }}</td>
        <td>{{ $applicant->email }}</td>
        <td>{{ $applicant->nomor_hp }}</td>
        <td>{{ $applicant->alamat }}</td>
        <td>{{ $applicant->riwayat_pekerjaan }}</td>
        <td>{{ get_posisi_name($applicant->posisi) }}</td>
        <td>{{ get_perusahaan_name($applicant->id_hrd) }}</td>
	</tr>
	@endforeach
</table>