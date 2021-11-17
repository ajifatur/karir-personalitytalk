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
		@if(Auth::user()->role == role('admin'))
		<td align="center" width="40" style="background-color: #f88315;"><strong>Perusahaan</strong></td>
		@endif
	</tr>
	@foreach($employees as $key=>$employee)
	<tr>
		<td>{{ $employee->id_user }}</td>
		<td>{{ ($key+1) }}</td>
        <td>{{ $employee->nama_lengkap }}</td>
        <td>{{ $employee->tanggal_lahir != null ? date('d/m/Y', strtotime($employee->tanggal_lahir)) : '' }}</td>
        <td>{{ $employee->jenis_kelamin }}</td>
        <td>{{ $employee->email }}</td>
        <td>{{ $employee->nomor_hp }}</td>
        <td>{{ $employee->alamat }}</td>
        <td>{{ $employee->pendidikan_terakhir }}</td>
        <td>{{ $employee->awal_bekerja != null ? date('d/m/Y', strtotime($employee->awal_bekerja)) : '' }}</td>
        <td>{{ get_posisi_name($employee->posisi) }}</td>
        <td>{{ get_kantor_name($employee->kantor) }}</td>
		@if(Auth::user()->role == role('admin'))
        <td>{{ get_perusahaan_name($employee->id_hrd) }}</td>
        @endif
	</tr>
	@endforeach
</table>