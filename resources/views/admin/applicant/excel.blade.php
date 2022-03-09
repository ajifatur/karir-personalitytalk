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
		@if(Auth::user()->role->is_global === 1)
		<td align="center" width="40" style="background-color: #f88315;"><strong>Perusahaan</strong></td>
		@endif
	</tr>
	@foreach($applicants as $key=>$applicant)
	<tr>
		<td>{{ ($key+1) }}</td>
        <td>{{ strtoupper($applicant->name) }}</td>
        <td>{{ $applicant->attribute->birthplace }}</td>
        <td>{{ $applicant->attribute->birthdate != null ? date('d/m/Y', strtotime($applicant->attribute->birthdate)) : '-' }}</td>
        <td>{{ gender($applicant->attribute->gender) }}</td>
        <td>{{ religion($applicant->attribute->religion) }}</td>
        <td>{{ $applicant->email }}</td>
        <td>{{ $applicant->attribute->phone_number }}</td>
        <td>{{ $applicant->attribute->address }}</td>
        <td>{{ $applicant->attribute->job_experience }}</td>
        <td>{{ $applicant->attribute->position->name }}</td>
		@if(Auth::user()->role->is_global === 1)
        <td>{{ $applicant->attribute->company->name }}</td>
		@endif
	</tr>
	@endforeach
</table>