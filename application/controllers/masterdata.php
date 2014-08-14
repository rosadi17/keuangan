<?php

class Masterdata extends CI_Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    function unit() {
        $data['title'] = 'Unit Satuan Kerja';
        $this->load->view('masterdata/unit', $data);
    }
    
    function manage_unit($action, $page = null) {
        $limit = 10;
        switch ($action) {
            case 'list':
                $search['key'] = $_GET['search'];
                $search['id']  = $_GET['id'];
                $data = $this->get_list_data_unit($limit, $page, $search);
                $this->load->view('masterdata/unit-table', $data);
                break;
            case 'save': 
                $data = $this->m_masterdata->save_unit();
                die(json_encode($data));
                break;
            case 'delete': 
                $this->m_masterdata->delete_unit($_GET['id']);
                break;
            
        }
    }
    
    function get_list_data_unit($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_masterdata->get_data_unit($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        return $data;
    }
    
    function kegiatan() {
        $data['title'] = 'Kegiatan';
        $this->load->view('masterdata/kegiatan', $data);
    }
    
    function get_list_data_kegiatan($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_masterdata->get_data_kegiatan($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        return $data;
    }
    
    function kegiatan_preview() {
        $satker = get_safe('id');
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $s = NULL;
        if ($satker !== '') {
            $s = $satker;
        }
        $data['sk'] = $s;
        $data['proja'] = $this->m_masterdata->get_nama_proja($s)->row();
        $data['program'] = $this->m_masterdata->load_all_data_program($s)->result();
        $this->load->view('masterdata/kegiatan-preview', $data);
    }
    
    /*PROGRAM*/
    function program() {
        $data['title'] = 'program';
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $this->load->view('masterdata/program', $data);
    }
    
    function manage_program($action, $page = null) {
        $limit = 15;
        switch ($action) {
            case 'list':
                $search['key'] = $_GET['search'];
                $search['id']  = $_GET['id'];
                $data = $this->get_list_data_program($limit, $page, $search);
                $this->load->view('masterdata/program-table', $data);
                break;
            case 'save': 
                $data = $this->m_masterdata->save_program();
                die(json_encode($data));
                break;
            case 'delete': 
                $this->m_masterdata->delete_program($_GET['id']);
                break;
            
        }
    }
    
    function get_list_data_program($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_masterdata->get_data_program($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        return $data;
    }
    
    /*KEGIATAN*/
    function keg_program() {
        $data['title'] = 'keg_program';
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $this->load->view('masterdata/keg-program', $data);
    }
    
    function manage_keg_program($action, $page = null) {
        $limit = 15;
        switch ($action) {
            case 'list':
                $search['key'] = $_GET['search'];
                $search['id']  = $_GET['id'];
                $data = $this->get_list_data_keg_program($limit, $page, $search);
                $this->load->view('masterdata/keg-program-table', $data);
                break;
            case 'save': 
                $data = $this->m_masterdata->save_keg_program();
                die(json_encode($data));
                break;
            case 'delete': 
                $this->m_masterdata->delete_keg_program($_GET['id']);
                break;
            
        }
    }
    
    function get_list_data_keg_program($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_masterdata->get_data_keg_program($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        return $data;
    }
    
    /*SUB KEGIATAN*/
    function sub_kegiatan() {
        $data['title'] = 'sub_kegiatan';
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $this->load->view('masterdata/sub-kegiatan', $data);
    }
    
    function manage_sub_kegiatan($action, $page = null) {
        $limit = 15;
        switch ($action) {
            case 'list':
                $search['key'] = $_GET['search'];
                $search['id']  = $_GET['id'];
                $data = $this->get_list_data_sub_kegiatan($limit, $page, $search);
                $this->load->view('masterdata/sub-kegiatan-table', $data);
                break;
            case 'save': 
                $data = $this->m_masterdata->save_sub_kegiatan();
                die(json_encode($data));
                break;
            case 'delete': 
                $this->m_masterdata->delete_sub_kegiatan($_GET['id']);
                break;
            
        }
    }
    
    function get_list_data_sub_kegiatan($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_masterdata->get_data_sub_kegiatan($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        return $data;
    }
    
    /*URAIAN*/
    function uraian() {
        $data['title'] = 'uraian';
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $this->load->view('masterdata/uraian', $data);
    }
    
    function manage_uraian($action, $page = null) {
        $limit = 15;
        switch ($action) {
            case 'list':
                $search['key'] = $_GET['search'];
                $search['id']  = $_GET['id'];
                $data = $this->get_list_data_uraian($limit, $page, $search);
                $this->load->view('masterdata/uraian-table', $data);
                break;
            case 'save': 
                $data = $this->m_masterdata->save_uraian();
                die(json_encode($data));
                break;
            case 'delete': 
                $this->m_masterdata->delete_uraian($_GET['id']);
                break;
            
        }
    }
    
    function get_list_data_uraian($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_masterdata->get_data_uraian($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        return $data;
    }
    
    /*SUB URAIAN*/
    function sub_uraian() {
        $data['title'] = 'uraian';
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $this->load->view('masterdata/sub-uraian', $data);
    }
    
    function manage_sub_uraian($action, $page = null) {
        $limit = 15;
        switch ($action) {
            case 'list':
                $search['key'] = $_GET['search'];
                $search['id']  = $_GET['id'];
                $data = $this->get_list_data_sub_uraian($limit, $page, $search);
                $this->load->view('masterdata/sub-uraian-table', $data);
                break;
            case 'save': 
                $data = $this->m_masterdata->save_sub_uraian();
                die(json_encode($data));
                break;
            case 'delete': 
                $this->m_masterdata->delete_sub_uraian($_GET['id']);
                break;
            
        }
    }
    
    function get_list_data_sub_uraian($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_masterdata->get_data_sub_uraian($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        return $data;
    }
    
    /*SUB SUB URAIAN*/
    function sub_sub_uraian() {
        $data['title'] = 'uraian';
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $this->load->view('masterdata/sub-sub-uraian', $data);
    }
    
    function manage_sub_sub_uraian($action, $page = null) {
        $limit = 15;
        switch ($action) {
            case 'list':
                $search['key'] = $_GET['search'];
                $search['id']  = $_GET['id'];
                $data = $this->get_list_data_sub_sub_uraian($limit, $page, $search);
                $this->load->view('masterdata/sub-sub-uraian-table', $data);
                break;
            case 'save': 
                $data = $this->m_masterdata->save_sub_sub_uraian();
                die(json_encode($data));
                break;
            case 'delete': 
                $this->m_masterdata->delete_sub_sub_uraian($_GET['id']);
                break;
            
        }
    }
    
    function get_list_data_sub_sub_uraian($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_masterdata->get_data_sub_sub_uraian($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        return $data;
    }
    
    /* USER ACCOUNT */
    function account(){
        $data['title'] = 'Account';
        $this->load->view('masterdata/group', $data);
    }

    function user_group(){
        $this->load->view('masterdata/user_group');
    }

    function user_account() {
        $data['user_group'] = $this->m_masterdata->get_user_group();
        $this->load->view('masterdata/account', $data);
    }

    function get_group_list($limit, $page, $search){
        if ($page == 'undefined') {
            $page = 1;
        }
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $query = $this->m_masterdata->group_get_data($limit, $start, $search);
        $data['jumlah'] = $query['jumlah'];
        $data['user'] = $query['data'];
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, '');
        return $data;
    }

    function manage_group($mode,$page=null){
        $limit = 15;
        $add = array(
            'id' => post_safe('id'),
            'nama' => post_safe('nama')
        );
        $search = array();
        switch ($mode) {
            case 'list':
                $data = $this->get_group_list($limit, $page, $search);
                $this->load->view('masterdata/list_group', $data);
                break;
            case 'post':
                $search['id'] = $this->m_masterdata->group_update_data($add);
                $data = $this->get_group_list($limit, $page, $search);
                $this->load->view('masterdata/list_group', $data);
                break;

             case 'edit':
                $data['title'] = "User Group Privileges";
                $data['id'] = get_safe('id');
                $data['nama'] = get_safe('nama');
                $this->load->view('masterdata/privilege', $data);
                break;

            case 'delete':
                $id = get_safe('id');
                $this->m_masterdata->group_delete_data($id);
                $data = $this->get_group_list($limit, $page, null);
                if ($data['user'] == null) {
                    $data = $this->get_group_list($limit, 1, null);
                }
                $this->load->view('masterdata/list_group', $data);
                break;

            default:
                
                break;
        }
    }

    function get_user_list($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $query = $this->m_masterdata->user_get_data($limit, $start, $search);
        $data['jumlah'] = $query['jumlah'];
        $data['user'] = $query['data'];
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 2, '');
        return $data;
    }

    function manage_user($mode, $page = null) {
        $limit = 15;
        $add = array(
            'id' => post_safe('id_penduduk'),
            'username' => post_safe('username'),
            'nama' => post_safe('nama'),
            'id_user_group' => post_safe('group'),
        );
        switch ($mode) {
            case 'list':
                $search['id'] = post_safe('id_penduduk');
                $search['nama'] = post_safe('nama');
                $search['username'] = post_safe('username');
                $data = $this->get_user_list($limit, $page, $search);
                $this->load->view('masterdata/list_account', $data);
                break;
            case 'save':
                $add['password'] = md5('1234');
                $data = $this->m_masterdata->user_add_data($add);
                die(json_encode($data));
                break;
            case 'delete':
                $id = get_safe('id');
                $this->m_masterdata->user_delete_data($id);
                break;
            case 'reset_password':
                $id = get_safe('id');
                $this->m_masterdata->reset_password($id);
                break;
            case 'cek':
                $kab = array(
                    'nama' => get_safe('user'),
                    'kecamatan_id' => get_safe('kecid'),
                    'kode' => get_safe('kode')
                );
                $cek = $this->m_masterdata->user_cek_data($kab);
                die(json_encode(array('status' => $cek)));

                break;
            case 'search': 
                $search['id'] = post_safe('id_penduduk');
                $search['nama'] = post_safe('nama');
                $search['username'] = post_safe('username');
                $data = $this->get_user_list($limit, 1, $search);
                $this->load->view('masterdata/list_account', $data);
                break;
            default:
                break;
        }
    }

    function get_unit() {
        $q = get_safe('q');
        $data = $this->m_masterdata->get_unit($q)->result();
        die(json_encode($data));
    }

    function get_group_privileges($id) {
        $data['user_priv'] = $this->m_masterdata->group_privileges_data($id);
        $data['privilege'] = $this->m_masterdata->privileges_get_data();
        return $data;
    }

    function manage_privileges($mode) {

        switch ($mode) {
            case 'list':
                $id = get_safe('id');
                $data = $this->get_group_privileges($id);
                $this->load->view('masterdata/list_privileges', $data);

                break;

            case 'add':
                $add = array(
                    'privileges' => post_safe('data'),
                    'id_group' => post_safe('id_group')
                );
                $this->m_masterdata->privileges_edit_data($add);
                $data = $this->get_group_privileges(post_safe('id_group'));
                $this->load->view('masterdata/list_privileges', $data);

                break;

            default:
                break;
        }
    }
    
    
    /*AKUN PERKIRAAN*/
    function kode_akun() {
        $data['title'] = 'Daftar Account / Perkiraan';
        $this->load->view('masterdata/kode-akun',$data);
    }
    
    function manage_rekening($act, $page = null) {
        
        $data['list_rekening'] = $this->m_masterdata->data_rekening_load_data()->result();
        $data['srekening'] = $this->m_masterdata->data_subrekening_load_data()->result();
        $data['ssrekening'] = $this->m_masterdata->data_subsubrekening_load_data()->result();
        switch ($act) {
            case 'list':
                $data['list_data'] = $this->m_masterdata->data_rekening_load_data()->result();
                $data['list_rekening'] = $this->m_masterdata->data_rekening_load_data()->result();
                $this->load->view('masterdata/kode-akun-list',$data);
            break;
            case 'add':
                $data['id'] = $this->m_masterdata->rekening_save();
                $data['id_sub'] = post_safe('sub_rekening');
                $data['nama_sub_sub'] = post_safe('sub_sub_rekening');
                $data['jenis'] = post_safe('jenis');
                $data['list_data'] = $this->m_masterdata->data_rekening_load_data($data['id'])->result();
                $data['list_rekening'] = $this->m_masterdata->data_rekening_load_data()->result();
                $this->load->view('masterdata/kode-akun-list',$data);
            break;
            case 'edit_rek':
                $this->m_masterdata->rekening_update();
                $data['id_sub'] = post_safe('sub_rekening');
                $data['nama_sub_sub'] = post_safe('sub_sub_rekening');
                $data['jenis'] = post_safe('jenis');
                $data['list_data'] = $this->m_masterdata->data_rekening_load_data(post_safe('kode_rek'))->result();
                $data['list_rekening'] = $this->m_masterdata->data_rekening_load_data()->result();
                $this->load->view('masterdata/kode-akun-list',$data);
            break;
            case 'add_sub':
                $data['id_sub'] = $this->m_masterdata->subrekening_save();
                $data['list_data'] = $this->m_masterdata->data_rekening_load_data(post_safe('rekening_id'))->result();
                $data['srekening'] = $this->m_masterdata->data_subrekening_load_data()->result();
                $data['list_rekening'] = $this->m_masterdata->data_rekening_load_data()->result();
                $this->load->view('masterdata/kode-akun-list',$data);
            break;
            case 'edit_sub':
                $data['id_sub'] = $this->m_masterdata->subrekening_edit();
                $data['list_rekening'] = $this->m_masterdata->data_rekening_load_data()->result();
                $data['list_data'] = $this->m_masterdata->data_rekening_load_data(post_safe('rekening_id'))->result();
                $this->load->view('masterdata/kode-akun-list',$data);
            break;
            case 'search':
                $id_rek = post_safe('nama_ssss');
                $data['id_sub'] = post_safe('sub_rekening');
                $data['nama_sub_sub'] = post_safe('sub_sub_rekening');
                $data['jenis'] = post_safe('jenis');
                $data['list_data'] = $this->m_masterdata->data_subsubsubsub_rekening_load_data(null, null, $id_rek)->result();
                $this->load->view('masterdata/kode-akun-list',$data);
            break;
            case 'add_sub_sub_rek':
                $rows = $this->m_masterdata->sub_sub_rek_save();
                $data['id_sub'] = $rows->id_subrekening;
                $data['id_sub_sub'] = $rows->id;
                $data['id_sub_sub_sub'] = '';
                $data['id_sub_sub_sub_sub'] = '';
                $data['srekening'] = $this->m_masterdata->data_subrekening_load_data()->result();
                $data['ssrekening'] = $this->m_masterdata->data_subsubrekening_load_data()->result();
                $data['list_data'] = $this->m_masterdata->data_rekening_load_data($rows->id_rekening)->result();
                $this->load->view('masterdata/kode-akun-list',$data);
            break;
            case 'edit_sub_sub_rek':
                $rows = $this->m_masterdata->sub_sub_rek_save();
                $data['id_sub'] = $rows->id_subrekening;
                $data['id_sub_sub'] = $rows->id;
                $data['id_sub_sub_sub'] = '';
                $data['id_sub_sub_sub_sub'] = '';
                $data['ssrekening'] = $this->m_masterdata->data_subsubrekening_load_data()->result();
                $data['list_data'] = $this->m_masterdata->data_rekening_load_data($rows->id_rekening)->result();
                $this->load->view('masterdata/kode-akun-list',$data);
            break;
            case 'add_sub_sub_sub_rek':
                $rows = $this->m_masterdata->sub_sub_sub_rek_save();
                $data['id_sub'] = $rows->id_sub_rekening;
                $data['id_sub_sub'] = $rows->id_sub_sub_rekening;
                $data['id_sub_sub_sub'] = $rows->id;
                $data['id_sub_sub_sub_sub'] = '';
                $data['list_data'] = $this->m_masterdata->data_rekening_load_data($rows->id_rekening)->result();
                $this->load->view('masterdata/kode-akun-list',$data);
            break;
        }
    }
    
    function save_sub_sub_sub_sub_rekening() {
        $data['list_rekening'] = $this->m_masterdata->data_rekening_load_data()->result();
        $data['srekening'] = $this->m_masterdata->data_subrekening_load_data()->result();
        $data['ssrekening'] = $this->m_masterdata->data_subsubrekening_load_data()->result();
        $rows = $this->m_masterdata->save_sub_sub_sub_sub_rekening();
        $data['id_sub'] = $rows->id_sub_rekening;
        $data['id_sub_sub'] = $rows->id_sub_sub_rekening;
        $data['id_sub_sub_sub'] = $rows->id_sub_sub_sub_rekening;
        $data['id_sub_sub_sub_sub'] = $rows->id_sub_sub_sub_sub_rekening;
        $data['list_data'] = $this->m_masterdata->data_rekening_load_data($rows->id_rekening)->result();
        //die(json_encode($data));
        $this->load->view('masterdata/kode-akun-list',$data);
    }
    
    function save_edit_sub_sub_sub_sub_rekening() {
        $data['list_rekening'] = $this->m_masterdata->data_rekening_load_data()->result();
        $data['srekening'] = $this->m_masterdata->data_subrekening_load_data()->result();
        $data['ssrekening'] = $this->m_masterdata->data_subsubrekening_load_data()->result();
        $rows = $this->m_masterdata->save_edit_sub_sub_sub_sub_rekening();
        $data['id_sub'] = $rows->id_sub_rekening;
        $data['id_sub_sub'] = $rows->id_sub_sub_rekening;
        $data['id_sub_sub_sub'] = $rows->id_sub_sub_sub_rekening;
        $data['id_sub_sub_sub_sub'] = $rows->id_sub_sub_sub_sub_rekening;
        $data['list_data'] = $this->m_masterdata->data_rekening_load_data($rows->id_rekening)->result();
        $this->load->view('masterdata/kode-akun-list',$data);
    }
    
    function save_edit_sub_sub_sub_rek() {
        $data['list_rekening'] = $this->m_masterdata->data_rekening_load_data()->result();
        $data['srekening'] = $this->m_masterdata->data_subrekening_load_data()->result();
        $data['ssrekening'] = $this->m_masterdata->data_subsubrekening_load_data()->result();
        $rows = $this->m_masterdata->sub_sub_sub_rek_save();
        $data['id_sub'] = $rows->id_sub_rekening;
        $data['id_sub_sub'] = $rows->id_sub_sub_rekening;
        $data['id_sub_sub_sub'] = $rows->id;
        $data['id_sub_sub_sub_sub'] = '';
        $data['list_data'] = $this->m_masterdata->data_rekening_load_data($rows->id_rekening)->result();
        $this->load->view('masterdata/kode-akun-list',$data);
    }
    
    function cetak_proja($id_satker) {
        $data['proja'] = $this->m_masterdata->get_nama_proja($id_satker)->row();
        $data['program'] = $this->m_masterdata->load_all_data_program($id_satker)->result();
        $this->load->view('masterdata/cetak-proja', $data);
    }
}