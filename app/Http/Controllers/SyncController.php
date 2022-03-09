<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class SyncController extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
		ini_set('max_execution_time ', '300');
		set_time_limit(300);
    }

    public function user()
    {
		$users = \App\Models\User::all();
		foreach($users as $user) {
			$user_attr = \App\Models\UserAttribute::where('user_id','=',$user->id)->first();
			if(!$user_attr) $user_attr = new \App\Models\UserAttribute;

			$user_attr->user_id = $user->id;
			$user_attr->birthdate = $user->tanggal_lahir != null ? $user->tanggal_lahir : '';
			$user_attr->gender = $user->jenis_kelamin != null ? $user->jenis_kelamin : '';
			$user_attr->country_code = 'ID';
			$user_attr->dial_code = '+62';
			$user_attr->phone_number = '';
			$user_attr->save();
		}
		var_dump(count($users));
    }

    public function applicant()
    {
		$applicants = \App\Models\Pelamar::all();
		foreach($applicants as $applicant) {
			$user_attr = \App\Models\UserAttribute::where('user_id','=',$applicant->id_user)->first();
			if($user_attr) {
                $vacancy = \App\Models\Lowongan::find($applicant->posisi);

				$user_attr->company_id = $applicant->id_hrd;
				$user_attr->office_id = 0;
				$user_attr->position_id = $vacancy ? $vacancy->posisi : 0;
				$user_attr->vacancy_id = $applicant->posisi;
				$user_attr->birthplace = $applicant->tempat_lahir;
				$user_attr->phone_number = $applicant->nomor_hp;
				$user_attr->identity_number = $applicant->nomor_ktp;
				$user_attr->address = $applicant->alamat;
				$user_attr->religion = $applicant->agama;
				$user_attr->relationship = $applicant->status_hubungan;
				$user_attr->latest_education = $applicant->pendidikan_terakhir;
				$user_attr->job_experience = $applicant->riwayat_pekerjaan;
				$user_attr->start_date = null;
				$user_attr->end_date = null;
				$user_attr->save();
			}
		}
		var_dump(count($applicants));
    }

    public function applicantAttachment()
    {
		$applicants = \App\Models\Pelamar::all();
		foreach($applicants as $applicant) {
            for($i=1; $i<=2; $i++) {
                $user_attachment = \App\Models\UserAttachment::where('user_id','=',$applicant->id_user)->where('attachment_id','=',$i)->first();
                if(!$user_attachment) $user_attachment = new \App\Models\UserAttachment;
                $user_attachment->user_id = $applicant->id_user;
                $user_attachment->attachment_id = $i;
                $user_attachment->file = ($i == 1) ? $applicant->pas_foto : $applicant->foto_ijazah;
                $user_attachment->save();
            }
		}
		var_dump(count($applicants));
    }

    public function applicantSocmed()
    {
		$applicants = \App\Models\Pelamar::all();
		foreach($applicants as $applicant) {
            $socmeds = json_decode($applicant->akun_sosmed, true);
            if(is_array($socmeds)) {
                foreach($socmeds as $key=>$value) {
                    $platform = 0;
                    if($key == 'Facebook') $platform = 1;
                    elseif($key == 'Instagram') $platform = 2;
                    elseif($key == 'YouTube') $platform = 3;
                    elseif($key == 'Twitter') $platform = 5;
                    elseif($key == 'LinkedIn') $platform = 6;

                    $user_socmed = \App\Models\UserSocmed::where('user_id','=',$applicant->id_user)->first();
                    if(!$user_socmed) $user_socmed = new \App\Models\UserSocmed;
                    $user_socmed->user_id = $applicant->id_user;
                    $user_socmed->platform = $platform;
                    $user_socmed->account = $value;
                    $user_socmed->save();
                }
            }
		}
		var_dump(count($applicants));
    }

    public function applicantGuardian()
    {
		$applicants = \App\Models\Pelamar::all();
		foreach($applicants as $applicant) {
            $guardian = json_decode($applicant->data_darurat, true);
            if(is_array($guardian)) {
                $user_guardian = \App\Models\UserGuardian::where('user_id','=',$applicant->id_user)->first();
                if(!$user_guardian) $user_guardian = new \App\Models\UserGuardian;
                $user_guardian->user_id = $applicant->id_user;
                $user_guardian->name = array_key_exists('nama_orang_tua', $guardian) ? $guardian['nama_orang_tua'] : '';
                $user_guardian->address = array_key_exists('alamat_orang_tua', $guardian) ? $guardian['alamat_orang_tua'] : '';
                $user_guardian->country_code = 'ID';
                $user_guardian->dial_code = '+62';
                $user_guardian->phone_number = array_key_exists('nomor_hp_orang_tua', $guardian) ? $guardian['nomor_hp_orang_tua'] : '';
                $user_guardian->occupation = array_key_exists('pekerjaan_orang_tua', $guardian) ? $guardian['pekerjaan_orang_tua'] : '';
                $user_guardian->save();
            }
		}
		var_dump(count($applicants));
    }

    public function applicantSkill()
    {
		$applicants = \App\Models\Pelamar::all();
		foreach($applicants as $applicant) {
            $skills = json_decode($applicant->keahlian, true);
            if(is_array($skills)) {
                foreach($skills as $skill) {
                    if($skill['jenis'] != null) {
                        $s = \App\Models\Skill::firstOrCreate(['name' => $skill['jenis']]); // Get or add skill

                        $user_skill = \App\Models\UserSkill::where('user_id','=',$applicant->id_user)->where('skill_id','=',$s->id)->first();
                        if(!$user_skill) $user_skill = new \App\Models\UserSkill;
                        $user_skill->user_id = $applicant->id_user;
                        $user_skill->skill_id = $s->id;
                        $user_skill->score = $skill['skor'];
                        $user_skill->save();
                    }
                }
            }
		}
		var_dump(count($applicants));
    }

    public function employee()
    {
		$employees = \App\Models\Karyawan::all();
		foreach($employees as $employee) {
			$user_attr = \App\Models\UserAttribute::where('user_id','=',$employee->id_user)->first();

			if($user_attr) {
				$user_attr->company_id = $employee->id_hrd;
				$user_attr->office_id = $employee->kantor;
				$user_attr->position_id = $employee->posisi;
				$user_attr->vacancy_id = 0;
				$user_attr->phone_number = $employee->nomor_hp;
				$user_attr->identity_number = $employee->nik;
				$user_attr->address = $employee->alamat;
				$user_attr->latest_education = $employee->pendidikan_terakhir;
				$user_attr->start_date = $employee->awal_bekerja;
				$user_attr->end_date = null;
				$user_attr->save();
			}
		}
		var_dump(count($employees));
    }

    public function internship()
    {
		$positions = [
			'Social Media Manager',
			'Content Writer',
			'Event Manager',
			'Creative and Design Manager',
			'Video Editor'
		];
		foreach($positions as $position) {
			$p = \App\Models\Position::where('company_id','=',1)->where('role_id','=',role('internship'))->where('name','=',$position)->first();
			if(!$p) $p = new \App\Models\Position;
			$p->company_id = 1;
			$p->role_id = role('internship');
			$p->name = $position;
			$p->save();
		}

		$users = \App\Models\User::where('role_id','=',role('internship'))->get();
		foreach($users as $user) {
			$user_attr = \App\Models\UserAttribute::where('user_id','=',$user->id)->first();

			if($user_attr) {
				$user_attr->company_id = 1;
				$user_attr->office_id = 0;
				$user_attr->position_id = 0;
				$user_attr->vacancy_id = 0;
				$user_attr->address = $user->avatar;
				$user_attr->start_date = null;
				$user_attr->end_date = null;
				$user_attr->save();

				if(array_key_exists($user->jenis_kelamin - 1, $positions)) {
					$p = \App\Models\Position::where('company_id','=',1)->where('role_id','=',role('internship'))->where('name','=',$positions[$user->jenis_kelamin - 1])->first();
					if($p) {
						$user_attr->position_id = $p->id;
						$user_attr->save();
					}
				}
			}
		}
		var_dump(count($users));
    }

    public function hrd()
    {
		$hrds = \App\Models\HRD::all();
		foreach($hrds as $hrd) {
			$user_attr = \App\Models\UserAttribute::where('user_id','=',$hrd->id_user)->first();

			if($user_attr) {
				$user_attr->company_id = $hrd->id_hrd;
				$user_attr->save();
			}
		}
		var_dump(count($hrds));
    }

    public function companyTest()
    {
		$hrds = \App\Models\HRD::all();
		foreach($hrds as $hrd) {
			$company = \App\Models\Company::find($hrd->id_hrd);
			$tests = explode(',', $hrd->akses_tes);

			if($company) {
				$company->tests()->sync($tests);
			}
		}
		var_dump(count($hrds));
    }

    public function positionTest()
    {
		$positions = \App\Models\Position::all();
		foreach($positions as $position) {
			if($position->tes != '') {
				$tests = explode(',', $position->tes);
				$position->tests()->sync($tests);
			}
		}
		var_dump(count($positions));
    }

    public function positionSkill()
    {
		$positions = \App\Models\Position::all();
		foreach($positions as $position) {
			if($position->keahlian != '') {
				$skills = explode(',', $position->keahlian);
				$skillArray = [];
				if(count($skills) > 0) {
					foreach($skills as $skill) {
                        $s = \App\Models\Skill::firstOrCreate(['name' => $skill]); // Get or add skill
						array_push($skillArray, $s->id);
					}
				}
				if(count($skillArray) > 0)
					$position->skills()->sync($skillArray);
			}
		}
		var_dump(count($positions));
    }

    public function selection()
    {
		$selections = \App\Models\Selection::all();
		foreach($selections as $selection) {
			$applicant = \App\Models\Pelamar::find($selection->id_pelamar);
			if($applicant) {
				$selection->user_id = $applicant->id_user;
				$selection->save();
			}
		}
		var_dump(count($selections));
    }
}