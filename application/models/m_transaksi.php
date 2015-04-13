<?php

class M_transaksi extends CI_Model {
    
    function get_data_renbut($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            $q.=" and rk.tanggal_renbut between '".$search['awal']."' and '".$search['akhir']."'";
        }
        if ($search['awal_keg'] !== '' and $search['akhir_keg'] !== '') {
            $q.=" and rk.tanggal_kegiatan between '".$search['awal_keg']."' and '".$search['akhir_keg']."'";
        }
        if ($search['jenis'] !== '') {
            if ($search['jenis'] === 'murni') {
                $q.=" and rk.kode_cashbon = ''";
            }
            if ($search['jenis'] === 'cashbon') {
                $q.=" and rk.kode_cashbon != ''";
            }
        }
        if ($search['kegiatan'] !== '') {
            $q.=" and rk.keterangan like ('%".$search['kegiatan']."%')";
        }
        if ($search['satker'] !== '') {
            $q.=" and s.id = '".$search['satker']."'";
        }
        $q.=" order by rk.id_renbut desc";
        $sql = "select rk.*, s.nama as satker, u.kode as ma_proja, p.status as status_pengeluaran,
            CONCAT_WS(' / ',s.nama, p.status, p.nama_program, k.nama_kegiatan, sk.nama_sub_kegiatan) as detail, IFNULL(ks.id,'') as id_pengeluaran
            from rencana_kebutuhan rk
            left join kasir ks on (rk.kode_cashbon = ks.kode and ks.kode = 'BKK')
            join uraian u on (rk.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
            where rk.kode != ''";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo "<pre>".$sql . $q . $limitation."</pre>";
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function get_data_renbut_detail($id) {
        $sql = "select rk.*, rk.id_renbut as id, s.id as id_satker, s.kode as kode_satker, s.nama as satker, year(rk.tanggal) as tahun_anggaran, 
            p.nama_program,k.nama_kegiatan, sk.nama_sub_kegiatan, u.uraian, rk.jml_renbut, p.status as status_pengeluaran,
            u.kode as ma_proja from rencana_kebutuhan rk
            join uraian u on (rk.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
        where rk.id_renbut = '$id'";
        return $this->db->query($sql);
    }
    
    function save_renbut() {
        $id         = post_safe('id_renbut');
        $tanggal    = (post_safe('tanggal_renbut') !== '')?date2mysql(post_safe('tanggal')):NULL;
        $tgl_renbut = date2mysql(post_safe('tanggal_renbut'));
        $id_uraian  = post_safe('id_uraian');
        $keterangan = post_safe('keterangan');
        $jml_renbut = currencyToNumber(post_safe('jml_renbut'));
        $kode       = post_safe('nomor');
        $id_out     = post_safe('id_pengeluaran');
//        $cashbon    = post_safe('cashbon');
//        $nominal    = post_safe('nominal');
        $penerima   = post_safe('penerima');
        if ($id === '') {
            $data = array(
                'tanggal' => date("Y-m-d"),
                'kode' => $kode,
                'tanggal_kegiatan' => $tanggal,
                'tanggal_renbut' => $tgl_renbut,
                'id_uraian' => $id_uraian,
                'keterangan' => $keterangan,
                'jml_renbut' => $jml_renbut,
                'nominal' => $jml_renbut,
                'penerima' => $penerima
            );
            $this->db->insert('rencana_kebutuhan', $data);
            $id_renbut = $this->db->insert_id();
        } else {
            $renbut = $this->db->get_where('rencana_kebutuhan', array('id_renbut' => $id))->row();
            $data = array(
                'tanggal_kegiatan' => $tanggal,
                'tanggal_renbut' => $tgl_renbut,
                'kode' => $kode,
                'id_uraian' => $id_uraian,
                'keterangan' => $keterangan,
                'jml_renbut' => $jml_renbut,
                'nominal' => $jml_renbut-$renbut->cashbon,
                'penerima' => $penerima
            );
            $this->db->where('id_renbut', $id);
            $this->db->update('rencana_kebutuhan', $data);
            $id_renbut = $id;
        }
        $this->db->where('id', $id_out);
        $this->db->update('kasir', array('id_renbut' => $id_renbut));
        $result['status'] = TRUE;
        $result['id_renbut']= $id_renbut;
        return $result;
    }
    
    function delete_renbut($id) {
        $this->db->delete('rencana_kebutuhan', array('id_renbut' => $id));
    }
    
    /*DROPPING*/
    function get_data_dropping($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            $q.=" and rk.tanggal_renbut between '".$search['awal']."' and '".$search['akhir']."'";
        }
        if ($search['satker'] !== '') {
            $q.=" and s.id = '".$search['satker']."'";
        }
        if ($search['proja'] !== '') {
            $q.=" having ma_proja like ('%".$search['proja']."%')";
        }
        if ($search['pjawab'] !== '') {
            $q.=" and rk.penerima like ('%".$search['pjawab']."%')";
        }
        $q.=" order by rk.tanggal asc";
        $sql = "select rk.*, s.nama as satker, u.kode as ma_proja, u.uraian
            from rencana_kebutuhan rk
            join uraian u on (rk.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
            where rk.kode_cashbon = ''";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_dropping() {
        $id         = post_safe('id_dropping');
        $tanggal    = date2mysql(post_safe('tanggal'));
        $id_uraian  = post_safe('id_uraian');
        $keterangan = post_safe('keterangan');
        $jml_dropping = currencyToNumber(post_safe('jml_dropping'));
//        $cashbon    = post_safe('cashbon');
//        $nominal    = post_safe('nominal');
//        $penerima   = post_safe('penerima');
        $data = array(
            'tanggal' => $tanggal,
            'id_uraian' => $id_uraian,
            'keterangan' => $keterangan,
            'jml_dropping' => $jml_dropping,
            
        );
        if ($id === '') {
            $this->db->insert('rencana_kebutuhan', $data);
            $id_dropping = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('rencana_kebutuhan', $data);
            $id_dropping = $id;
        }
        $result['status'] = TRUE;
        $result['id_dropping']= $id_dropping;
        return $result;
    }
    
    function approve_dropping($param) {
        $this->db->where('id_renbut', $param['id']);
        $this->db->update('rencana_kebutuhan', array('verificator' => $this->session->userdata('id_user'), 'status' => $param['status'], 'date_verify' => date("Y-m-d"), 'jml_dropping' => currencyToNumber($param['jumlah'])));
        return array('status' => $param['status']);
    }
    
    function delete_dropping($id) {
        $this->db->delete('satker', array('id' => $id));
    }
    
    /*PENCAIRAN*/
    function get_data_pencairan_renbut($limit = null, $start = null, $search = null) {
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
            where rk.status = 'Disetujui'";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_pencairan() {
        $id_renbut  = post_safe('id_renbut');
        $nominal    = currencyToNumber(post_safe('nominal'));
        $id_akun    = post_safe('kode_perkiraan');
        
        $data_cair  = array(
            'nominal'  => $nominal,
            'tanggal_cair' => date("Y-m-d"),
            'id_akun_rekening' => $id_akun
        );
        $this->db->where('id_renbut', $id_renbut);
        $this->db->update('rencana_kebutuhan', $data_cair);
        return array('status' => TRUE, 'id' => $id_renbut);
    }
    
    /*CASHBON*/
    function get_data_cashbon($limit = null, $start = null, $search = null) {
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
        $sql = "select rk.*, s.nama as satker, u.kode as ma_proja from rencana_kebutuhan rk
            join uraian u on (rk.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
            where rk.id_renbut is not NULL and rk.cashbon != '0'";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function get_data_cashbon_detail($id) {
        $sql = "select rk.*, s.id as id_satker, s.nama as satker, year(rk.tanggal) as tahun_anggaran, 
            p.nama_program,k.nama_kegiatan, sk.nama_sub_kegiatan, u.uraian, rk.jml_renbut,
            u.kode as ma_proja from rencana_kebutuhan rk
            join uraian u on (rk.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
        where rk.id_renbut = '$id'";
        return $this->db->query($sql);
    }
    
    function save_cashbon() {
        $id         = post_safe('id_renbut');
        $tanggal    = date2mysql(post_safe('tanggal'));
        $id_uraian  = post_safe('id_uraian');
        $keterangan = post_safe('keterangan');
        $jml_cashbon= currencyToNumber(post_safe('jml_cashbon'));
        $penerima   = post_safe('penerima');
        if ($id === '') {
            $data = array(
                'tanggal' => $tanggal,
                'tanggal_kegiatan' => $tanggal,
                'id_uraian' => $id_uraian,
                'keterangan' => $keterangan,
                'cashbon' => $jml_cashbon,
                'penerima' => $penerima,
                'verificator' => $this->session->userdata('id_user'),
                'status' => 'Disetujui',
                'date_verify' => date("Y-m-d"),
                'tanggal_cair' => date("Y-m-d")
            );
            $this->db->insert('rencana_kebutuhan', $data);
            $id_renbut = $this->db->insert_id();
        } else {
            $data = array(
                'id_uraian' => $id_uraian,
                'keterangan' => $keterangan,
                'cashbon' => $jml_cashbon,
                'penerima' => $penerima,
                'status' => 'Disetujui',
            );
            $this->db->where('id_renbut', $id);
            $this->db->update('rencana_kebutuhan', $data);
            $id_renbut = $id;
        }
        $result['status'] = TRUE;
        $result['id_renbut']= $id_renbut;
        return $result;
    }
    
    /*PAGU*/
    function load_pagu() {
        $sql = "select * from pagu order by nama";
        return $this->db->query($sql);
    }
    
    function get_data_pagu($limit = null, $start = null, $search = null) {
        $q = null;
        if (($search['key'] !== 'undefined') and ($search['key'] !== '')) {
            $q.=" and p.tahun = '".$search['key']."'";
        }
        if ($search['id'] !== 'undefined' and $search['id'] !== '') {
            $q.=" and p.id = '".$search['id']."'";
        }
        $q.=" order by p.tahun desc, s.nama asc";
        $sql = "select p.*, s.nama as satker from pagu_anggaran p join satker s on (p.id_satker = s.id) where p.id is not NULL";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_pagu() {
        $id         = post_safe('id_pagu');
        $jml_pagu   = currencyToNumber(post_safe('nama')); // Jumlah Pagu
        $tahun      = post_safe('year');
        $satker     = post_safe('id_satker');
        $data = array(
            'id_satker' => $satker,
            'tahun' => $tahun,
            'pagu' => $jml_pagu,
            'id_user' => $this->session->userdata('id_user')
        );
        if ($id === '') {
            $this->db->insert('pagu_anggaran', $data);
            $id_unit = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('pagu_anggaran', $data);
            $id_unit = $id;
        }
        $result['status'] = TRUE;
        $result['id_pagu']= $id_unit;
        return $result;
    }
    
    function delete_pagu($id) {
        $this->db->delete('pagu_anggaran', array('id' => $id));
    }
    
    function get_data_pemasukkan($limit, $start, $search) {
        $q = NULL;
        $sql = "select p.*, u.kode, s.nama as sub_rekening, u.uraian from penerimaan p 
            join sub_sub_sub_sub_rekening s on (p.id_rekening = s.id)
            join uraian u on (p.id_uraian = u.id)
            where p.id is not NULL order by tanggal desc, id desc";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function get_data_pemasukkan_detail($id) {
        $sql = "select p.*, p.penyetor as penerima, u.kode as ma_proja, s.nama as sub_rekening, u.uraian, p.pemasukkan as nominal, p.id_rekening as id_akun_rekening from penerimaan p 
            join sub_sub_sub_sub_rekening s on (p.id_rekening = s.id)
            join uraian u on (p.id_uraian = u.id)
            where p.id = '$id'";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function save_pemasukkan() {
        $id         = post_safe('id_pemasukkan');
        $id_uraian  = post_safe('id_uraian');
        $kode_akun  = post_safe('kode_perkiraan');
        $jumlah     = currencyToNumber(post_safe('nominal'));
        
        $data = array(
            'tanggal' => date("Y-m-d"),
            'id_rekening' => $kode_akun,
            'id_uraian' => $id_uraian,
            'pemasukkan' => $jumlah
        );
        
        if ($id === '') {
            $this->db->insert('penerimaan', $data);
            $id = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('penerimaan', $data);
            $id = $id;
        }
        
        return array('status' => TRUE, 'id_penerimaan' => $id);
    }
    
    function kasir_save() {
        $idkasir= post_safe('id_kasir');
        $tanggal= date2mysql(post_safe('tanggal'));
        $jenis  = post_safe('jenis');
        $no     = post_safe('no');
        $sumber = post_safe('sumberdana');
        $kd_perkiraan = post_safe('kode_perkiraan');
        $maproja= post_safe('id_kode');
        $penyetor= post_safe('nama_user');
        $jumlah = currencyToNumber(post_safe('jumlah'));
        $perwabku= post_safe('perwabku');
        $id_renbut = post_safe('id_renbut');
        $uraian = post_safe('uraian');
        $id_rek_pwk = post_safe('kode_perkiraan_pwk');
        if ($idkasir === '') {
            if ($jenis === 'bkk' and $id_renbut === '') {
                $data_renbut = array(
                    'tanggal' => date("Y-m-d"),
                    'kode_cashbon' => $no,
                    'tanggal_kegiatan' => $tanggal,
                    'id_uraian' => $maproja,
                    'keterangan' => $uraian,
                    'cashbon' => $jumlah,
                    'penerima' => $penyetor
                );
                $this->db->insert('rencana_kebutuhan', $data_renbut);
                $id_rencana = $this->db->insert_id();
            }
            $jns = NULL;
            if ($jenis === 'bkk') {
                $jns = 'BKK';
            }
            if ($jenis === 'bkm') {
                $jns = 'BKM';
            }
            if ($jenis === 'mutasi') {
                $jns = 'MTS';
            }
            $data = array(
                'kode' => $no,
                'sumberdana' => $sumber,
                'tanggal' => $tanggal,
                'id_rekening' => $kd_perkiraan,
                'id_renbut' => ($id_renbut !== '')?$id_renbut:NULL, // NULL jika cashbon
                'id_uraian' => $maproja,
                'pengeluaran' => $jumlah,
                'penerima' => $penyetor,
                'perwabku' => !empty($perwabku)?$perwabku:NULL,
                'jenis' => $jns,
                'id_rekening_pwk' => ($id_rek_pwk !== '')?$id_rek_pwk:NULL
            );
            $this->db->insert('kasir', $data);
            $id = $this->db->insert_id();

        } else {
            $this->db->delete('rencana_kebutuhan', array('id_renbut' => $id_renbut));
            if ($jenis === 'bkk' and $id_renbut === '') {
                $data_renbut = array(
                    'tanggal' => date("Y-m-d"),
                    'kode_cashbon' => $no,
                    'tanggal_kegiatan' => $tanggal,
                    'id_uraian' => $maproja,
                    'keterangan' => $uraian,
                    'cashbon' => $jumlah,
                    'penerima' => $penyetor
                );
                $this->db->insert('rencana_kebutuhan', $data_renbut);
                $id_rencana = $this->db->insert_id();
            }
            $data = array(
                'kode' => $no,
                'sumberdana' => $sumber,
                'tanggal' => $tanggal,
                'id_rekening' => $kd_perkiraan,
                'id_renbut' => ($id_renbut !== '')?$id_renbut:NULL,
                'id_uraian' => $maproja,
                'pengeluaran' => $jumlah,
                'penerima' => $penyetor,
                'perwabku' => !empty($perwabku)?$perwabku:NULL,
                'id_rekening_pwk' => ($id_rek_pwk !== '')?$id_rek_pwk:NULL
            );
            $this->db->where('id', $idkasir);
            $this->db->update('kasir', $data);
            $id = $idkasir;
        }

        $data_cair  = array(
            'nominal'  => $jumlah,
            'tanggal_cair' => $tanggal,
            'id_akun_rekening' => $kd_perkiraan
        );
        $this->db->where(array('id_uraian' => $maproja, 'YEAR(tanggal)' => date("Y")));
        $this->db->update('rencana_kebutuhan', $data_cair);
        //$get = $this->db->query("select id_renbut from rencana_kebutuhan where id_uraian = '$maproja' and YEAR(tanggal) = '".date("Y")."'")->row();
        $result['id'] = $id;
        $result['act'] = 'bkk';

        $result['status'] = TRUE;
        return $result;
    }
    
    function print_bukti_kas($id, $jenis) {
        $sql = "select p.*, p.penerima, u.kode as ma_proja, s.nama as sub_rekening, u.uraian, p.pengeluaran as nominal, p.id_rekening as id_akun_rekening from kasir p 
        join sub_sub_sub_sub_rekening s on (p.id_rekening = s.id)
        join uraian u on (p.id_uraian = u.id)
        where p.id = '$id'";
        return $this->db->query($sql);
    }
    
    function jurnal_save() {
        $cek = $this->db->query("select r.posisi from sub_sub_sub_sub_rekening s4r
            join sub_sub_sub_rekening s3r on (s4r.id_sub_sub_sub_rekening = s3r.id)
            join sub_sub_rekening s2r on (s3r.id_sub_sub_rekening = s2r.id)
            join sub_rekening sr on (s2r.id_sub_rekening = sr.id)
            join rekening r on (sr.id_rekening = r.id)
            where s4r.id = '".post_safe('kode_perkiraan')."'")->row();
        if ($cek->posisi === 'D') {
            $data = array(
                'tanggal' => date2mysql(post_safe('tanggal')),
                'id_rekening' => post_safe('kode_perkiraan'),
                'id_uraian' => post_safe('id_kode'),
                'perwabku' => post_safe('perwabku'),
                'debet' => currencyToNumber(post_safe('jumlah'))
            );
            $this->db->insert('jurnal', $data);
        } else {
            $data = array(
                'tanggal' => date2mysql(post_safe('tanggal')),
                'id_rekening' => post_safe('kode_perkiraan'),
                'id_uraian' => post_safe('id_kode'),
                'perwabku' => post_safe('perwabku'),
                'kredit' => currencyToNumber(post_safe('jumlah'))
            );
            $this->db->insert('jurnal', $data);
        }
        $id = $this->db->insert_id();
        return $id;
    }
    
    function get_data_jurnal($limit, $start, $search) {
        $q = NULL;
        $sql = "select * from jurnal order by id";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_jurnal_transaksi() {
        
        $this->db->trans_begin();
        $kode_debet = post_safe('kode_perkiraan_d'); // array
        $jumlah_d   = post_safe('jumlah_d'); // array
        $kode_kredit= post_safe('kode_perkiraan_k'); // array
        $jumlah_k   = post_safe('jumlah_k'); // array
        
        $kode_trans = post_safe('kode_transaksi');
        $tanggal    = date2mysql(post_safe('tanggal'));
        $keterangan = post_safe('uraian');
        
        foreach ($kode_debet as $key => $kode_d) {
            $data2 = array(
                'kode_nota' => $kode_trans,
                'tanggal' => $tanggal,
                'id_rekening' => $kode_d,
                'debet' => currencyToNumber($jumlah_d[$key]),
                'keterangan' => $keterangan
            );
            $this->db->insert('jurnal', $data2);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
        }
        
        foreach ($kode_kredit as $key => $kode_k) {
            $data = array(
                'kode_nota' => $kode_trans,
                'tanggal' => $tanggal,
                'id_rekening' => $kode_k,
                'kredit' => currencyToNumber($jumlah_k[$key]),
                'keterangan' => $keterangan
            );
            $this->db->insert('jurnal', $data);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['status'] = FALSE;
        } else {
            $this->db->trans_commit();
            $result['status'] = TRUE;
        }
        
        return $result;
    }
    
    function delete_jurnal($id) {
        $this->db->delete('jurnal', array('id' => $id));
    }
    
    function get_data_kasir($limit, $start, $search) {
        $q = NULL;
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            $q.=" and pg.tanggal between '".$search['awal']."' and '".$search['akhir']."'";
        }
        if ($search['jenis'] !== '') {
            $q.=" and pg.jenis = '".$search['jenis']."'";
        }
        if ($search['kegiatan'] !== '') {
            $q.=" and u.uraian like ('%".$search['kegiatan']."%')";
        }
        if ($search['png_jwb'] !== '') {
            $q.=" and pg.penerima like ('%".$search['png_jwb']."%')";
        }
        $sql = "select pg.id, pg.kode, pg.sumberdana, pg.tanggal, pg.id_rekening, pg.id_renbut, 
                pg.id_uraian, pg.pengeluaran as nominal, pg.penerima as penanggung_jwb, pg.perwabku, substr(pg.kode,1,3) as kode_trans, 
                u.uraian as keterangan, IFNULL(pg.id_renbut,'') as renbut 
                from kasir pg
                join uraian u on (pg.id_uraian = u.id)
                where pg.id is not NULL $q order by pg.id desc";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function get_data_kasir_by_id($id, $transaksi) {
        $sql = "select pg.*, IFNULL(pg.id_rekening,'') as id_rekening, substr(pg.kode,1,3) as kode_trans, 
            u.kode as kode_uraian, u.uraian as keterangan_ma, IFNULL(pg.id_renbut,'') as renbut, s4r.nama as rekening, s.nama as satker,
            CONCAT_WS(' / ',s.nama, p.status, p.nama_program, k.nama_kegiatan, sk.nama_sub_kegiatan) as keterangan,
            IFNULL(pg.id_rekening_pwk,'') as id_rekening_pwk, IFNULL(s4r2.nama,'') as rekening_pwk
            from kasir pg
            join uraian u on (pg.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id) 
            left join sub_sub_sub_sub_rekening s4r on (pg.id_rekening = s4r.id)
            left join sub_sub_sub_sub_rekening s4r2 on (pg.id_rekening_pwk = s4r2.id)
            where pg.id = '$id'";
        return $this->db->query($sql);
    }
    
    function get_data_perwabku($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['id'] !== '') {
            $q.=" and pw.id = '".$search['id']."'";
        }
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            $q.=" and pw.tanggal between '".$search['awal']."' and '".$search['akhir']."'";
        }
        if ($search['nomorpwk'] !== '') {
            $q.=" and pw.kode like ('%".$search['nomorpwk']."%')";
        }
        if ($search['nomorbkk'] !== '') {
            $q.=" and pg.kode like ('%".$search['nomorbkk']."%')";
        }
        $sql = "select pw.*, pw.kode as kode_pwk, pg.tanggal as tanggal_pengeluaran, sum(pg.pengeluaran) as dana, 
            pg.penerima, pg.kode, YEAR(pg.tanggal) as thn_anggaran, s.nama as satker,
            u.kode as kode_ma, s.kode as kode_satker, us.username,
            p.nama_program, k.nama_kegiatan, sk.nama_sub_kegiatan, u.uraian
            from perwabku pw
            join detail_perwabku dp on (dp.id_perwabku = pw.id)
            join kasir pg on (dp.id_pengeluaran = pg.id)
            join uraian u on (pg.id_uraian = u.id)
            join sub_kegiatan sk on (u.id_sub_kegiatan = sk.id)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
            join users us on (pw.id_user = us.id)
            where pw.id is not NULL $q group by pw.id";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation =" limit $start , $limit";
        }
        $result = $this->db->query($sql . $limitation)->result();
        foreach ($result as $key => $val) {
            $sql_bkk = "select k.kode as kodes
                from detail_perwabku dp 
                join kasir k on (dp.id_pengeluaran = k.id)
                where dp.id_perwabku = '".$val->id."'";
            $child_bkk = $this->db->query($sql_bkk)->result();
            $result[$key]->perwabku = $child_bkk;
            
            $sql_png = "select k.penerima as png_jwb
                from detail_perwabku dp 
                join kasir k on (dp.id_pengeluaran = k.id)
                where dp.id_perwabku = '".$val->id."'";
            $child_png = $this->db->query($sql_png)->result();
            $result[$key]->png_jwb = $child_png;
        }
        $queryAll = $this->db->query($sql);
        $data['data'] = $result;
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_perwabku() {
        $this->db->trans_begin();
        $id_pengeluaran = post_safe('id_nomorbkk'); // array
        $tanggal        = date2mysql(post_safe('tanggal'));
        $kode_pwk       = post_safe('nomor');
        $kelengkapan    = post_safe('kelengkapan');
        $catatan        = post_safe('catatan');
        $dana           = currencyToNumber(post_safe('dana'));
        
        if (is_array($id_pengeluaran)) {
            $data = array(
                'kode' => $kode_pwk,
                'tanggal' => $tanggal,
                'dana_digunakan' => $dana,
                'kelengkapan' => $kelengkapan,
                'catatan' => $catatan,
                'id_user' => $this->session->userdata('id_user')
            );
            $this->db->insert('perwabku', $data);

            $id_perwabku = $this->db->insert_id();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
            foreach ($id_pengeluaran as $data) {
                $data_detail = array(
                    'id_perwabku' => $id_perwabku,
                    'id_pengeluaran' => $data
                );
                $this->db->insert('detail_perwabku', $data_detail);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $result['status'] = FALSE;
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            } else {
                $this->db->trans_commit();
                $result['status'] = TRUE;
            }
        } else {
            $result['status'] = FALSE;
        }
        
        return $result;
    }
    
    function delete_perwabku($id) {
        $this->db->delete('perwabku', array('id' => $id));
    }
}
?>
