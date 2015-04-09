<?php

class M_masterdata extends CI_Model {
    
    /*UNIT*/
    function load_satker() {
        $sql = "select * from satker order by kode asc";
        return $this->db->query($sql);
    }
    
    function get_data_unit($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['key'] !== 'undefined') {
            $q.=" and nama like '%".$search['key']."%'";
        }
        if ($search['id'] !== 'undefined') {
            $q.=" and id = '".$search['id']."'";
        }
        $q.=" order by convert(`kode`, decimal) asc";
        $sql = "select * from satker where id is not NULL";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_unit() {
        $id     = post_safe('id_unit');
        $kode   = post_safe('kode');
        $nama   = post_safe('nama');
        $data = array(
            'nama' => $nama,
            'kode' => $kode
        );
        if ($id === '') {
            $this->db->insert('satker', $data);
            $id_unit = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('satker', $data);
            $id_unit = $id;
        }
        $result['status'] = TRUE;
        $result['id_unit']= $id_unit;
        return $result;
    }
    
    function delete_unit($id) {
        $this->db->delete('satker', array('id' => $id));
    }
    
    /*PROGRAM*/
    function get_data_program($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['key'] !== 'undefined') {
            $q.=" and (p.nama_program like '%".$search['key']."%' or s.nama like '%".$search['key']."%')";
        }
        if ($search['id'] !== 'undefined') {
            $q.=" and p.id = '".$search['id']."'";
        }
        $q.=" order by convert(s.kode, decimal) asc";
        $sql = "select p.*, s.nama as satker, s.kode from program p
            join satker s on (p.id_satker = s.id) where p.id is not NULL";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_program() {
        $id_hide= post_safe('id');
        $id     = post_safe('id_program');
        $id_satker  = post_safe('id_satker');
        $nama   = strtoupper(post_safe('nama'));
        $status = post_safe('status');
        $data = array(
            'kode' => $id,
            'id_satker' => $id_satker,
            'nama_program' => $nama,
            'status' => $status
        );
        if ($id_hide === '') {
            $this->db->insert('program', $data);
            $id_program = $id;
        } else {
            $this->db->where('id', $id_hide);
            $this->db->update('program', $data);
            $id_program = $id;
        }
        $result['status'] = TRUE;
        $result['id_program']= $id_program;
        return $result;
    }
    
    function delete_program($id) {
        $this->db->delete('program', array('id' => $id));
    }
    
    function load_all_data_program($satker = NULL) {
        $q = NULL;
        if ($satker !== NULL) {
            $q = "where id_satker = '$satker'";
        }
        $sql = "select p.*, s.nama as satker from program p join satker s on (p.id_satker = s.id) $q order by p.id_satker asc, p.kode asc";
        return $this->db->query($sql);
    }
    
    function get_nama_proja($id_satker) {
        $sql = "select * from satker where id = '$id_satker'";
        return $this->db->query($sql);   
    }
    
    /*KEGIATAN PROGRAM*/
    function get_data_keg_program($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['key'] !== 'undefined') {
            $q.=" and (k.nama_kegiatan like '%".$search['key']."%' or p.nama_program like '%".$search['key']."%')";
        }
        if ($search['id'] !== 'undefined') {
            $q.=" and k.id = '".$search['id']."'";
        }
        $q.=" order by p.id asc, k.kode asc";
        $sql = "select k.*, p.nama_program, k.kode as kode, s.nama as satker,
            s.id as id_satker, p.status, k.kode as kode_kegiatan, p.kode as kode_program 
            from kegiatan k
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
                where k.id is not NULL";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_keg_program() {
        $id     = $_POST['id_keg_program'];
        $kode   = $_POST['kode'];
        $nama   = $_POST['kegiatan'];
        $id_prog= $_POST['id_program'];
        $data   = array(
            'kode' => $kode,
            'nama_kegiatan' => $nama,
            'id_program' => $id_prog
        );
        
        if ($id === '') {
            $this->db->insert('kegiatan', $data);
            $id_kegiatan = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('kegiatan', $data);
            $id_kegiatan = $id;
        }
        $result['status'] = TRUE;
        $result['id'] = $id_kegiatan;
        return $result;
    }
    
    function delete_keg_program($id) {
        $this->db->delete('kegiatan', array('id' => $id));
    }
    
    /*SUB KEGIATAN*/
    function get_data_sub_kegiatan($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['key'] !== 'undefined') {
            $q.=" and (k.nama_kegiatan like '%".$search['key']."%' or p.nama_program like '%".$search['key']."%')";
        }
        if ($search['id'] !== 'undefined') {
            $q.=" and k.id = '".$search['id']."'";
        }
        $q.=" order by p.id asc, k.kode asc";
        $sql = "select sk.*, p.id as id_program,  k.kode as kode_sub_kegiatan, k.nama_kegiatan, sk.kode as kode, s.nama as satker,
            s.id as id_satker, p.status, k.kode as kode_kegiatan, p.kode as kode_program, sk.nama_sub_kegiatan 
            from sub_kegiatan sk
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
                where k.id is not NULL";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_sub_kegiatan() {
        $id     = $_POST['id_sub_kegiatan'];
        $kode   = $_POST['kode'];
        $nama   = $_POST['sub_kegiatan'];
        $id_keg = $_POST['id_kegiatan'];
        $data   = array(
            'kode' => $kode,
            'id_kegiatan' => $id_keg,
            'nama_sub_kegiatan' => $nama
        );
        
        if ($id === '') {
            $this->db->insert('sub_kegiatan', $data);
            $id_kegiatan = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('sub_kegiatan', $data);
            $id_kegiatan = $id;
        }
        $result['status'] = TRUE;
        $result['id'] = $id_kegiatan;
        return $result;
    }
    
    function delete_sub_kegiatan($id) {
        $this->db->delete('sub_kegiatan', array('id' => $id));
    }
    
    /*URAIAN*/
    function get_data_uraian($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['key'] !== 'undefined') {
            $q.=" and (k.nama_kegiatan like '%".$search['key']."%' or p.nama_program like '%".$search['key']."%')";
        }
        if ($search['id'] !== 'undefined') {
            $q.=" and k.id = '".$search['id']."'";
        }
        $q.=" order by p.id asc, k.kode asc";
        $sql = "select u.*, u.id as id_uraian, u.kode as kode_uraian, p.id as id_program, k.id as id_kegiatan, k.kode as kode_sub_kegiatan, k.nama_kegiatan, u.kode as kode, s.nama as satker,
            s.id as id_satker, p.status,  k.kode as kode_kegiatan, sk.kode as kode_sub_kegiatan, p.kode as kode_program, sk.nama_sub_kegiatan, u.kode as kode_uraian,
            p.nama_program
            from sub_kegiatan sk
            left join uraian u on (sk.id = u.id_sub_kegiatan)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
                where k.id is not NULL";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_uraian() {
        $id     = $_POST['id_uraian'];
        $kode   = $_POST['kode'];
        $nama   = $_POST['uraian'];
        $id_sub_keg = $_POST['id_sub_kegiatan'];
        $data   = array(
            'kode' => $kode,
            'id_sub_kegiatan' => $id_sub_keg,
            'uraian' => $nama
        );
        
        if ($id === '') {
            $this->db->insert('uraian', $data);
            $id_kegiatan = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('uraian', $data);
            $id_kegiatan = $id;
        }
        $result['status'] = TRUE;
        $result['id'] = $id_kegiatan;
        return $result;
    }
    
    function delete_uraian($id) {
        $this->db->delete('uraian', array('id' => $id));
    }
    
    /*SUB URAIAN*/
    function get_data_sub_uraian($limit = null, $start = null, $search = null) {
        $q = null;
//        if ($search['key'] !== 'undefined') {
//            $q.=" and (k.nama_kegiatan like '%".$search['key']."%' or p.nama_program like '%".$search['key']."%')";
//        }
        if ($search['tahun'] !== '') {
            $q.=" and su.tahun = '".$search['tahun']."'";
        }
        if ($search['satker'] !== '') {
            $q.=" and s.id = '".$search['satker']."'";
        }
        if ($search['suburaian'] !== '') {
            $q.=" and su.keterangan like ('%".$search['suburaian']."%')";
        }
        if ($search['status'] !== '') {
            $q.=" and p.status = '".$search['status']."'";
        }
        $q.=" order by p.id asc, k.kode asc";
        $sql = "select su.*, u.id as id_uraian, u.kode as kode_uraian, p.id as id_program, k.id as id_kegiatan, k.kode as kode_sub_kegiatan, k.nama_kegiatan, 
            u.kode as kode, CONCAT_WS(' ',s.nama, '(', p.status, ')') as satker, s.kode as kode_satker,
            s.id as id_satker, u.uraian, u.kode as id_sub_kegiatan, p.status,  
            k.kode as kode_kegiatan, 
            sk.kode as kode_sub_kegiatan, 
            u.kode as code,
            p.kode as kode_program, sk.nama_sub_kegiatan, u.kode as kode_uraian,
            p.nama_program
            from sub_uraian su
            join uraian u on (su.id_uraian = u.id)
            join sub_kegiatan sk on (sk.id = u.id_sub_kegiatan)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
                where k.id is not NULL";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_sub_uraian() {
        $id         = $_POST['id_sub_uraian'];
        $id_uraian  = post_safe('id_uraian');
        $kuat       = post_safe('kuat');
        $keterangan = post_safe('sub_uraian');
        $vol_orang  = (post_safe('vol_orang') !== '')?post_safe('vol_orang'):'1';
        $vol_hari   = (post_safe('haribulan') !== '')?post_safe('haribulan'):'1';
        $hrg_satuan = currencyToNumber(post_safe('harga'));
        $subtotal   = $vol_hari*$vol_orang*$hrg_satuan;
        $tahun      = post_safe('tahun');
        
        $kode = $this->db->query("select IFNULL(max(kode),0)+1 as kode from sub_uraian where id_uraian = '$id_uraian'")->row();
        
        if ($id === '') {
            $data   = array(
                'kode' => $kode->kode,
                'id_uraian' => $id_uraian,
                'tahun' => $tahun,
                'keterangan' => $keterangan,
//                'data_kuat_org' => $kuat,
//                'vol_orang' => $vol_orang,
//                'vol_hari_perbulan' => $vol_hari,
                'harga_satuan' => $hrg_satuan,
                'sub_total' => $subtotal
            );
            $this->db->insert('sub_uraian', $data);
            $id_sub_uraian = $this->db->insert_id();
        } else {
            $data   = array(
                'id_uraian' => $id_uraian,
                'tahun' => $tahun,
                'keterangan' => $keterangan,
//                'data_kuat_org' => $kuat,
//                'vol_orang' => $vol_orang,
//                'vol_hari_perbulan' => $vol_hari,
                'harga_satuan' => $hrg_satuan,
                'sub_total' => $subtotal
            );
            $this->db->where('id', $id);
            $this->db->update('sub_uraian', $data);
            $id_sub_uraian = $id;
        }
        $result['status'] = TRUE;
        $result['id'] = $id_sub_uraian;
        return $result;
    }
    
    function delete_sub_uraian($id) {
        $this->db->delete('sub_uraian', array('id' => $id));
    }
    
    /*SUB SUB URAIAN*/
    function get_data_sub_sub_uraian($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['key'] !== 'undefined') {
            $q.=" and (k.nama_kegiatan like '%".$search['key']."%' or p.nama_program like '%".$search['key']."%')";
        }
        if ($search['id'] !== 'undefined') {
            $q.=" and k.id = '".$search['id']."'";
        }
        $q.=" order by p.id asc, k.kode asc";
        $sql = "select ssu.*, ssu.kode as kode_ssu, su.kode as kode_su, u.id as id_uraian, u.kode as kode_uraian, 
            p.id as id_program, k.id as id_kegiatan, k.kode as kode_sub_kegiatan, k.nama_kegiatan, 
            u.kode as kode, s.nama as satker,
            s.id as id_satker, u.uraian, u.kode as id_sub_kegiatan, p.status,  
            k.kode as kode_kegiatan, 
            sk.kode as kode_sub_kegiatan, 
            u.kode as code,
            p.kode as kode_program, sk.nama_sub_kegiatan, u.kode as kode_uraian,
            p.nama_program
            from sub_sub_uraian ssu
            join sub_uraian su on (ssu.id_sub_uraian = su.id)
            join uraian u on (su.id_uraian = u.id)
            join sub_kegiatan sk on (sk.id = u.id_sub_kegiatan)
            join kegiatan k on (sk.id_kegiatan = k.id)
            join program p on (k.id_program = p.id)
            join satker s on (p.id_satker = s.id)
                where k.id is not NULL";
        $limitation = null;
        $limitation.=" limit $start , $limit";
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_sub_sub_uraian() {
        $id         = $_POST['id_sub_sub_uraian'];
        $id_uraian  = post_safe('id_sub_uraian');
        $kuat       = post_safe('kuat');
        $keterangan = post_safe('sub_sub_uraian');
        $vol_orang  = (post_safe('vol_orang') !== '')?post_safe('vol_orang'):'1';
        $vol_hari   = (post_safe('haribulan') !== '')?post_safe('haribulan'):'1';
        $hrg_satuan = currencyToNumber(post_safe('harga'));
        $subtotal   = $vol_hari*$vol_orang*$hrg_satuan;
        
        $kode = $this->db->query("select IFNULL(max(kode),0)+1 as kode from sub_sub_uraian where id_sub_uraian = '$id_uraian'")->row();
        $data   = array(
            'kode' => $kode->kode,
            'id_sub_uraian' => $id_uraian,
            'keterangan' => $keterangan,
            'data_kuat_org' => $kuat,
            'vol_orang' => $vol_orang,
            'vol_hari_perbulan' => $vol_hari,
            'harga_satuan' => $hrg_satuan,
            'sub_total' => $subtotal
        );
        
        if ($id === '') {
            $this->db->insert('sub_sub_uraian', $data);
            $id_sub_sub_uraian = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('sub_sub_uraian', $data);
            $id_sub_sub_uraian = $id;
        }
        $result['status'] = TRUE;
        $result['id'] = $id_sub_sub_uraian;
        return $result;
    }
    
    function delete_sub_sub_uraian($id) {
        $this->db->delete('sub_sub_uraian', array('id' => $id));
    }
    
    /*USER ACCOUNT*/
    function group_get_data($limit, $start, $search){
        $q = '';
        if (isset($search['id']) && ($search['id'] !== '')) {
            $q .= " and id = '".$search['id']."'";
        }
        $limit = " limit $start, $limit ";
        $sql = "select * from user_group where id is not null $q order by nama";
        $query = $this->db->query($sql . $limit);
        $ret['data'] = $query->result();
        $ret['jumlah'] = $this->db->query($sql)->num_rows();
        return $ret;
    }

    function get_user_group(){
        $query = $this->db->order_by('nama')->get('user_group')->result();
        return $query;
    }

    function group_update_data($data){
        if ($data['id'] === '') {
            // insert
            $this->db->insert('user_group', $data);
            $id = $this->db->insert_id();
        }else{
            // update
            $this->db->where('id', $data['id']);
            $this->db->update('user_group', $data);
            $id = $data['id'];
        }

        return $id;

    }

    function group_delete_data($id) {
        $db = "delete from user_group where id = '$id'";
        $this->db->query($db);
    }

    function user_get_data($limit, $start, $search) {
        $q = '';
        $limit = " limit $start, $limit ";
        if (isset($search['id']) && $search['id'] != '') {
            $q.=" and u.id = '" . $search['id'] . "'";
        }
        if (isset($search['nama']) && $search['nama'] != '') {
            $q.=" and p.nama like ('%$search[nama]%')";
        }
        if (isset($search['username']) && $search['username'] != '') {
            $q.=" and u.username like ('%$search[username]%')";
        }
        $sql = "select u.*, 
        ug.id as group_id, ug.nama as user_group 
        from users u
        left join user_group ug on (ug.id = u.id_user_group)
        where u.id is not NULL $q
        order by username asc ";

        //echo $sql."<br/><br/>";
        $query = $this->db->query($sql . $limit);
        $ret['data'] = $query->result();
        $ret['jumlah'] = $this->db->query($sql)->num_rows();
        return $ret;
    }

    function user_add_data($data) {
        if (post_safe('id_user_account') == '') {
            $this->db->insert('users', $data);
            $result['status'] = TRUE;
            $result['id'] = $this->db->insert_id();
            $result['act'] = 'add';
        } else {
            $data['password'] = md5('1234');
            $this->db->where('id', post_safe('id_user_account'));
            $this->db->update('users', $data);
            $result['status'] = TRUE;
            $result['id'] = post_safe('id');
            $result['act'] = 'edit';
        }
        return $result;
    }

    function user_delete_data($id) {
        $db = "delete from users where id = '$id'";
        $this->db->query($db);
    }
    
    function reset_password($id) {
        $sql = "update users set password = '".md5(1234)."' where id = '$id'";
        $this->db->query($sql);
    }

    function get_unit($q){
        $sql = "select * from unit where nama like ('%$q%') order by locate('$q', nama)";
        return $this->db->query($sql);
    }

    function detail_user_data($id) {
        $sql = "select p.*, p.id as id_penduduk, dp.* from penduduk p
            left join dinamis_penduduk dp on (p.id = dp.penduduk_id)
            where p.id =  '$id'";
         // echo $sql;
        $query = $this->db->query($sql);
        return $query->row();
    }

    function group_privileges_data($id) {
        $sql = "select * from user_group_privileges where 
             user_group_id = '" . $id . "'";
        //echo $sql;
        $query = $this->db->query($sql)->result();
        $data = array();
        foreach ($query as $value) {
            $data[] = $value->privileges_id;
        }
        return $data;
    }

    function privileges_get_data() {
        $sql = "select p.*, m.nama as modul from `privileges`p 
            join module m on(p.module_id = m.id)
            order by m.nama, p.form_nama";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function privileges_edit_data($data) {
        $this->db->trans_begin();
        $this->db->where('user_group_id', $data['id_group']);
        $this->db->delete('user_group_privileges');

        if (is_array($data['privileges'])) {
            foreach ($data['privileges'] as $value) {
                $insert = array(
                    'user_group_id' => $data['id_group'],
                    'privileges_id' => $value
                );
                $this->db->insert('user_group_privileges', $insert);
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = FALSE;
        } else {
            $this->db->trans_commit();
            $status = TRUE;
        }
    }
    
    /*AKUN*/
    function data_rekening_load_data($id = null) {
        $q = null;
        if ($id != null) {
            $q = "where id = '$id'";
        }
        $sql = "select *, nama as rekening from rekening $q order by urut asc";
        return $this->db->query($sql);
    }
    
    function data_subrekening_load_data($id = null, $id_rekening = null) {
        $q = null;
        if ($id != null) {
            $q.=" and id = '$id'";
        }
        if ($id_rekening != null) {
            $q.=" and id_rekening = '$id_rekening'";
        }
        $sql = "select * from sub_rekening where id is not NULL $q order by id";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function data_subsubrekening_load_data($id = null, $id_sub_rekening = null) {
        $q = null;
        if ($id != null) {
            $q.=" and ssr.id = '$id'";
        }
        if ($id_sub_rekening != null) {
            $q.=" and ssr.id_sub_rekening = '$id_sub_rekening'";
        }
        $sql = "select ssr.*, r.id as id_rekening, sr.id as id_subrekening, sr.nama as sub_rekening, 
            r.nama as rekening from sub_sub_rekening ssr
            join sub_rekening sr on (ssr.id_sub_rekening = sr.id)
            join rekening r on (r.id = sr.id_rekening)
            where ssr.id is not NULL $q order by ssr.id";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function data_subsubsub_rekening_load_data($id = null, $id_subsub_rekening = null) {
        $q = null;
        if ($id != NULL) {
            $q.=" and sssr.id = '$id'";
        }
        if ($id_subsub_rekening != NULL) {
            $q.=" and ssr.id = '$id_subsub_rekening'";
        }
        $sql = "select r.id as id_rekening, sr.id as id_sub_rekening, ssr.id as id_sub_sub_rekening, sssr.id as id_sub_sub_sub_rekening, sssr.*, sr.nama as sub_rekening, r.nama as rekening from sub_sub_sub_rekening sssr
            join sub_sub_rekening ssr on (sssr.id_sub_sub_rekening = ssr.id)
            join sub_rekening sr on (ssr.id_sub_rekening = sr.id)
            join rekening r on (r.id = sr.id_rekening)
            where sssr.id is not NULL $q order by sssr.id";
        //echo "$sql";
        return $this->db->query($sql);
    }
    
    function data_subsubsubsub_rekening_load_data($id = null, $id_subsubsubsub_rekening = null, $nama = null) {
        $q = null; $s = null; $order = "order by ssssr.id";
        if ($id != NULL) {
            $q.=" and ssssr.id = '$id'";
        }
        if ($id_subsubsubsub_rekening != NULL) {
            $q.=" and ssssr.id_sub_sub_sub_rekening = '$id_subsubsubsub_rekening'";
        }
        if ($nama != NULL) {
            $q.=" and ssssr.nama like ('%$nama%')";
            $s = " r.id,";
            $order = "order by r.id asc";
        }
        $sql = "select ssssr.*, $s r.id as id_rekening, sr.id as id_subrekening, sr.id as id_sub_rekening, ssr.id as id_sub_sub_rekening, 
            sssr.id as id_sub_sub_sub_rekening, ssssr.id as id_sub_sub_sub_sub_rekening, sr.nama as sub_rekening, r.nama as rekening, 
            sssr.nama as sub_sub_sub_rekening, ssssr.nama as sub_sub_sub_sub_rekening from sub_sub_sub_sub_rekening ssssr
            join sub_sub_sub_rekening sssr on (ssssr.id_sub_sub_sub_rekening = sssr.id)
            join sub_sub_rekening ssr on (sssr.id_sub_sub_rekening = ssr.id)
            join sub_rekening sr on (ssr.id_sub_rekening = sr.id)
            join rekening r on (r.id = sr.id_rekening)
            where ssssr.id is not NULL $q $order";
        //echo "$sql";
        return $this->db->query($sql);
    }
    
    function data_subsubsubsubrekening_load_data($q, $id_sub_sub_sub = '') {
        $w = '';
        if ($id_sub_sub_sub != '') {
            $w = " where ssssr.id_sub_sub_sub_rekening = '$id_sub_sub_sub'";
        }
        $sql = "select ssssr.*, r.id as id_rekening, sr.id as id_subrekening, 
            sr.id as id_sub_rekening, ssr.id as id_sub_sub_rekening, ssr.nama as sub_sub_rekening, sssr.id as id_sub_sub_sub_rekening, 
            ssssr.id as id_sub_sub_sub_sub_rekening, sr.nama as sub_rekening, r.nama as rekening, sssr.nama as sub_sub_sub_rekening, 
            ssssr.nama as sub_sub_sub_sub_rekening, 
            CONCAT_WS(' - ',r.nama, sr.nama, ssr.nama, sssr.nama, ssssr.nama) as rekening_concat, 
            CONCAT_WS('.',r.id, sr.id, ssr.id, sssr.id, ssssr.id) as new_code
            from sub_sub_sub_sub_rekening ssssr
            join sub_sub_sub_rekening sssr on (ssssr.id_sub_sub_sub_rekening = sssr.id)
            join sub_sub_rekening ssr on (sssr.id_sub_sub_rekening = ssr.id)
            join sub_rekening sr on (ssr.id_sub_rekening = sr.id)
            join rekening r on (r.id = sr.id_rekening)
            $w where ssssr.id like ('%$q%') or ssssr.nama like ('%$q%')";
        //having rekening_concat like ('%$q%') or new_code like ('%$q%') // jgn di hapus
        return $this->db->query($sql);
    }
    
    function save_sub_sub_rekening() {
        $data_sub_sub = array(
            'id' => post_safe('kode_subsub'),
            'id_sub_rekening' => post_safe('id_subrekening'),
            'nama' => post_safe('sub_sub_rekening')
        );
        $this->db->insert('sub_sub_rekening', $data_sub_sub);
        $result['id'] = $this->db->insert_id();
        $result['status'] = TRUE;
        return $result;
    }
    
    function save_edit_sub_sub_rekening() {
        $data_sub_sub = array(
            'id' => post_safe('kode_subsub'),
            'id_sub_rekening' => post_safe('id_subrekening'),
            'nama' => post_safe('sub_sub_rekening')
        );
        $this->db->where('id', post_safe('id_sub_sub_reks'));
        $this->db->update('sub_sub_rekening', $data_sub_sub);
        $result['id'] = post_safe('id_sub_sub_reks');
        $result['status'] = TRUE;
        return $result;
    }
    
    function rekening_save() {
        $this->db->insert('rekening', array('id' => post_safe('kode_rek'), 'nama' => post_safe('nama_rekening'), 'posisi' => post_safe('posisi'), 'urut' => post_safe('kode_rek')));
        return $this->db->insert_id();
    }
    
    function rekening_update() {
        $data = array(
            'id' => post_safe('kode_rek'),
            'nama' => post_safe('nama_rekening'),
            'posisi' => post_safe('posisi')
        );
        $this->db->where('id', post_safe('kode_rek_id'));
        $this->db->update('rekening', $data);
    }
    
    function subrekening_edit() {
        $data = array(
            'id' => post_safe('kode_sub_rek'),
            'id_rekening' => post_safe('rekening_id'),
            'nama' => post_safe('nama_sub')
        );
        $this->db->where('id', post_safe('kode_sub_rek_id'));
        $this->db->update('sub_rekening', $data);
    }
    
    function subrekening_save() {
        $this->db->insert('sub_rekening', array('id' => post_safe('kode_sub_rek'), 'id_rekening' => post_safe('rekening_id'),'nama' => post_safe('nama_sub')));
        return $this->db->insert_id();
    }
    
    function delete_rekening($id) {
        $this->db->delete('rekening', array('id' => $id));
    }
    
    function delete_subrekening($id) {
        $this->db->delete('sub_rekening', array('id' => $id));
    }
    
    function delete_subsubrekening($id) {
        $this->db->delete('sub_sub_rekening', array('id' => $id));
    }
    
    function referensi_load_data() {
        $sql = "select r.nama as rekening, s.nama as sub_rekening, ss.id, ss.nama as sub_sub_rekening from rekening r 
            join sub_rekening s on (r.id = s.id_rekening)
            join sub_sub_rekening ss on (s.id = ss.id_sub_rekening)";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function sub_sub_rek_save() {
        $cek = $this->db->query("select count(*) as jumlah from sub_sub_rekening where id = '".post_safe('kode_sub_sub_rek')."'")->row();
        $array = array(
            'id' => post_safe('kode_sub_sub_rek'),
            'id_sub_rekening' => post_safe('sub_rekening_id'),
            'nama' => post_safe('nama_sub_sub')
        );
        if ($cek->jumlah == 0) {
            $this->db->insert('sub_sub_rekening', $array);
            $id = post_safe('kode_sub_sub_rek');
            return $this->data_subsubrekening_load_data($id)->row();
        } else {
            $this->db->where('id', post_safe('kode_sub_sub_rek'));
            $this->db->update('sub_sub_rekening', $array);
            return $this->data_subsubrekening_load_data(post_safe('kode_sub_sub_rek'))->row();
        }
    }
    
    function sub_sub_sub_rek_save() {
        if (post_safe('id_sub_sub_sub') == '') {
            $array = array(
                'id' => post_safe('kode'),
                'id_sub_sub_rekening' => post_safe('sub_sub_rek_id'),
                'nama' => post_safe('nama')
            );
            $this->db->insert('sub_sub_sub_rekening', $array);
            $id = $this->db->insert_id();
        } else {
            $array = array(
                'id' => post_safe('kode'),
                'id_sub_sub_rekening' => post_safe('sub_sub_rek_id'),
                'nama' => post_safe('nama')
            );
            $this->db->where('id', post_safe('id_sub_sub_sub'));
            $this->db->update('sub_sub_sub_rekening', $array);
            $id = post_safe('id_sub_sub_sub');
        }
        return $this->data_subsubsub_rekening_load_data($id)->row();
    }
    
    function save_sub_sub_sub_sub_rekening() {
        $array = array(
            'id' => post_safe('kode_subsubsubsub'),
            'id_sub_sub_sub_rekening' => post_safe('sub_sub_sub_rekening'),
            'nama' => post_safe('nama_ssss')
        );
        $this->db->insert('sub_sub_sub_sub_rekening',$array);
        return $this->data_subsubsubsub_rekening_load_data(post_safe('kode_subsubsubsub'))->row();
    }
    
    function save_edit_sub_sub_sub_sub_rekening() {
        $array = array(
            'id' => post_safe('kode_subsubsubsub'),
            'id_sub_sub_sub_rekening' => post_safe('sub_sub_sub_rekening'),
            'nama' => post_safe('nama_ssss')
        );
        $this->db->where('id', post_safe('kode_subsubsubsub'));
        $this->db->update('sub_sub_sub_sub_rekening',$array);
        return $this->data_subsubsubsub_rekening_load_data(post_safe('kode_subsubsubsub'))->row();
    }
    
    function get_sub_sub_sub_sub_rekening($q) {
        $sql = "select r.id as id_rekening, sr.id as id_sub_rekening, ssr.id as id_sub_sub_rekening, sssr.id as id_sub_sub_sub_rekening, ssssr.id as id_sub_sub_sub_sub_rekening,
            concat(ssssr.nama,' ',sssr.nama,' ',ssr.nama,' ',sr.nama,' ',r.nama) as nama from sub_sub_sub_sub_rekening ssssr
            join sub_sub_sub_rekening sssr on (ssssr.id_sub_sub_sub_rekening = sssr.id)
            join sub_sub_rekening ssr on (sssr.id_sub_sub_rekening = ssr.id)
            join sub_rekening sr on (ssr.id_sub_rekening = sr.id)
            join rekening r on (r.id = sr.id_rekening)
            where ssssr.nama like ('%$q%') order by locate('$q',ssssr.nama)";
        return $this->db->query($sql);
    }
    
    function total_jurnal_by_sub_sub($id_sub_sub, $status = NULL) {
        $q = " where date(j.waktu) between '".date("Y-m-d")."' and '".date("Y-m-d")."'";
        if (isset($_GET['awal'])) {
            if ($status !== NULL) {
                $q =" where date(j.waktu) = '".(date("Y")-1)."'";
            } else {
                $q =" where date(j.waktu) between '".  date2mysql(get_safe('awal'))."' and  '".  date2mysql(get_safe('akhir'))."'";
            }
        }
        $sql = "select sum(j.debet) as total_debet, sum(j.kredit) as total_kredit, sum(j.debet-j.kredit) as sub_total, 
            r.nama as rekening, s.nama as sub_rekening, j.* from jurnal j
            join sub_sub_sub_sub_rekening ssssr on (j.id_sub_sub_sub_sub_rekening = ssssr.id)
            join sub_sub_sub_rekening sssr on (ssssr.id_sub_sub_sub_rekening = sssr.id)
            join sub_sub_rekening ss on (sssr.id_sub_sub_rekening = ss.id) 
            join sub_rekening s on (ss.id_sub_rekening = s.id)
            join rekening r on (s.id_rekening = r.id) $q and ss.id = '$id_sub_sub'";
        //echo $sql."<br/>";
        return $this->db->query($sql);
    }
    
    function save_ubah_password() {
        $passlama = md5($_POST['passlama']);
        $passsess = $this->session->userdata('pass');
        $passbaru = md5($_POST['passbaru']);
        if ($passsess !== $passlama) {
            $result['status'] = FALSE;
        } else {
            $data = array(
                'password' => $passbaru
            );
            $this->db->where('id', $this->session->userdata('id_user'));
            $this->db->update('users', $data);
            $result['status'] = TRUE;
        }
        return $result;
    }
}
?>
