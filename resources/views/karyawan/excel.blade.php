<table border="1">
	<tr>
		<td width="0"></td>
		<td align="center" width="5" style="background-color: #f88315;"><strong>No.</strong></td>
		<td align="center" width="40" style="background-color: #f88315;"><strong>Nama</strong></td>
		<td align="center" width="20" style="background-color: #f88315;"><strong>Tanggal Lahir</strong></td>
		<td align="center" width="20" style="background-color: #f88315;"><strong>Jenis Kelamin</strong></td>
		<td align="center" width="40" style="background-color: #f88315;"><strong>Email</strong></td>
		<td align="center" width="20" style="background-color: #f88315;"><strong>Nomor HP</strong></td>
		<td align="center" width="40" style="background-color: #f88315;"><strong>Alamat</strong></td>
		<td align="center" width="40" style="background-color: #f88315;"><strong>Pend. Terakhir</strong></td>
		<td align="center" width="20" style="background-color: #f88315;"><strong>Awal Bekerja</strong></td>
		<td align="center" width="40" style="background-color: #f88315;"><strong>Posisi</strong></td>
		<td align="center" width="40" style="background-color: #f88315;"><strong>Kantor</strong></td>
		@if(Auth::user()->role == role_admin())
		<td align="center" width="40" style="background-color: #f88315;"><strong>Perusahaan</strong></td>
		@endif
	</tr>
	@foreach($karyawan as $key=>$data)
	<tr>
		<td>{{ $data->id_user }}</td>
		<td>{{ ($key+1) }}</td>
        <td>{{ $data->nama_lengkap }}</td>
        <td>{{ $data->tanggal_lahir != null ? date('d/m/Y', strtotime($data->tanggal_lahir)) : '' }}</td>
        <td>{{ $data->jenis_kelamin }}</td>
        <td>{{ $data->email }}</td>
        <td>{{ $data->nomor_hp }}</td>
        <td>{{ $data->alamat }}</td>
        <td>{{ $data->pendidikan_terakhir }}</td>
        <td>{{ $data->awal_bekerja != null ? date('d/m/Y', strtotime($data->awal_bekerja)) : '' }}</td>
        <td>{{ get_posisi_name($data->posisi) }}</td>
        <td>{{ get_kantor_name($data->kantor) }}</td>
		@if(Auth::user()->role == role_admin())
        <td>{{ get_perusahaan_name($data->id_hrd) }}</td>
        @endif
	</tr>
	@endforeach
</table>