<?php

class M_autocomplete extends CI_Model {
    
    function get_satker($q) {
        $sql = "select * from satker where nama like ('%$q%') order by locate('$q',nama)";
        return $this->db->query($sql);
    }
    
    function get_program($q, $id_satker, $status) {
        $a = NULL;
        if ($id_satker !== '') {
            $a.= "and s.id = '$id_satker'";
        }
        if ($status !== NULL and $status !== '') {
            $a.=" and p.status = '$status'";
        }
        $sql = "select p.*, s.nama as satker from program p
            join satker s on (p.id_satker = s.id) 
            where (p.kode like ('%$q%') or p.nama_program like ('%$q%')) $a";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function get_kegiatan($q, $id_satker, $status) {
        $a = NULL;
        if ($id_satker !== '') {
            $a.= "and s.id = '$id_satker'";
        }
        if ($status !== NULL and $status !== '') {
            $a.=" and p.status = '$status'";
        }
        $sql = "select p.*, s.nama as satker, s.id as id_satker, k.kode as code,
            k.nama_kegiatan, k.id as id_kegiatan from kegiatan k
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id) 
            where k.id is not NULL $a having code like ('%$q%') or nama_kegiatan like ('%$q%')";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function get_uraian($q, $id_satker, $status) {
        $a = NULL;
        if ($id_satker !== '') {
            $a.= "and s.id = '$id_satker'";
        }
        if ($status !== NULL and $status !== '') {
            $a.=" and p.status = '$status'";
        }
        $sql = "select u.*, s.nama as satker, s.id as id_satker, u.kode as code,
            sk.nama_sub_kegiatan, k.id as id_kegiatan, p.status, u.uraian
            from uraian u
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id) 
            where u.id is not NULL $a having code like ('%$q%') or uraian like ('%$q%')";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function get_sub_uraian($q, $id_satker, $status) {
        $a = NULL;
        if ($id_satker !== '') {
            $a.= "and s.id = '$id_satker'";
        }
        if ($status !== NULL and $status !== '') {
            $a.=" and p.status = '$status'";
        }
        $sql = "select su.*, s.nama as satker, s.id as id_satker, su.kode as code,
            sk.nama_sub_kegiatan, k.id as id_kegiatan, p.status, su.keterangan as uraian,
            CONCAT_WS(' - ',p.nama_program,k.nama_kegiatan,sk.nama_sub_kegiatan,u.uraian,su.keterangan) as oketerangan
            from sub_uraian su
            join uraian u on (su.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id) 
            where su.id is not NULL $a having code like ('%$q%') or oketerangan like ('%$q%')";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function get_sub_kegiatan($q, $id_satker, $status) {
        $a = NULL;
        if ($id_satker !== '') {
            $a.= "and s.id = '$id_satker'";
        }
        if ($status !== NULL and $status !== '') {
            $a.=" and p.status = '$status'";
        }
        $sql = "select sk.*, s.nama as satker, s.id as id_satker, sk.kode as code,
            sk.nama_sub_kegiatan, k.id as id_kegiatan, p.status from sub_kegiatan sk
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id) 
            where k.id is not NULL $a having code like ('%$q%') or nama_sub_kegiatan like ('%$q%')";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function get_ma_proja($q) {
        $sql = "select u.*, s.nama as satker, 
            u.kode as ma_proja, u.uraian,
            CONCAT_WS(' / ',s.nama, p.status, p.nama_program, k.nama_kegiatan, sk.nama_sub_kegiatan) as keterangan
            from uraian u
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id) 
            where u.id is not NULL 
            having ma_proja like ('%$q%') or keterangan like ('%$q%')";
        return $this->db->query($sql);
    }
    
    function get_kode_renbut($q) {
        $sql = "select u.*, s.nama as satker, rk.id_renbut as id_rk, rk.kode as kode_rk, rk.jml_renbut, rk.penerima, 
            u.kode as ma_proja, u.uraian,
            CONCAT_WS(' / ',s.nama, p.status, p.nama_program, k.nama_kegiatan, sk.nama_sub_kegiatan) as keterangan
            from uraian u
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id) 
            join rencana_kebutuhan rk on (rk.id_uraian = u.id)
            where u.id is not NULL 
            and rk.kode like ('$q%')";
        return $this->db->query($sql);
    }
    
    function get_kode_perkiraan($param) {
        $q = NULL;
        if ($param['kategori'] === 'Kas') {
            $q.=" and s4r.id_sub_sub_sub_rekening = '111100'";
        }
        if ($param['kategori'] === 'Bank') {
            $q.=" and s4r.id_sub_sub_sub_rekening = '111200'";
        }
        $sql = "select r.*, s4r.id as id_akun, CONCAT_WS(' - ',r.nama,sr.nama,s2r.nama,s3r.nama,s4r.nama) as akun,
            s4r.nama as perkiraan
            from sub_sub_sub_sub_rekening s4r
            join sub_sub_sub_rekening s3r on (s4r.id_sub_sub_sub_rekening = s3r.id)
            join sub_sub_rekening s2r on (s3r.id_sub_sub_rekening = s2r.id)
            join sub_rekening sr on (s2r.id_sub_rekening = sr.id)
            join rekening r on (sr.id_rekening = r.id)
            where s4r.id is not NULL $q having akun like ('".$param['q']."%') or id_akun like ('".$param['q']."%')";
        return $this->db->query($sql);
    }
    
    function get_kode_perkiraan_pwk($param) {
        $q = NULL;
        if ($param['kategori'] === 'Belum') {
            $q.=" and s4r.id_sub_sub_sub_rekening = '115100'";
        }
        if ($param['kategori'] === 'Sudah') {
            $q.=" and s4r.id_sub_sub_sub_rekening = '511100'";
        }
        $sql = "select r.*, s4r.id as id_akun, CONCAT_WS(' - ',r.nama,sr.nama,s2r.nama,s3r.nama,s4r.nama) as akun,
            s4r.nama as perkiraan
            from sub_sub_sub_sub_rekening s4r
            join sub_sub_sub_rekening s3r on (s4r.id_sub_sub_sub_rekening = s3r.id)
            join sub_sub_rekening s2r on (s3r.id_sub_sub_rekening = s2r.id)
            join sub_rekening sr on (s2r.id_sub_rekening = sr.id)
            join rekening r on (sr.id_rekening = r.id)
            where s4r.id is not NULL $q having akun like ('".$param['q']."%') or id_akun like ('".$param['q']."%')";
        return $this->db->query($sql);
    }
            
    function get_last_code($table, $kolom, $id_parent = NULL) {
        $adding = 0;
        $q = "";
        $cols = "";
        if ($table === 'rekening') {
            $adding = 100000;
            $cols = "IFNULL(max($kolom),0)+$adding";
        } else if ($table === 'sub_rekening') {
            $adding = 10000;
            $cols = "IFNULL(max($kolom),$id_parent)+$adding";
            if ($id_parent !== NULL) {
                $q.=" where id_rekening = '$id_parent'";
            }
        } else if ($table === 'sub_sub_rekening') {
            $adding = 1000;
            $cols = "IFNULL(max($kolom),$id_parent)+$adding";
            if ($id_parent !== NULL) {
                $q.=" where id_sub_rekening = '$id_parent'";
            }
        } else if ($table === 'sub_sub_sub_rekening') {
            $adding = 100;
            $cols = "IFNULL(max($kolom),$id_parent)+$adding";
            if ($id_parent !== NULL) {
                $q.=" where id_sub_sub_rekening = '$id_parent'";
            }
        } else if ($table === 'sub_sub_sub_sub_rekening') {
            $adding = 1;
            $cols = "IFNULL(max($kolom),$id_parent)+$adding";
            if ($id_parent !== NULL) {
                $q.=" where id_sub_sub_sub_rekening = '$id_parent'";
            }
        }

        $sql = "select $cols as id from $table $q";
        return $this->db->query($sql);
    }
    
    function get_last_code_kasir($trans, $tanggal) {
        $bulan = substr($tanggal, 0, 7);
        if ($trans === 'bkm') {
            $sql = "select IFNULL(SUBSTR(kode,8,4),0) as kode from penerimaan where tanggal like '".$bulan."%' order by id desc limit 1";
            $data= $this->db->query($sql)->row();
            if (isset($data->kode)) {
                $auto = $data->kode;
            } else {
                $auto = 0;
            }
            $thn = substr($bulan, 2, 2);
            $bln = substr($bulan, 5, 2);
            $result['no'] = 'BKM'.$thn.$bln.pad($auto+1, 4);
        }
        if ($trans === 'bkk') {
            $sql = "select IFNULL(SUBSTR(kode,8,4),0) as kode from pengeluaran where tanggal like '".$bulan."%' order by id desc limit 1";
            $data= $this->db->query($sql)->row();
            if (isset($data->kode)) {
                $auto = $data->kode;
            } else {
                $auto = 0;
            }
            $thn = substr($bulan, 2, 2);
            $bln = substr($bulan, 5, 2);
            $result['no'] = 'BKK'.$thn.$bln.pad($auto+1, 4);
        }
        return $result;
    }
    
    function get_auto_last_code_program($satker, $status) {
        $sql = "select IFNULL(max(kode),0)+10000 as kode from program where id_satker = '$satker' and status = '$status'";
        return $this->db->query($sql);
    }
    
    function get_auto_last_code_kegiatan($id_program) {
        $sql = "select IFNULL(max(k.kode),p.kode)+1000 as kode
                from kegiatan k 
                join program p on (k.id_program = p.id)
                where k.id_program = '$id_program'";
        return $this->db->query($sql);
    }
    
    function get_auto_last_code_sub_kegiatan($id_kegiatan) {
        $sql = "select IFNULL(max(sk.kode),k.kode)+100 as kode
                from sub_kegiatan sk
                join kegiatan k on (sk.id_kegiatan = k.id)
                join program p on (k.id_program = p.id)
                where sk.id_kegiatan = '$id_kegiatan'";
        return $this->db->query($sql);
    }
    
    function get_auto_last_code_uraian($sub_kegiatan) {
        $sql = "select IFNULL(max(u.kode),sk.kode)+10 as kode
                from uraian u
                join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
                join kegiatan k on (sk.id_kegiatan = k.id)
                join program p on (k.id_program = p.id)
                where u.id_sub_kegiatan = '$sub_kegiatan'";
        return $this->db->query($sql);
    }
    
    function get_auto_last_code($table, $kolom, $id_parent = NULL) {
        $adding = 0;
        $q = "";
        $cols = "";
        if ($table === 'program') {
            $adding = 10000;
            $cols = "IFNULL(max($kolom),0)+$adding";
        } else if ($table === 'kegiatan') {
            $adding = 10000;
            $cols = "IFNULL(max($kolom),$id_parent)+$adding";
            if ($id_parent !== NULL) {
                $q.=" where id_rekening = '$id_parent'";
            }
        }
        $sql = "select $cols as id from $table $q";
        return $this->db->query($sql);
    }
    
    function get_nominal_renbut($id_uraian, $tanggal) {
        $tahun = substr($tanggal, 6, 4);
        $sql = "select IFNULL(sum(ssu.sub_total), sum(su.sub_total)) as total from 
        sub_sub_uraian ssu    
        right join sub_uraian su on (ssu.id_sub_uraian = su.id)
        join uraian u on (su.id_uraian = u.id)
        join sub_kegiatan sk on (sk.id = u.id_sub_kegiatan)
        join kegiatan k on (sk.id_kegiatan = k.id)
        join program p on (k.id_program = p.id)
        join satker s on (p.id_satker = s.id) where u.id = '$id_uraian' and su.tahun = '$tahun'";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function get_nomor_renbut($tanggal) {
        $row = $this->db->query("select substr(kode,10,4) as kode from rencana_kebutuhan where kode != '' and tanggal_kegiatan like '".$tanggal."%' order by id_renbut desc limit 1")->row();
        //echo "select substr(kode,10,4) as kode from rencana_kebutuhan where kode != '' and tanggal like '".$tanggal."%' order by id_renbut desc limit 1";
        if (!isset($row->kode)) {
            $nomor = 0;
        } else {
            $nomor = $row->kode;
        }
        return 'RBT'.str_replace('-', '', $tanggal).pad($nomor+1,4);
    }
    
    function nomorbkk($q) {
        $sql = "select p.*, u.uraian as keterangan, p.kode, p.pengeluaran as cashbon, u.kode as kode_ma, r.id_renbut
            from pengeluaran p
            left join rencana_kebutuhan r on (p.kode = r.kode_cashbon)
            join uraian u on (p.id_uraian = u.id)
            where p.perwabku = 'Default' and p.id_renbut is NULL and p.kode like ('".$q."%')";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function nomorbkkdp($q) {
        $sql = "select p.*, u.uraian as keterangan, p.kode, p.pengeluaran as cashbon, u.kode as kode_ma, r.id_renbut
            from pengeluaran p
            left join rencana_kebutuhan r on (p.kode = r.kode_cashbon)
            join uraian u on (p.id_uraian = u.id)
            where p.perwabku = 'Belum' and p.kode like ('".$q."%')";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function get_nomor_perwabku($tanggal) {
        $row = $this->db->query("select substr(kode,10,4) as kode from perwabku where tanggal like '".$tanggal."%' order by id desc limit 1")->row();
        //echo "select substr(kode,10,4) as kode from perwabku where tanggal like '".$tanggal."%' order by id desc limit 1";
        if (!isset($row->kode)) {
            $nomor = 0;
        } else {
            $nomor = $row->kode;
        }
        $thn = substr($tanggal, 2, 2);
        $bln = substr($tanggal, 5, 2);
        return 'PWK'.$thn.$bln.pad($nomor+1,4);
    }
}