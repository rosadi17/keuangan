<?php

function pembulatan_seratus($angka) {
    $kelipatan = 100;
    $sisa = $angka % $kelipatan;
    if ($sisa != 0) {
        $kekurangan = $kelipatan - $sisa;
        $hasilBulat = $angka + $kekurangan;
        return ceil($hasilBulat);
    } else {
        return ceil($angka);
    }
}

function inttocur($jml) {
    $int = number_format($jml, 0, '', '.');
    return $int;
}

function rupiah($jml) {
    $int = number_format(ceil($jml), 0, '', '.');
    return $int;
}

function rupiah2($jml) {
    $int = number_format($jml, 0, '', '.');
    return $int;
}

function get_date_from_dt($dt) {
    $var = explode(" ", $dt);
    return $var[0];
}

function get_day($date){
    $datetime = new DateTime($date);
    $day = $datetime->format('N');
    $hari = '';
    switch ($day) {
        case '1': $hari = 'Senin'; break;
        case '2': $hari = 'Selasa'; break;
        case '3': $hari = 'Rabu'; break;
        case '4': $hari = 'Kamis'; break;
        case '5': $hari = 'Jumat'; break;
        case '6': $hari = 'Sabtu'; break;
        case '7': $hari = 'Minggu'; break;
        
        default:
            # code...
            break;
    }

    return $hari;
}

function get_date_format($date){
     $datetime = new DateTime($date);
     $month = $datetime->format('m');
     $bulan = '';
     switch ($month) {
        case '01': $bulan = 'Januari'; break;
        case '02': $bulan = 'Februari'; break;
        case '03': $bulan = 'Maret'; break;
        case '04': $bulan = 'April'; break;
        case '05': $bulan = 'Mei'; break;
        case '06': $bulan = 'Juni'; break;
        case '07': $bulan = 'Juli'; break;
        case '08': $bulan = 'Agustus'; break;
        case '09': $bulan = 'September'; break;
        case '10': $bulan = 'Oktober'; break;
        case '11': $bulan = 'November'; break;
        case '12': $bulan = 'Desember'; break;
        
        default:
            # code...
            break;
        }
     
     return $datetime->format('d')." ".$bulan." ".$datetime->format('Y');
}

function datetime($dt) {
    if ($dt != NULL and $dt != '0000-00-00 00:00:00') {
        $var = explode(" ", $dt);
        $var1 = explode("-", $var[0]);
        $var2 = "$var1[2]/$var1[1]/$var1[0]";
        return $var2 . " " . substr($var[1], 0, 5);
    } else {
        return '-';
    }
}

function datetimefmysql($dt, $time = NULL) {
    $var = explode(" ", $dt);
    $var1 = explode("-", $var[0]);
    $var2 = "$var1[2]/$var1[1]/$var1[0]";
    if ($time != NULL) {
        return $var2 . ' ' . $var[1];
    } else {
        return $var2;
    }
}

function datetime2mysql($dt) {
    $var = explode(" ", $dt);
    $var1 = explode("/", $var[0]);
    $var2 = "$var1[2]-$var1[1]-$var1[0]";

    return $var2 . " " . $var[1];
}

function datetimetomysql($dt) {
    // $dt = 2013-03-06 00:00:00
    $var = explode(" ", $dt);
    $date = explode("-", $var[0]);
    $time = explode(":", $var[1]);

    return $date[2] . "/" . $date[1] . "/" . $date[0] . " " . $time[0] . ":" . $time[1];
}

function dateconvert($tgl) {
    $new = explode('-', $tgl);
    if ($new[1] == '01') {
        $month = 'Januari';
    }
    if ($new[1] == '02') {
        $month = 'Februari';
    }
    if ($new[1] == '03') {
        $month = 'Maret';
    }
    if ($new[1] == '04') {
        $month = 'April';
    }
    if ($new[1] == '05') {
        $month = 'Mei';
    }
    if ($new[1] == '06') {
        $month = 'Juni';
    }
    if ($new[1] == '07') {
        $month = 'Juli';
    }
    if ($new[1] == '08') {
        $month = 'Agustus';
    }
    if ($new[1] == '09') {
        $month = 'September';
    }
    if ($new[1] == '10') {
        $month = 'Oktober';
    }
    if ($new[1] == '11') {
        $month = 'November';
    }
    if ($new[1] == '12') {
        $month = 'Desember';
    }
    return $new[2] . " " . $month . " " . $new[0];
}

function indo_tgl($tgl) {
    $baru = explode("-", $tgl);
    if ($baru[1] == '01')
        $mo = "Januari";
    if ($baru[1] == '02')
        $mo = "Februari";
    if ($baru[1] == '03')
        $mo = "Maret";
    if ($baru[1] == '04')
        $mo = "April";
    if ($baru[1] == '05')
        $mo = "Mei";
    if ($baru[1] == '06')
        $mo = "Juni";
    if ($baru[1] == '07')
        $mo = "Juli";
    if ($baru[1] == '08')
        $mo = "Agustus";
    if ($baru[1] == '09')
        $mo = "September";
    if ($baru[1] == '10')
        $mo = "Oktober";
    if ($baru[1] == '11')
        $mo = "November";
    if ($baru[1] == '12')
        $mo = "Desember";
    $new = "$baru[2] $mo $baru[0]";

    return $new;
}

function indo_tgl_graph($tgl) {
    $baru = explode("-", $tgl);
    if ($baru[1] == '01')
        $mo = "Jan";
    if ($baru[1] == '02')
        $mo = "Feb";
    if ($baru[1] == '03')
        $mo = "Mar";
    if ($baru[1] == '04')
        $mo = "Apr";
    if ($baru[1] == '05')
        $mo = "Mei";
    if ($baru[1] == '06')
        $mo = "Jun";
    if ($baru[1] == '07')
        $mo = "Jul";
    if ($baru[1] == '08')
        $mo = "Agu";
    if ($baru[1] == '09')
        $mo = "Sep";
    if ($baru[1] == '10')
        $mo = "Okt";
    if ($baru[1] == '11')
        $mo = "Nov";
    if ($baru[1] == '12')
        $mo = "Des";
    $new = "$baru[2] $mo";

    return $new;
}

function tampil_bulan($tgl) {
    $tgl = explode('-', $tgl);
    if ($tgl[1] == '01')
        $mo = "Januari";
    if ($tgl[1] == '02')
        $mo = "Februari";
    if ($tgl[1] == '03')
        $mo = "Maret";
    if ($tgl[1] == '04')
        $mo = "April";
    if ($tgl[1] == '05')
        $mo = "Mei";
    if ($tgl[1] == '06')
        $mo = "Juni";
    if ($tgl[1] == '07')
        $mo = "Juli";
    if ($tgl[1] == '08')
        $mo = "Agustus";
    if ($tgl[1] == '09')
        $mo = "September";
    if ($tgl[1] == '10')
        $mo = "Oktober";
    if ($tgl[1] == '11')
        $mo = "November";
    if ($tgl[1] == '12')
        $mo = "Desember";

    return $mo;
}

function datetopg($tgl) {
    $new = null;
    $tgl = explode("/", $tgl);
    if (empty($tgl[2]))
        return "";
    $new = "$tgl[2]-$tgl[1]-$tgl[0]";
    return $new;
}

function date2mysql($tgl) {
    $new = null;
    $tgl = explode("/", $tgl);
    if (empty($tgl[2]))
        return "";
    $new = "$tgl[2]-$tgl[1]-$tgl[0]";
    return $new;
}

function datefmysql($tgl) {
    if ($tgl == '' || $tgl == null) {
        return "";
    } else {
        $tgl = explode("-", $tgl);
        $new = $tgl[2] . "/" . $tgl[1] . "/" . $tgl[0];
        return $new;
    }
}

function datefrompg($tgl) {
    if ($tgl == '' || $tgl == null) {
        return "";
    } else {
        $tgl = explode("-", $tgl);
        $new = $tgl[2] . "/" . $tgl[1] . "/" . $tgl[0];
        return $new;
    }
}

function createUmur($tgl1) {

    $tgl2 = date("Y-m-d");
    $sql = mysql_query("select datediff('$tgl2', '$tgl1') as tahun");
    $rows = mysql_fetch_array($sql);
    return floor($rows['tahun'] / 365);
}

function hitungUmur($tgl) {
    $tanggal = explode("-", $tgl);
    $tahun = $tanggal[0];
    $bulan = $tanggal[1];
    $hari = $tanggal[2];
    
    if ($tahun != '0000') {
    
        $day = date('d');
        $month = date('m');
        $year = date('Y');

        $tahun = $year - $tahun;
        $bulan = $month - $bulan;
        $hari = $day - $hari;

        $jumlahHari = 0;
        $bulanTemp = ($month == 1) ? 12 : $month - 1;
        if ($bulanTemp == 1 || $bulanTemp == 3 || $bulanTemp == 5 || $bulanTemp == 7 || $bulanTemp == 8 || $bulanTemp == 10 || $bulanTemp == 12) {
            $jumlahHari = 31;
        } else if ($bulanTemp == 2) {
            if ($tahun % 4 == 0)
                $jumlahHari = 29;
            else
                $jumlahHari = 28;
        }else {
            $jumlahHari = 30;
        }

        if ($hari <= 0) {
            $hari+=$jumlahHari;
            $bulan--;
        }
        if ($bulan < 0 || ($bulan == 0 && $tahun != 0)) {
            $bulan+=12;
            $tahun--;
        }
        $result = $tahun . " Tahun " . $bulan . " Bulan " . $hari . " Hari";
    } else {
        $result = "-";
    }
    return $result;
}

function currencyToNumber($a) {
    return str_ireplace(".", "", $a);
}

function int_to_money($nominal) {
    return number_format($nominal, 0, '', '.');
}

function get_umur($tgl_lahir) {
    $tglawal = date('Y');  // Format: Tanggal/Bulan/Tahun -> 12 Desember 2010
    $year1 = explode('-', $tgl_lahir);
    $selisih = $tglawal - $year1[0];
    return $selisih;
}

function paging($jmldata, $dataPerPage, $tab = NULL) {

    $showPage = NULL;
    ob_start();
    echo "
        <div class='body-page'>";
    if (!empty($_GET['page'])) {
        $noPage = $_GET['page'];
    } else {
        $noPage = 1;
    }

    $dataPerPage = $dataPerPage;
    $offset = ($noPage - 1) * $dataPerPage;


    $jumData = $jmldata;
    $jumPage = ceil($jumData / $dataPerPage);
    $get = $_GET;
    if ($jumData > $dataPerPage) {
        $onclick = null;
        if ($noPage > 1) {
            $get['page'] = ($noPage - 1);
            $onclick = "onClick=location.href='?" . generate_get_parameter($get) . "'";
        }
        echo "<span class='page-prev' $onclick>prev</span>";
        for ($page = 1; $page <= $jumPage; $page++) {
            if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) {
                if (($showPage == 1) && ($page != 2))
                    echo "...";
                if (($showPage != ($jumPage - 1)) && ($page == $jumPage))
                    echo "...";
                if ($page == $noPage)
                    echo " <span class='noblock'>" . $page . "</span> ";
                else {
                    $get['page'] = $page;

                    if ($tab != NULL) {
                        $get['tab'] = $tab;
                    }

                    echo " <a class='block' href='?" . generate_get_parameter($get) . "'>" . $page . "</a> ";
                }
                $showPage = $page;
            }
        }
        $onClick = null;
        if ($noPage < $jumPage) {
            $get['page'] = ($noPage + 1);
            $onClick = "onClick=location.href='?" . generate_get_parameter($get) . "'";
        }
        echo "<span class='page-next' $onClick>next</span>";
    }
    echo "</div>";

    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}

function generate_get_parameter($get, $addArr = array(), $removeArr = array()) {
    if ($addArr == null)
        $addArr = array();
    foreach ($removeArr as $rm) {
        unset($get[$rm]);
    }
    $link = "";
    $get = array_merge($get, $addArr);
    foreach ($get as $key => $val) {
        if ($link == null) {
            $link.="$key=$val";
        }else
            $link.="&$key=$val";
    }
    return $link;
}

function form_type_button($value = null, $attr = null) {
    $val = null;
    if ($value != '') {
        $val = $value;
    }
    $atrib = null;
    if ($attr != null) {
        $atrib = $attr;
    }

    return '<input type="button" value="' . $val . '" "' . $atrib . '" />';
}

function get_duration($date1, $date2) {
    $date1 = new DateTime($date1);
    $date2 = new DateTime($date2);
    $durasi = $date1->diff($date2);
    return array('day' => $durasi->d, 'hour' => $durasi->h, 'minute'=>$durasi->i);
}

function get_last_id($table, $kolom) {
    $CI = & get_instance();
    $adding = 0;
    if ($table === 'rekening') {
        $adding = 100000;
    } else if ($table === 'sub_rekening') {
        $adding = 10000;
    } else if ($table === 'sub_sub_rekening') {
        $adding = 1000;
    } else if ($table === 'sub_sub_sub_rekening') {
        $adding = 100;
    }
    
    $sql = "select max($kolom)+$adding as id from $table";
    $id = $CI->db->query($sql)->row();
    return isset($id->id) ? $id->id : '1';
}

function get_last_no_rm() {
    $CI = & get_instance();
    $sql = "select max(no_rm) as id from pasien";
    $no = $CI->db->query($sql)->row();
    $number = $no->id+1;
    $width = 6;
    $padded = str_pad((string)$number, $width, "0", STR_PAD_LEFT);
    return $padded;
}

function pad($number, $length) {
    $padded = str_pad((string)$number, $length, "0", STR_PAD_LEFT);
    return $padded;
}

function number_pad($number,$n) {
    return str_pad((int) $number,$n,"0",STR_PAD_RIGHT);
}

function get_last_repackage_id($table, $kolom, $trans) {
    $CI = & get_instance();
    $sql = "select max($kolom)+1 as id from $table where transaksi_jenis = '$trans'";
    $id = $CI->db->query($sql)->row();
    return isset($id->id) ? $id->id : '1';
}

function header_excel($namaFile) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,
            pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // header untuk nama file
    header("Content-Disposition: attachment;
            filename=" . $namaFile . "");
    header("Content-Transfer-Encoding: binary ");
}

function paging_ajax($jmldata, $dataPerPage, $klik, $tab = NULL, $search) {
    /*
     * Parameter '$search' dalam bentuk string , bisa json string atau yang lain
     * contoh 1#nama_barang#nama_pabrik
     */

    $showPage = NULL;
    ob_start();
    echo "
        <div class='body-page'>";
    if (!empty($klik)) {
        $noPage = $klik;
    } else {
        $noPage = 1;
    }

    $dataPerPage = $dataPerPage;


    $jumData = $jmldata;
    $jumPage = ceil($jumData / $dataPerPage);
    $get = $_GET;
    //if ($jumData > $dataPerPage) {
        $onclick = null;
        if ($noPage > 1) {
            $get['page'] = ($noPage - 1);
            $onclick = $klik;
        }
        $prev = null;
        $last = ' class="last-block" ';
        if ($klik > 1) {
            $prev = "onClick=\"paging(" . ($klik - 1) . "," . $tab . ", '" . $search . "')\" ";
        }
        echo "<div class='page-prev' $prev>prev</div>";
        for ($page = 1; $page <= $jumPage; $page++) {
            if ((($page >= $noPage - 1) && ($page <= $noPage + 1)) || ($page == 1) || ($page == $jumPage)) {
                if (($showPage == 1) && ($page != 2))
                    echo "<div class='titik'>...</div>";
                if (($showPage != ($jumPage - 1)) && ($page == $jumPage))
                    echo "<div class='titik'>...</div>";
                if ($page == $noPage)
                    echo " <div class='noblock'>" . $page . "</div> ";
                else {
                    $get['page'] = $page;
                    if ($tab != NULL) {
                        $get['tab'] = $tab;
                    }
                    $next = "onClick=\"paging(" . $page . "," . $tab . ", '" . $search . "')\" ";
                    //echo " <a class='block' href='?" . generate_get_parameter($get) . "'>" . $page . "</a> ";
                    if ($page == $jumPage) {
                        echo '<div  class="block" ' . $next . '>' . $page . '</div>';
                    } else {
                        echo '<div class="block" ' . $next . '>' . $page . '</div>';
                    }
                }
                $showPage = $page;
            }
        }
        $next = null;
        if ($klik < $jumPage) {
            $next = "onClick=\"paging(" . ($klik + 1) . "," . $tab . ", '" . $search . "')\" ";
        }
        echo "<div class='page-next' $next >next</div>";
    //}
    echo "</div>";

    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}

function page_summary($total_data, $halaman, $dataperpage) {
    $total_page = ceil($total_data/$dataperpage);
    return '<div class="page_summary">Page '.$halaman.' of '.$total_page.' Total '.$total_data.' Data(s)</div>';
}

function range_year_start_from_one_year_ago() {
    $x = mktime(0, 0, 0, date("m"), date("d"), date("Y") - 1);
    return date("Y-m-d", $x);
}

function range_hours_between_two_dates($date_input, $date_trans) {
    $val = explode(" ", $date_input);
    $date = explode("-", $val[0]);
    $time = explode(":", $val[1]);

    $vals = explode(" ", $date_trans);
    $dates = explode("-", $vals[0]);
    $times = explode(":", $vals[1]);

    $now = mktime($times[0], 0, 0, $dates[1], $dates[2], $dates[0]);
    $input = mktime($time[0], 0, 0, $date[1], $date[2], $date[0]);
    $selisih = ($now - $input) / 3600;
    return $selisih;
}

function tanggal_format($tgl) {
    $data = explode("-", $tgl);
    return $data[2] . " " . tampil_bulan($tgl) . " " . $data[0];
}

function cek_karakter($teks) {

    $kata_kotor = array("cadangan","akumulasi");
    $hasil = 0;
    $jml_kata = count($kata_kotor);
 
    for ($i=0;$i<$jml_kata;$i++) {
        if (stristr($teks,$kata_kotor[$i])) { 
            $hasil=1;    
        }
    }
    return $hasil;
}

function createRange($startDate, $endDate) {
    $tmpDate = new DateTime($startDate);
    $tmpEndDate = new DateTime($endDate);

    $outArray = array();
    do {
        $outArray[] = $tmpDate->format('Y-m-d');
    } while ($tmpDate->modify('+1 day') <= $tmpEndDate);

    return $outArray;
}



function get_safe($parameter){
    $CI = & get_instance();
    $string = $CI->input->get($parameter);
    $quote = str_replace("'", "`", $string);
    $hasil = str_replace(array("?", "\\"), "", $quote);
    return $hasil;
}

function post_safe($parameter){
    $CI = & get_instance();
    $string = $CI->input->post($parameter);
    $quote = str_replace("'", "`", $string);
    $hasil = str_replace(array("?", "\\"), "", $quote);
    return $hasil;
}

function form_input($name, $value = NULL, $attr = NULL) {
    return '<input type=text name="'.$name.'" value="'.$value.'" '.$attr.' />';
}

function form_password($name, $value = NULL, $attr = NULL) {
    return '<input type=password name="'.$name.'" value="'.$value.'" '.$attr.' />';
}

function form_hidden($name, $value = NULL, $attr = NULL) {
    return '<input type=hidden name="'.$name.'" value="'.$value.'" '.$attr.' />';
}

function form_textarea($name, $value = NULL, $attr = NULL) {
    return '<textarea name="'.$name.'" '.$attr.'>'.$value.'</textarea>';
}

function form_upload($name, $value = NULL, $attr = NULL) {
    return '<input type=file name="'.$name.'"  value="'.$value.'" '.$attr.' />';
}

function form_button($value, $attr) {
    return '<button '.$attr.' onclick="return false;">'.$value.'</button>';
    //return '<input type=button value="'.$value.'" '.$attr.' />';
}

function form_radio($name, $value, $id, $label = null, $checked = null) {
    if ($checked !== NULL) {
        if ($checked == 'TRUE') {
            $attr = "checked";
        } else if ($checked == 'FALSE') {
            $attr = "";
        } else {
            $attr = "$checked";
        }
    }
    return '<input type=radio name="'.$name.'" value="'.$value.'" id="'.$id.'" '.$attr.' /><label for="'.$id.'">'.$label.'</label>';
}

function form_checkbox($name, $value, $id, $label = null, $checked = FALSE) {
    $attr = "";
    if ($checked == TRUE) {
        $attr = "checked";
    }
    return '<input type=checkbox name="'.$name.'" value="'.$value.'" id="'.$id.'" '.$attr.' /><label for="'.$id.'">'.$label.'</label>';
}


function get_last_pemesanan() {
    $sql = mysql_query("select substr(id, 4,3) as id  from pemesanan order by tanggal desc limit 1");
    $row = mysql_fetch_object($sql);
    if (!isset($row->id)) {
        $result = "SP.001/".date("m/Y");
    } else {
        $result = "SP.".str_pad((string)($row->id+1), 3, "0", STR_PAD_LEFT)."/".date("m/Y");
    }
    return $result;
}

function get_list_bulan() {
    return array(
        array('01' => 'Januari')
    );
}

function tampil_nama_bulan($ym) { // parameter year and month
    $split  = explode("-", $ym);
    if ($split[1] === '01') {
        $mon    = "Januari";
    } 
    if ($split[1] === '02') {
        $mon    = "Februari";
    } 
    if ($split[1] === '03') {
        $mon    = "Maret";
    } 
    if ($split[1] === '04') {
        $mon    = "April";
    } 
    if ($split[1] === '05') {
        $mon    = "Mei";
    } 
    if ($split[1] === '06') {
        $mon    = "Juni";
    } 
    if ($split[1] === '07') {
        $mon    = "Juli";
    } 
    if ($split[1] === '08') {
        $mon    = "Agustus";
    } 
    if ($split[1] === '09') {
        $mon    = "September";
    } 
    if ($split[1] === '10') {
        $mon    = "Oktober";
    } 
    if ($split[1] === '11') {
        $mon    = "November";
    } 
    if ($split[1] === '12') {
        $mon    = "Desember";
    }
    return strtoupper($mon)." ".$split[0];
}

/*TERBILANG*/
function terbilang_get_valid($str,$from,$to,$min=1,$max=9){
	$val=false;
	$from=($from<0)?0:$from;
	for ($i=$from;$i<$to;$i++){
		if (((int) $str{$i}>=$min)&&((int) $str{$i}<=$max)) $val=true;
	}
	return $val;
}
function terbilang_get_str($i,$str,$len){
	$numA=array("","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan");
	$numB=array("","se","dua ","tiga ","empat ","lima ","enam ","tujuh ","delapan ","sembilan ");
	$numC=array("","satu ","dua ","tiga ","empat ","lima ","enam ","tujuh ","delapan ","sembilan ");
	$numD=array(0=>"puluh",1=>"belas",2=>"ratus",4=>"ribu", 7=>"juta", 10=>"milyar", 13=>"triliun");
	$buf="";
	$pos=$len-$i;
	switch($pos){
		case 1:
				if (!terbilang_get_valid($str,$i-1,$i,1,1))
					$buf=$numA[(int) $str{$i}];
			break;
		case 2:	case 5: case 8: case 11: case 14:
				if ((int) $str{$i}==1){
					if ((int) $str{$i+1}==0)
						$buf=($numB[(int) $str{$i}]).($numD[0]);
					else
						$buf=($numB[(int) $str{$i+1}]).($numD[1]);
				}
				else if ((int) $str{$i}>1){
						$buf=($numB[(int) $str{$i}]).($numD[0]);
				}				
			break;
		case 3: case 6: case 9: case 12: case 15:
				if ((int) $str{$i}>0){
						$buf=($numB[(int) $str{$i}]).($numD[2]);
				}
			break;
		case 4: case 7: case 10: case 13:
				if (terbilang_get_valid($str,$i-2,$i)){
					if (!terbilang_get_valid($str,$i-1,$i,1,1))
						$buf=$numC[(int) $str{$i}].($numD[$pos]);
					else
						$buf=$numD[$pos];
				}
				else if((int) $str{$i}>0){
					if ($pos==4)
						$buf=($numB[(int) $str{$i}]).($numD[$pos]);
					else
						$buf=($numC[(int) $str{$i}]).($numD[$pos]);
				}
			break;
	}
	return $buf;
}
function toTerbilang($nominal){
	$buf="";
	$str=$nominal."";
	$len=strlen($str);
	for ($i=0;$i<$len;$i++){
		$buf=trim($buf)." ".terbilang_get_str($i,$str,$len);
	}
	return trim($buf);
}

function formatcurrency($floatcurr, $curr = "USD"){
        $currencies['ARS'] = array(2,',','.');          //  Argentine Peso
        $currencies['AMD'] = array(2,'.',',');          //  Armenian Dram
        $currencies['AWG'] = array(2,'.',',');          //  Aruban Guilder
        $currencies['AUD'] = array(2,'.',' ');          //  Australian Dollar
        $currencies['BSD'] = array(2,'.',',');          //  Bahamian Dollar
        $currencies['BHD'] = array(3,'.',',');          //  Bahraini Dinar
        $currencies['BDT'] = array(2,'.',',');          //  Bangladesh, Taka
        $currencies['BZD'] = array(2,'.',',');          //  Belize Dollar
        $currencies['BMD'] = array(2,'.',',');          //  Bermudian Dollar
        $currencies['BOB'] = array(2,'.',',');          //  Bolivia, Boliviano
        $currencies['BAM'] = array(2,'.',',');          //  Bosnia and Herzegovina, Convertible Marks
        $currencies['BWP'] = array(2,'.',',');          //  Botswana, Pula
        $currencies['BRL'] = array(2,',','.');          //  Brazilian Real
        $currencies['BND'] = array(2,'.',',');          //  Brunei Dollar
        $currencies['CAD'] = array(2,'.',',');          //  Canadian Dollar
        $currencies['KYD'] = array(2,'.',',');          //  Cayman Islands Dollar
        $currencies['CLP'] = array(0,'','.');           //  Chilean Peso
        $currencies['CNY'] = array(2,'.',',');          //  China Yuan Renminbi
        $currencies['COP'] = array(2,',','.');          //  Colombian Peso
        $currencies['CRC'] = array(2,',','.');          //  Costa Rican Colon
        $currencies['HRK'] = array(2,',','.');          //  Croatian Kuna
        $currencies['CUC'] = array(2,'.',',');          //  Cuban Convertible Peso
        $currencies['CUP'] = array(2,'.',',');          //  Cuban Peso
        $currencies['CYP'] = array(2,'.',',');          //  Cyprus Pound
        $currencies['CZK'] = array(2,'.',',');          //  Czech Koruna
        $currencies['DKK'] = array(2,',','.');          //  Danish Krone
        $currencies['DOP'] = array(2,'.',',');          //  Dominican Peso
        $currencies['XCD'] = array(2,'.',',');          //  East Caribbean Dollar
        $currencies['EGP'] = array(2,'.',',');          //  Egyptian Pound
        $currencies['SVC'] = array(2,'.',',');          //  El Salvador Colon
        $currencies['ATS'] = array(2,',','.');          //  Euro
        $currencies['BEF'] = array(2,',','.');          //  Euro
        $currencies['DEM'] = array(2,',','.');          //  Euro
        $currencies['EEK'] = array(2,',','.');          //  Euro
        $currencies['ESP'] = array(2,',','.');          //  Euro
        $currencies['EUR'] = array(2,',','.');          //  Euro
        $currencies['FIM'] = array(2,',','.');          //  Euro
        $currencies['FRF'] = array(2,',','.');          //  Euro
        $currencies['GRD'] = array(2,',','.');          //  Euro
        $currencies['IEP'] = array(2,',','.');          //  Euro
        $currencies['ITL'] = array(2,',','.');          //  Euro
        $currencies['LUF'] = array(2,',','.');          //  Euro
        $currencies['NLG'] = array(2,',','.');          //  Euro
        $currencies['PTE'] = array(2,',','.');          //  Euro
        $currencies['GHC'] = array(2,'.',',');          //  Ghana, Cedi
        $currencies['GIP'] = array(2,'.',',');          //  Gibraltar Pound
        $currencies['GTQ'] = array(2,'.',',');          //  Guatemala, Quetzal
        $currencies['HNL'] = array(2,'.',',');          //  Honduras, Lempira
        $currencies['HKD'] = array(2,'.',',');          //  Hong Kong Dollar
        $currencies['HUF'] = array(0,'','.');           //  Hungary, Forint
        $currencies['ISK'] = array(0,'','.');           //  Iceland Krona
        $currencies['INR'] = array(2,'.',',');          //  Indian Rupee
        $currencies['IDR'] = array(2,',','.');          //  Indonesia, Rupiah
        $currencies['IRR'] = array(2,'.',',');          //  Iranian Rial
        $currencies['JMD'] = array(2,'.',',');          //  Jamaican Dollar
        $currencies['JPY'] = array(0,'',',');           //  Japan, Yen
        $currencies['JOD'] = array(3,'.',',');          //  Jordanian Dinar
        $currencies['KES'] = array(2,'.',',');          //  Kenyan Shilling
        $currencies['KWD'] = array(3,'.',',');          //  Kuwaiti Dinar
        $currencies['LVL'] = array(2,'.',',');          //  Latvian Lats
        $currencies['LBP'] = array(0,'',' ');           //  Lebanese Pound
        $currencies['LTL'] = array(2,',',' ');          //  Lithuanian Litas
        $currencies['MKD'] = array(2,'.',',');          //  Macedonia, Denar
        $currencies['MYR'] = array(2,'.',',');          //  Malaysian Ringgit
        $currencies['MTL'] = array(2,'.',',');          //  Maltese Lira
        $currencies['MUR'] = array(0,'',',');           //  Mauritius Rupee
        $currencies['MXN'] = array(2,'.',',');          //  Mexican Peso
        $currencies['MZM'] = array(2,',','.');          //  Mozambique Metical
        $currencies['NPR'] = array(2,'.',',');          //  Nepalese Rupee
        $currencies['ANG'] = array(2,'.',',');          //  Netherlands Antillian Guilder
        $currencies['ILS'] = array(2,'.',',');          //  New Israeli Shekel
        $currencies['TRY'] = array(2,'.',',');          //  New Turkish Lira
        $currencies['NZD'] = array(2,'.',',');          //  New Zealand Dollar
        $currencies['NOK'] = array(2,',','.');          //  Norwegian Krone
        $currencies['PKR'] = array(2,'.',',');          //  Pakistan Rupee
        $currencies['PEN'] = array(2,'.',',');          //  Peru, Nuevo Sol
        $currencies['UYU'] = array(2,',','.');          //  Peso Uruguayo
        $currencies['PHP'] = array(2,'.',',');          //  Philippine Peso
        $currencies['PLN'] = array(2,'.',' ');          //  Poland, Zloty
        $currencies['GBP'] = array(2,'.',',');          //  Pound Sterling
        $currencies['OMR'] = array(3,'.',',');          //  Rial Omani
        $currencies['RON'] = array(2,',','.');          //  Romania, New Leu
        $currencies['ROL'] = array(2,',','.');          //  Romania, Old Leu
        $currencies['RUB'] = array(2,',','.');          //  Russian Ruble
        $currencies['SAR'] = array(2,'.',',');          //  Saudi Riyal
        $currencies['SGD'] = array(2,'.',',');          //  Singapore Dollar
        $currencies['SKK'] = array(2,',',' ');          //  Slovak Koruna
        $currencies['SIT'] = array(2,',','.');          //  Slovenia, Tolar
        $currencies['ZAR'] = array(2,'.',' ');          //  South Africa, Rand
        $currencies['KRW'] = array(0,'',',');           //  South Korea, Won
        $currencies['SZL'] = array(2,'.',', ');         //  Swaziland, Lilangeni
        $currencies['SEK'] = array(2,',','.');          //  Swedish Krona
        $currencies['CHF'] = array(2,'.','\'');         //  Swiss Franc 
        $currencies['TZS'] = array(2,'.',',');          //  Tanzanian Shilling
        $currencies['THB'] = array(2,'.',',');          //  Thailand, Baht
        $currencies['TOP'] = array(2,'.',',');          //  Tonga, Paanga
        $currencies['AED'] = array(2,'.',',');          //  UAE Dirham
        $currencies['UAH'] = array(2,',',' ');          //  Ukraine, Hryvnia
        $currencies['USD'] = array(2,'.',',');          //  US Dollar
        $currencies['VUV'] = array(0,'',',');           //  Vanuatu, Vatu
        $currencies['VEF'] = array(2,',','.');          //  Venezuela Bolivares Fuertes
        $currencies['VEB'] = array(2,',','.');          //  Venezuela, Bolivar
        $currencies['VND'] = array(0,'','.');           //  Viet Nam, Dong
        $currencies['ZWD'] = array(2,'.',' ');          //  Zimbabwe Dollar

        if ($curr == "INR"){    
            return formatinr($floatcurr);
        } else {
            return number_format($floatcurr,$currencies[$curr][0],$currencies[$curr][1],$currencies[$curr][2]);
        }
    }

?>
