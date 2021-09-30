<?php

// use Auth;
use App\HRD;
use App\TesSettings;

// Subdomain Tes Psikologanda
if(!function_exists('subdomain_tes')){
    function subdomain_tes(){
        return "https://tes.psikologanda.com/";
    }
}

// STIFIn access
if(!function_exists('stifin_access')){
    function stifin_access(){
        if(Auth::user()->role == role_admin())
            return true;
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            if($hrd){
                if($hrd->akses_stifin == 1) return true;
                else return false;
            }
            else return false;
        }
    }
}

// Tes settings
if(!function_exists('tes_settings')){
    function tes_settings($id_paket, $key){
        if(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            if($hrd){
                $value = TesSettings::where('id_hrd','=',$hrd->id_hrd)->where('id_paket','=',$id_paket)->pluck($key)->toArray();
                return array_key_exists(0, $value) ? $value[0] : '';
            }
            else return '';
        }
    }
}

// Get HRD tes
if(!function_exists('get_hrd_tes')){
    function get_hrd_tes(){
		$data = DB::table('hrd')->where('id_user','=',Auth::user()->id_user)->first();
        if(!$data) return [];
        else{
            if($data->akses_tes != ''){
                $akses_tes = explode(',', $data->akses_tes);
                $array = [];
                foreach($akses_tes as $id){
                    $tes = DB::table('tes')->where('id_tes','=',$id)->first();
                    if($tes) array_push($array, $tes->path);
                }
                return $array;
            }
            else return [];
        }
    }
}

// Role admin
if(!function_exists('role_admin')){
    function role_admin(){
        return 1;
    }
}

// Role HRD
if(!function_exists('role_hrd')){
    function role_hrd(){
        return 2;
    }
}

// Role karyawan
if(!function_exists('role_karyawan')){
    function role_karyawan(){
        return 3;
    }
}

// Role pelamar
if(!function_exists('role_pelamar')){
    function role_pelamar(){
        return 4;
    }
}

// Role umum
if(!function_exists('role_umum')){
    function role_umum(){
        return 5;
    }
}

// Role magang
if(!function_exists('role_magang')){
    function role_magang(){
        return 6;
    }
}

// Mencari index array multidimensional
if(!function_exists('searchIndex')){
    function searchIndex($array, $key, $value){
        for($i = 0; $i < count($array); $i++){
            if($array[$i][$key] == $value){
                return $i;
            }
        }
    }
}

// Get HRD
if(!function_exists('get_hrd')){
    function get_hrd(){
		$data = DB::table('hrd')->get();
		return $data;
    }
}

// Get Data Tes
if(!function_exists('get_data_tes')){
    function get_data_tes(){
        $data = DB::table('tes')->orderBy('nama_tes','asc')->get();
        return $data;
    }
}

// Get Data Update
if(!function_exists('get_data_update')){
    function get_data_update(){
        $data = DB::table('update_sistem')->orderBy('update_at','desc')->get();
        return $data;
    }
}

// Get User HRD
if(!function_exists('get_user_hrd')){
    function get_user_hrd(){
        if(Auth::user()->role == role_hrd()){
            $data = DB::table('hrd')->where('id_user','=',Auth::user()->id_user)->first();
            return $data->id_hrd;
        }
        return null;
    }
}

// Get nama role
if(!function_exists('get_role_name')){
    function get_role_name($id){
		$data = DB::table('role')->where('id_role','=',$id)->first();
		return $data ? $data->nama_role : '-';
    }
}

// Get nama posisi
if(!function_exists('get_posisi_name')){
    function get_posisi_name($id){
		$data = DB::table('posisi')->where('id_posisi','=',$id)->first();
		return $data ? $data->nama_posisi : '-';
    }
}

// Get nama kantor
if(!function_exists('get_kantor_name')){
    function get_kantor_name($id){
		$data = DB::table('kantor')->where('id_kantor','=',$id)->first();
		return $data ? $data->nama_kantor : '-';
    }
}

// Get nama perusahaan
if(!function_exists('get_perusahaan_name')){
    function get_perusahaan_name($id){
		$data = DB::table('hrd')->where('id_hrd','=',$id)->first();
		return $data ? $data->perusahaan : '-';
    }
}

// Get nama HRD
if(!function_exists('get_hrd_name')){
    function get_hrd_name($id){
        $data = DB::table('hrd')->where('id_hrd','=',$id)->first();
        return $data ? $data->nama_lengkap : '-';
    }
}

// Get id posisi
if(!function_exists('get_posisi_id')){
    function get_posisi_id($hrd, $name){
        $data = DB::table('posisi')->where('id_hrd','=',$hrd)->where('nama_posisi','=',$name)->first();
        return $data ? $data->id_posisi : 0;
    }
}

// Get id kantor
if(!function_exists('get_kantor_id')){
    function get_kantor_id($hrd, $name){
        $data = DB::table('kantor')->where('id_hrd','=',$hrd)->where('nama_kantor','=',$name)->first();
        return $data ? $data->id_kantor : 0;
    }
}

// Get perusahaan tes
if(!function_exists('get_perusahaan_tes')){
    function get_perusahaan_tes($id){
		$data = DB::table('hrd')->where('id_hrd','=',$id)->first();
        if(!$data) return null;
        else{
            if($data->akses_tes != ''){
                $akses_tes = explode(',', $data->akses_tes);
                $array = [];
                foreach($akses_tes as $id){
                    $tes = DB::table('tes')->where('id_tes','=',$id)->first();
                    array_push($array, $tes);
                }
                return $array;
            }
            else return null;
        }
    }
}

// Menghitung jumlah data duplikat
if(!function_exists('count_existing_data')){
    function count_existing_data($table, $field, $keyword, $primaryKey, $id = null){
        if($id == null) $data = DB::table($table)->where($field,'=',$keyword)->get();
        else $data = DB::table($table)->where($field,'=',$keyword)->where($primaryKey,'!=',$id)->get();
        return count($data);
    }
}

// Menghitung jumlah kantor berdasarkan perusahaan
if(!function_exists('count_kantor_by_perusahaan')){
    function count_kantor_by_perusahaan($id){
        $data = DB::table('kantor')->where('id_hrd','=',$id)->count();
        return $data;
    }
}

// Menghitung jumlah jabatan berdasarkan perusahaan
if(!function_exists('count_jabatan_by_perusahaan')){
    function count_jabatan_by_perusahaan($id){
        $data = DB::table('posisi')->where('id_hrd','=',$id)->count();
        return $data;
    }
}

// Menghitung jumlah karyawan berdasarkan perusahaan
if(!function_exists('count_karyawan_by_perusahaan')){
    function count_karyawan_by_perusahaan($id){
        $data = DB::table('karyawan')->join('users','karyawan.id_user','=','users.id_user')->where('id_hrd','=',$id)->where('status','=',1)->count();
        return $data;
    }
}

// Menghitung jumlah karyawan berdasarkan kantor
if(!function_exists('count_karyawan_by_kantor')){
    function count_karyawan_by_kantor($id){
        $data = DB::table('karyawan')->join('users','karyawan.id_user','=','users.id_user')->where('kantor','=',$id)->where('status','=',1)->count();
        return $data;
    }
}

// Menghitung jumlah karyawan berdasarkan jabatan
if(!function_exists('count_karyawan_by_jabatan')){
    function count_karyawan_by_jabatan($id){
		$data = DB::table('karyawan')->join('users','karyawan.id_user','=','users.id_user')->where('posisi','=',$id)->where('status','=',1)->count();
		return $data;
    }
}

// Menghitung jumlah pelamar berdasarkan perusahaan
if(!function_exists('count_pelamar_by_perusahaan')){
    function count_pelamar_by_perusahaan($id){
        $data = DB::table('pelamar')->where('id_hrd','=',$id)->count();
        return $data;
    }
}

// Menghitung jumlah pelamar belum diseleksi berdasarkan lowongan
if(!function_exists('count_pelamar_belum_diseleksi_by_lowongan')){
    function count_pelamar_belum_diseleksi_by_lowongan($id){
        if(Auth::user()->role == role_admin())
            $pelamar = DB::table('pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('id_lowongan','=',$id)->get();
        elseif(Auth::user()->role == role_hrd())
            $pelamar = DB::table('pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('pelamar.id_hrd','=',get_user_hrd())->where('id_lowongan','=',$id)->get();

        $count = 0;
        if(count($pelamar)>0){
            foreach($pelamar as $data){
                $seleksi = DB::table('seleksi')->where('id_pelamar','=',$data->id_pelamar)->where('id_lowongan','=',$id)->first();
                if(!$seleksi) $count++;
            }
        }

        return $count;
    }
}

// Menghitung jumlah pelamar belum dites berdasarkan lowongan
if(!function_exists('count_pelamar_belum_dites_by_lowongan')){
    function count_pelamar_belum_dites_by_lowongan($id){
        if(Auth::user()->role == role_admin())
            $data = DB::table('seleksi')->join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('lowongan.id_lowongan','=',$id)->where('seleksi.hasil','=',99)->count();
        elseif(Auth::user()->role == role_hrd())
            $data = DB::table('seleksi')->join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('lowongan.id_lowongan','=',$id)->where('seleksi.hasil','=',99)->where('seleksi.id_hrd','=',get_user_hrd())->count();
        return $data;
    }
}

// Menghitung jumlah pelamar belum diseleksi
if(!function_exists('count_pelamar_belum_diseleksi')){
    function count_pelamar_belum_diseleksi(){
        if(Auth::user()->role == role_admin())
            $pelamar = DB::table('pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->get();
        elseif(Auth::user()->role == role_hrd())
            $pelamar = DB::table('pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('pelamar.id_hrd','=',get_user_hrd())->get();

        $count = 0;
        if(count($pelamar)>0){
            foreach($pelamar as $data){
                $seleksi = DB::table('seleksi')->where('id_pelamar','=',$data->id_pelamar)->first();
                if(!$seleksi) $count++;
            }
        }

        return $count;
    }
}

// Menghitung jumlah pelamar belum dites
if(!function_exists('count_pelamar_belum_dites')){
    function count_pelamar_belum_dites(){
        if(Auth::user()->role == role_admin())
            $data = DB::table('seleksi')->join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.hasil','=',99)->count();
        elseif(Auth::user()->role == role_hrd())
            $data = DB::table('seleksi')->join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.hasil','=',99)->where('seleksi.id_hrd','=',get_user_hrd())->count();
        return $data;
    }
}

// Pesan validasi form
if(!function_exists('validationMessages')){
    function validationMessages(){
        // Pesan Error
        $messages = [
            'required' => 'wajib diisi.',
            'numeric' => 'wajib dengan nomor atau angka.',
            'unique' => 'sudah ada.',
            'min' => 'harus diisi minimal :min karakter.',
            'max' => 'harus diisi maksimal :max karakter.',
            'alpha' => 'hanya bisa diisi dengan huruf.',
        ];
        
        return $messages;
    }
}

// Set tanggal lengkap
if(!function_exists('setFullDate')){
    function setFullDate($date){
        $explode1 = explode(" ", $date);
        $explode2 = explode("-", $explode1[0]);
        $month = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
        return $explode2[2]." ".$month[$explode2[1]-1]." ".$explode2[0];
    }
}
	
// Menghapus array yang bervalue kosong
if(!function_exists('removeEmptyArray')){
    function removeEmptyArray($array, $key = null){
        if($key == null){
            $array_count_values = array_count_values($array);
            if($array_count_values[""] == count($array)){
                unset($array);
            }
        }
        else{
            $array_count_values = array_count_values($array[$key]);
            if($array_count_values[""] == count($array[$key])){
                unset($array[$key]);
            }
        }
    }
}

// Mengganti nama permalink jika ada yang sama
if(!function_exists('rename_permalink')){
    function rename_permalink($permalink, $count){
        return $permalink."-".($count+1);
    }
}

// Generate string ke url
if(!function_exists('generate_url')){
    function generate_url($string){
        $url = trim($string);
        $url = strtolower($url);
        $url = str_replace(" ", "-", $url);
        return $url;
    }
}

// Generate permalink
if(!function_exists('generate_permalink')){
    function generate_permalink($string){
        $result = strtolower($string);
        $result = preg_replace("/[^a-z0-9\s-]/", "", $result);
        $result = preg_replace("/\s+/", " ",$result);
        $result = str_replace(" ", "-", $result);
        return $result;
    }
}

// Hitung umur / usia
if(!function_exists('generate_age')){
    function generate_age($dateFrom, $dateTo = 'today'){
        $from = new DateTime($dateFrom);
        $to = new DateTime($dateTo);
        $y = $to->diff($from)->y;
        
        return $y;
    }
}

// Diff date
if(!function_exists('diff_date')){
    function diff_date($dateFrom, $dateTo){
        $from = new DateTime($dateFrom);
        $to = new DateTime($dateTo);
        $y = $to->diff($from)->y;
        
        return $y;
    }
}

// Generate tanggal
if(!function_exists('generate_date')){
    function generate_date($date){
        $explode1 = explode(" ", $date);
        $explode2 = explode("-", $explode1[0]);
        $month = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
        return $explode2[2]." ".$month[$explode2[1]-1]." ".$explode2[0];
    }
}

// Generate format tanggal
if(!function_exists('generate_date_format')){
    function generate_date_format($date, $format){
        if($format == 'd/m/y'){
            $explode = explode("-", $date);
            return count($explode) == 3 ? $explode[2].'/'.$explode[1].'/'.$explode[0] : '';
        }
        elseif($format == 'y-m-d'){
            $explode = explode("/", $date);
            return count($explode) == 3 ? $explode[2].'-'.$explode[1].'-'.$explode[0] : '';
        }
        else
            return '';
    }
}

// Generate username
if(!function_exists('generate_username')){
    function generate_username($username = null, $prefix){
        if($username != null){
            if(substr($username,3,5) === "00000"){
                $affix = (int)substr($username,8);
            }
            elseif(substr($username,3,4) === "0000"){
                $affix = (int)substr($username,7);
            }
            elseif(substr($username,3,3) === "000"){
                $affix = (int)substr($username,6);
            }
            elseif(substr($username,3,2) === "00"){
                $affix = (int)substr($username,5);
            }
            elseif(substr($username,3,1) === "0"){
                $affix = (int)substr($username,4);
            }
            else{
                $affix = (int)substr($username,3);
            }
    
            // Max 999.999
            if($affix + 1 >= 0 && $affix + 1 < 10)
                $username = $prefix."00000".($affix + 1);
            elseif($affix + 1 >= 10 && $affix + 1 < 100)
                $username = $prefix."0000".($affix + 1);
            elseif($affix + 1 >= 100 && $affix + 1 < 1000)
                $username = $prefix."000".($affix + 1);
            elseif($affix + 1 >= 1000 && $affix + 1 < 10000)
                $username = $prefix."00".($affix + 1);
            elseif($affix + 1 >= 10000 && $affix + 1 < 100000)
                $username = $prefix."0".($affix + 1);
            elseif($affix + 1 >= 100000 && $affix + 1 < 1000000)
                $username = $prefix.($affix + 1);
        }
        else{
            $username = $prefix."000001";
        }
        return $username;
    }
}

// Generate string that be able to read by url...
if(!function_exists('generate_path_url')){
	function generate_path_url($string){
		// Convert string to lowercase...
		$result = strtolower($string);
		// Only accept letters, numbers, and whitespaces...
		$result = preg_replace("/[^a-z0-9\s]/", "", $result);
		// Remove double whitespaces and make it into one whitespace only...
		$result = preg_replace("/\s+/", " ",$result);
		// Replace whitespaces to "-" characters...
		$result = str_replace(" ", "-", $result);
		// Return the result...
		return $result;
	}
}

// Acak huruf
if(!function_exists('shuffleString')){
    function shuffleString($length){
        $string = '1234567890QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm';
        $shuffle = substr(str_shuffle($string), 0, $length);
        return $shuffle;
    }
}

// Mengganti key pada json pelamar
if(!function_exists('replaceJsonKey')){
    function replaceJsonKey($string){
        $string = str_replace('_', ' ', $string);
        $string = str_replace('hp', 'HP', $string);
        $string = ucwords($string);
        return $string;
    }
}

if(!function_exists('time_elapsed_string')){
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'tahun',
            'm' => 'bulan',
            'w' => 'minggu',
            'd' => 'hari',
            'h' => 'jam',
            'i' => 'menit',
            's' => 'detik',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                // $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' yang lalu' : 'Baru saja';
    }
}

/* DISC 40 SOAL */
/* ======================================================================= */

// Scoring DISC MOST
if(!function_exists('discScoringM')){
    function discScoringM($number){
        $score = round(50 * pow(2, log($number / 10, 4)));
        return $score;
    }
}

// Scoring DISC LEAST
if(!function_exists('discScoringL')){
    function discScoringL($number){
        $score = 100 - round(50 * pow(2, log($number / 10, 4)));
        return $score;
    }
}

// Meranking score
if(!function_exists('sortScore')){
    function sortScore($array){
        $ordered_array = $array;
        arsort($ordered_array);
        $i = 1;
        $last_value = '';
        foreach($ordered_array as $ordered_key=>$ordered_value){
            $ordered_array[$ordered_key] = array();
            $ordered_array[$ordered_key]['rank'] = $ordered_value == $last_value ? ($i-1) : $i;
            $ordered_array[$ordered_key]['score'] = $ordered_value;
            $last_value = $ordered_value;
            $i++;
        }
        return $ordered_array;
    }
}

// Membuat kode
if(!function_exists('setCode')){
    function setCode($array){
        $new_array = array();
        $i = 1;
        while($i<=4){
            foreach($array as $key=>$value){
                if($array[$key]['rank'] == $i){
                    if($array[$key]['score'] < 50){
                        $new_value = "L".$key;
                        array_push($new_array, $new_value);
                    }
                    else{
                        $new_value = "H".$key;
                        array_push($new_array, $new_value);
                    }
                }
            }
            $i++;
        }
        return $new_array;
    }
}

/* DISC 24 SOAL */
/* ======================================================================= */

if(!function_exists('analisis_disc_24')){
	function analisis_disc_24($x, $d, $i, $s, $c){
        if($x == 1) return ($d <= 0 && $i <= 0 && $s <= 0 && $c > 0) ? 1 : 0;
        elseif($x == 2) return ($d > 0 && $i <= 0 && $s <= 0 && $c <= 0) ? 1 : 0;
        elseif($x == 3) return ($d > 0 && $i <= 0 && $s <= 0 && $c > 0 && $c >= $d) ? 1 : 0;
        elseif($x == 4) return ($d > 0 && $i > 0 && $s <= 0 && $c <= 0 && $i >= $d) ? 1 : 0;
        elseif($x == 5) return ($d > 0 && $i > 0 && $s < $c && $i && $d && $c > 0 && $i >= $d && $d >= $c) ? 1 : 0;
        elseif($x == 6) return ($d > 0 && $i > 0 && $s > 0 && $c < $i && $d && $s && $i >= $d && $d >= $s) ? 1 : 0;
        elseif($x == 7) return ($d > 0 && $i > 0 && $s > 0 && $c < $i && $d && $s && $i >= $s && $s >= $d) ? 1 : 0;
        elseif($x == 8) return ($d > 0 && $i <= 0 && $s > 0 && $c > 0 && $s >= $d && $d >= $c) ? 1 : 0;
        elseif($x == 9) return ($d > 0 && $i > 0 && $s <= 0 && $c <= 0 && $d >= $i) ? 1 : 0;
        elseif($x == 10) return ($d > 0 && $i > 0 && $s > 0 && $c < $i && $d && $s && $d >= $i && $i >= $s) ? 1 : 0;
        elseif($x == 11) return ($d > 0 && $i <= 0 && $s > 0 && $c <= 0 && $d >= $s) ? 1 : 0;
        elseif($x == 12) return ($d <= 0 && $i > 0 && $s > 0 && $c > 0 && $c >= $i && $i >= $s) ? 1 : 0;
        elseif($x == 13) return ($d <= 0 && $i > 0 && $s > 0 && $c > 0 && $c >= $s && $s >= $i) ? 1 : 0;
        elseif($x == 14) return ($d <= 0 && $i > 0 && $s > 0 && $c > 0 && $i >= $s && $i >= $c) ? 1 : 0;
        elseif($x == 15) return ($d <= 0 && $i <= 0 && $s > 0 && $c <= 0) ? 1 : 0;
        elseif($x == 16) return ($d <= 0 && $i <= 0 && $s > 0 && $c > 0 && $c >= $s) ? 1 : 0;
        elseif($x == 17) return ($d <= 0 && $i <= 0 && $s > 0 && $c > 0 && $s >= $c) ? 1 : 0;
        elseif($x == 18) return ($d > 0 && $i <= 0 && $s <= 0 && $c > 0 && $d >= $c) ? 1 : 0;
        elseif($x == 19) return ($d > 0 && $i > 0 && $c > 0 && $s < $c && $i && $d && $d >= $i && $i >= $c) ? 1 : 0;
        elseif($x == 20) return ($d > 0 && $i > 0 && $s > 0 && $c < $i && $d && $s && $d >= $s && $s >= $i) ? 1 : 0;
        elseif($x == 21) return ($d > 0 && $i <= 0 && $s > 0 && $c > 0 && $d >= $s && $s >= $c) ? 1 : 0;
        elseif($x == 22) return ($d > 0 && $i > 0 && $c > 0 && $s < $c && $i && $d && $d >= $c && $c >= $i) ? 1 : 0;
        elseif($x == 23) return ($d > 0 && $i <= 0 && $s > 0 && $c > 0 && $d >= $c && $c >= $i) ? 1 : 0;
        elseif($x == 24) return ($d <= 0 && $i > 0 && $s <= 0 && $c <= 0) ? 1 : 0;
        elseif($x == 25) return ($d <= 0 && $i > 0 && $s > 0 && $c <= 0 && $i >= $s) ? 1 : 0;
        elseif($x == 26) return ($d <= 0 && $i > 0 && $s <= 0 && $c > 0 && $i >= $c) ? 1 : 0;
        elseif($x == 27) return ($d > 0 && $i > 0 && $c > 0 && $s < $c && $i && $d && $i >= $c && $c >= $d) ? 1 : 0;
        elseif($x == 28) return ($d <= 0 && $i > 0 && $s > 0 && $c < 0 && $i >= $c && $c >= $s) ? 1 : 0;
        elseif($x == 29) return ($d > 0 && $i <= 0 && $s > 0 && $c <= 0 && $s >= $d) ? 1 : 0;
        elseif($x == 30) return ($d <= 0 && $i > 0 && $s > 0 && $c <= 0 && $s >= $i) ? 1 : 0;
        elseif($x == 31) return ($d > 0 && $i > 0 && $s > 0 && $c < $i && $d && $s && $s >= $d && $d >= $i) ? 1 : 0;
        elseif($x == 32) return ($d > 0 && $i > 0 && $s > 0 && $c < $i && $d && $s && $s >= $i && $i >= $d) ? 1 : 0;
        elseif($x == 33) return ($d <= 0 && $i > 0 && $s > 0 && $c > 0 && $s >= $i && $i >= $c) ? 1 : 0;
        elseif($x == 34) return ($d > 0 && $i <= 0 && $s > 0 && $c > 0 && $s >= $c && $c >= $d) ? 1 : 0;
        elseif($x == 35) return ($d <= 0 && $i > 0 && $s > 0 && $c > 0 && $s >= $c && $c >= $i) ? 1 : 0;
        elseif($x == 36) return ($d <= 0 && $i > 0 && $s <= 0 && $c > 0 && $c >= $i) ? 1 : 0;
        elseif($x == 37) return ($d > 0 && $i > 0 && $c > 0 && $s < $c && $i && $d && $c >= $d && $d >= $i) ? 1 : 0;
        elseif($x == 38) return ($d > 0 && $s > 0 && $c > 0 && $i < $c && $s && $d && $c >= $d && $d >= $s) ? 1 : 0;
        elseif($x == 39) return ($d > 0 && $i > 0 && $c > 0 && $s < $c && $i && $d && $c >= $i && $i >= $d) ? 1 : 0;
        elseif($x == 40) return ($d > 0 && $s > 0 && $c > 0 && $i < $c && $s && $d && $c >= $s && $s >= $d) ? 1 : 0;
    }
}

/* PAPIKOSTICK */
/* ======================================================================= */

// Menghapus array yang bervalue kosong
if(!function_exists('analisisPapikostick')){
	function analisisPapikostick($jawaban, $array){
	    // Menghitung jumlah if else
		$count = count($array);

	    	// Jika jumlah if else 2
		if($count == 2){
			if($jawaban <= $array[0]["syarat"]) return $array[0]["deskripsi"];
			else return $array[1]["deskripsi"];
		}
	    	// Jika jumlah if else 3
		elseif($count == 3){
			if($jawaban <= $array[0]["syarat"]) return $array[0]["deskripsi"];
			elseif($jawaban <= $array[1]["syarat"]) return $array[1]["deskripsi"];
			else return $array[2]["deskripsi"];
		}
	    	// Jika jumlah if else 4
		elseif($count == 4){
			if($jawaban <= $array[0]["syarat"]) return $array[0]["deskripsi"];
			elseif($jawaban <= $array[1]["syarat"]) return $array[1]["deskripsi"];
			elseif($jawaban <= $array[2]["syarat"]) return $array[2]["deskripsi"];
			else return $array[3]["deskripsi"];
		}
			//Jika jumlah if else 5
		elseif($count == 5){
			if($jawaban <= $array[0]["syarat"]) return $array[0]["deskripsi"];
			elseif($jawaban <= $array[1]["syarat"]) return $array[1]["deskripsi"];
			elseif($jawaban <= $array[2]["syarat"]) return $array[2]["deskripsi"];
			elseif($jawaban <= $array[3]["syarat"]) return $array[3]["deskripsi"];
			else return $array[4]["deskripsi"];
		}
	}
}