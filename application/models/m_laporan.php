<?php

class M_laporan extends CI_Model {
    
    function get_data_realisasi($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['key'] !== 'undefined') {
            $q.=" and rk.keterangan like '%".$search['key']."%'";
        }
        if ($search['id'] !== 'undefined') {
            $q.=" and rk.id_renbut = '".$search['id']."'";
        }
        if (($search['bulan'] !== '') and ($search['bulan'] !== 'undefined-undefined')) {
            $q.=" and rk.tanggal like ('%".$search['bulan']."%')";
        }
        if (($search['satker'] !== '') and ($search['satker'] !== 'undefined')) {
            $q.=" and s.id = '".$search['satker']."'";
        }
        $q.=" order by rk.tanggal asc";
        $sql = "select rk.*, s.nama as satker, CONCAT_WS('',p.kode,k.kode,sk.kode,u.kode) as ma_proja from rencana_kebutuhan rk
            join uraian u on (rk.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id) where rk.id is not NULL";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function load_satker($param) {
        $q = "";
        $tahun = date("Y");
        if ($param['tahun'] !== '') {
            $tahun = $param['tahun'];
        }
        if ($param['satker'] !== '' and $param['satker'] !== 'undefined') {
            $q.=" and p.id_satker = '".$param['satker']."'";
        }
        $sql = "select s.*, p.pagu, p.tahun, p.id_satker "
                . "from satker s "
                . "join pagu_anggaran p on (s.id = p.id_satker) "
                . "where p.tahun = '".$tahun."' $q order by s.nama";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function load_realisasi_total_satker($bulan, $id_satker = NULL) {
        $q = NULL;
        if ($id_satker !== NULL) {
            $q = "and s.id = '$id_satker'";
        }
        $sql = "select sum(nominal)+sum(cashbon) as total 
            from rencana_kebutuhan rk
            join uraian u on (rk.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
            where rk.tanggal_kegiatan like ('%".$bulan."%')
                $q";
        return $this->db->query($sql);
    }
    
    function get_data_pencairan_normal($limit, $start, $search) {
        $q = null;
        if ($search['key'] !== 'undefined') {
            $q.=" and rk.keterangan like '%".$search['key']."%'";
        }
        if ($search['id'] !== 'undefined') {
            $q.=" and rk.id_renbut = '".$search['id']."'";
        }
        if (($search['bulan'] !== '') and ($search['bulan'] !== 'undefined-undefined')) {
            $q.=" and rk.tanggal like ('%".$search['bulan']."%')";
        }
        if (($search['satker'] !== '') and ($search['satker'] !== 'undefined')) {
            $q.=" and s.id = '".$search['satker']."'";
        }
        if (($search['proja'] !== '') and ($search['proja'] !== 'undefined')) {
            $q.=" having ma_proja like ('%".$search['proja']."%')";
        }
        if (($search['pjawab'] !== '') and ($search['pjawab'] !== 'undefined')) {
            $q.=" and rk.penerima like ('%".$search['pjawab']."%')";
        }
        $q.=" order by rk.tanggal asc";
        $sql = "select rk.*, s.nama as satker, u.kode as ma_proja from rencana_kebutuhan rk
            join uraian u on (rk.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
            where rk.status = 'Disetujui' and rk.cashbon = '0'";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function get_data_cashbon($limit, $start, $search) {
        $q = null;
        if ($search['key'] !== 'undefined') {
            $q.=" and rk.keterangan like '%".$search['key']."%'";
        }
        if ($search['id'] !== 'undefined') {
            $q.=" and rk.id_renbut = '".$search['id']."'";
        }
        if (($search['bulan'] !== '') and ($search['bulan'] !== 'undefined-undefined')) {
            $q.=" and rk.tanggal like ('%".$search['bulan']."%')";
        }
        if (($search['satker'] !== '') and ($search['satker'] !== 'undefined')) {
            $q.=" and s.id = '".$search['satker']."'";
        }
        if (($search['proja'] !== '') and ($search['proja'] !== 'undefined')) {
            $q.=" having ma_proja like ('%".$search['proja']."%')";
        }
        if (($search['pjawab'] !== '') and ($search['pjawab'] !== 'undefined')) {
            $q.=" and rk.penerima like ('%".$search['pjawab']."%')";
        }
        $q.=" order by rk.tanggal asc";
        $sql = "select rk.*, s.nama as satker, u.kode as ma_proja from rencana_kebutuhan rk
            join uraian u on (rk.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
            where rk.status = 'Disetujui' and rk.cashbon != '0'";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function grafik_penggunaan_load_data($bulan, $satker) {
        $tahun = substr($bulan, 0, 4);
        $sql = "select s.nama as satker, sum(rk.nominal) as realisasi, 
            YEAR(rk.tanggal_cair) as tahun, 
            rk.tanggal_cair as bulan,
            (select (pagu/12) from pagu_anggaran where tahun = '$tahun' and id_satker = '$satker') as rata_pagu
            from rencana_kebutuhan rk
            join uraian u on (rk.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
            where YEAR(rk.tanggal_cair) = '$tahun' and s.id = '$satker' GROUP BY s.id, YEAR(rk.tanggal_cair), MONTH(rk.tanggal_cair) ASC
            ";
        //echo "<pre>".$sql."</pre>";
        return $this->db->query($sql);
    }
    
    function get_data_kas_bank($bulan, $rekening = NULL) {
        /*$sql = "select j.*, s.nama as nama_rekening, u.uraian
            from jurnal j
            join sub_sub_sub_sub_rekening s on (j.id_rekening = s.id)
            join uraian u on (j.id_uraian = u.id)
            where j.tanggal like ('%$bulan%')";*/
        $q = NULL;
        if ($rekening !== NULL) {
            $q=" and s.nama like ('%$rekening%')";
        }
        $sql = "select SUBSTR(p.kode,1,3) as kode_ket,SUBSTR(p.kode,5,4) as kode_auto, p.*, u.uraian,
            p.penyetor as user, p.pemasukkan, 0 as pengeluaran 
            from penerimaan p
            left join sub_sub_sub_sub_rekening s on (p.id_rekening = s.id)
            join uraian u on (p.id_uraian = u.id) 
            where p.tanggal like ('%$bulan%') $q
            UNION
            select SUBSTR(p.kode,1,3) as kode_ket,SUBSTR(p.kode,5,4) as kode_auto, p.*, u.uraian, 
            p.penerima as user, 0 as pemasukkan, p.pengeluaran 
            from pengeluaran p
            left join sub_sub_sub_sub_rekening s on (p.id_rekening = s.id)
            join uraian u on (p.id_uraian = u.id)
            where p.tanggal like ('%$bulan%') $q";
        //echo "<pre>".$sql."</pre>";
        return $this->db->query($sql);
    }
    
    function get_saldo_awal_kas($bulan) {
        $ext = explode('-', $bulan);
        $prev= mktime(0, 0, 0, $ext[1]-1, date("d") , $ext[0]);
        $new_prev = date("Y-m-31", $prev);
        $sql = "select sum(pemasukkan)-(select sum(pengeluaran) from pengeluaran where tanggal <= '$new_prev') as saldo_sisa from penerimaan where tanggal <= '$new_prev'";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function get_data_renbut($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['bulan'] !== '') {
            $q.=" and rk.tanggal like ('%".$search['bulan']."%')";
        }
        if (($search['satker'] !== '') and ($search['satker'] !== 'undefined')) {
            $q.=" and s.id = '".$search['satker']."'";
        }
        $q.=" order by rk.tanggal asc";
        $sql = "select rk.*, s.nama as satker, u.kode as ma_proja,
            CONCAT_WS(' / ',s.nama, p.status, p.nama_program, k.nama_kegiatan, sk.nama_sub_kegiatan) as detail
            from rencana_kebutuhan rk
            join uraian u on (rk.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
            where rk.kode != ''
            ";
        $limitation = null;
        if ($limit !== null) {
            $limitation =" limit $start , $limit";
        }
        $query = $this->db->query($sql . $q . $limitation);
        echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
}
?>
