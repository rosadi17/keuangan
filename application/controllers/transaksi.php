<?php

class Transaksi extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $id_user = $this->session->userdata('id_user');
        if (empty($id_user)) {
            die(json_encode(array('error' => 'Anda belum login')));
        }
    }
    
    function renbut() {
        $data['title'] = 'Rencana Kebutuhan';
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $data['bulan'] = array(
            array('','Semua'),
            array('01','Januari'),
            array('02','Februari'),
            array('03','Maret'),
            array('04', 'April'),
            array('05', 'Mei'),
            array('06', 'Juni'),
            array('07', 'Juli'),
            array('08', 'Agustus'),
            array('09', 'September'),
            array('10', 'Oktober'),
            array('11', 'November'),
            array('12', 'Desember')
        );
        $this->load->view('transaksi/renbut', $data);
    }
    
    function manage_renbut($action, $page = null) {
        $limit = 10;
        switch ($action) {
            case 'list':
                $search = array(
                    'satker' =>  get_safe('id_satker'),
                    'awal' => date2mysql(get_safe('awal')),
                    'akhir' => date2mysql(get_safe('akhir')),
                    'awal_keg' => date2mysql(get_safe('awal_keg')),
                    'akhir_keg' => date2mysql(get_safe('akhir_keg')),
                    'kegiatan' => get_safe('kegiatan'),
                    'jenis' => get_safe('jenis_renbut')
                );
                $data = $this->get_list_data_renbut($limit, $page, $search);
                $this->load->view('transaksi/renbut-table', $data);
                break;
            case 'save': 
                $data = $this->m_transaksi->save_renbut();
                die(json_encode($data));
                break;
            case 'print':
                $id = $_GET['id'];
                $data['data'] = $this->m_transaksi->get_data_renbut_detail($id)->row();
                $this->load->view('transaksi/print-renbut', $data);
                break;
            case 'delete': 
                $this->m_transaksi->delete_renbut($_GET['id']);
                break;
            
        }
    }
    
    function get_list_data_renbut($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_transaksi->get_data_renbut($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        $data['infopage'] = page_summary($data['jumlah'], $page, $limit);
        return $data;
    }
    
    /**DROPPING*/
    function dropping() {
        $data['title'] = 'Dropping';
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $data['bulan'] = array(
            array('','Semua'),
            array('01','Januari'),
            array('02','Februari'),
            array('03','Maret'),
            array('04', 'April'),
            array('05', 'Mei'),
            array('06', 'Juni'),
            array('07', 'Juli'),
            array('08', 'Agustus'),
            array('09', 'September'),
            array('10', 'Oktober'),
            array('11', 'November'),
            array('12', 'Desember')
        );
        $this->load->view('transaksi/dropping', $data);
    }
    
    function manage_dropping($action, $page = null) {
        $limit = 10;
        switch ($action) {
            case 'list':
                //$search['key'] = $_GET['search'];
                $search['id']  = get_safe('id');
                $search['awal'] = date2mysql(get_safe('awal'));
                $search['akhir']= date2mysql(get_safe('akhir'));
                $search['satker']= get_safe('id_satker');
                $search['proja'] = get_safe('id_uraian');
                $search['pjawab']= get_safe('png_jawab');
                $data = $this->get_list_data_dropping($limit, $page, $search);
                $this->load->view('transaksi/dropping-table', $data);
                break;
            case 'save': 
                $data = $this->m_transaksi->save_dropping();
                die(json_encode($data));
                break;
            case 'approve': 
                $param['id']     = post_safe('id');
                $param['status'] = post_safe('status');
                $param['jumlah'] = post_safe('jumlah');
                $data = $this->m_transaksi->approve_dropping($param);
                die(json_encode($data));
                break;
            case 'export_excel':
                $search['id']  = get_safe('id');
                $search['awal'] = date2mysql(get_safe('awal'));
                $search['akhir']= date2mysql(get_safe('akhir'));
                $search['satker']= get_safe('id_satker');
                $search['proja'] = get_safe('id_uraian');
                $search['pjawab']= get_safe('png_jawab');
                $query = $this->m_transaksi->get_data_dropping(NULL, NULL, $search);
                $data['list_data'] = $query['data'];
                $data['awal'] = date2mysql(get_safe('awal'));
                $data['akhir']= date2mysql(get_safe('akhir'));
                $this->load->view('transaksi/excel-dropping', $data);
                break;
            case 'delete': 
                $this->m_transaksi->delete_dropping(get_safe('id'));
                break;
            
        }
    }
    
    function get_list_data_dropping($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_transaksi->get_data_dropping($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        $data['infopage'] = page_summary($data['jumlah'], $page, $limit);
        return $data;
    }
    
    /*PENCAIRAN*/
    function pencairan() {
        $data['title'] = 'Pencairan';
        $this->load->view('transaksi/pencairan', $data);
    }
    
    function pencairan_renbut() {
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $data['bulan'] = array(
            array('01','Januari'),
            array('02','Februari'),
            array('03','Maret'),
            array('04', 'April'),
            array('05', 'Mei'),
            array('06', 'Juni'),
            array('07', 'Juli'),
            array('08', 'Agustus'),
            array('09', 'September'),
            array('10', 'Oktober'),
            array('11', 'November'),
            array('12', 'Desember')
        );
        $this->load->view('transaksi/pencairan-renbut', $data);
    }
    
    function get_list_data_pencairan_renbut($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_transaksi->get_data_pencairan_renbut($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        return $data;
    }
    
    function manage_pencairan_renbut($action, $page = null) {
        $limit = 10;
        switch ($action) {
            case 'list':
                $search['key'] = $_GET['search'];
                $search['id']  = $_GET['id'];
                $search['bulan'] = $_GET['bulan'];
                $search['satker']= $_GET['id_satker'];
                $search['proja'] = $_GET['proja'];
                $search['pjawab']= $_GET['pjawab'];
                $data = $this->get_list_data_pencairan_renbut($limit, $page, $search);
                $this->load->view('transaksi/pencairan-renbut-table', $data);
            break;
            case 'save':
                $data = $this->m_transaksi->save_pencairan();
                die(json_encode($data));
            break;
            case 'print_bukti_kas':
                $id = $_GET['id'];
                $data['list_data'] = $this->m_transaksi->get_data_renbut_detail($id)->result();
                $this->load->view('transaksi/print-bukti-kas', $data);
            break;
        }
    }
    
    /*CASHBON*/
    function cashbon() {
        $data['title'] = 'Rencana Kebutuhan';
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $data['bulan'] = array(
            array('01','Januari'),
            array('02','Februari'),
            array('03','Maret'),
            array('04', 'April'),
            array('05', 'Mei'),
            array('06', 'Juni'),
            array('07', 'Juli'),
            array('08', 'Agustus'),
            array('09', 'September'),
            array('10', 'Oktober'),
            array('11', 'November'),
            array('12', 'Desember')
        );
        $this->load->view('transaksi/cashbon', $data);
    }
    
    function manage_cashbon($action, $page = null) {
        $limit = 10;
        switch ($action) {
            case 'list':
                $search['key'] = $_GET['search'];
                $search['id']  = $_GET['id'];
                $search['bulan'] = $_GET['bulan'];
                $search['satker']= $_GET['id_satker'];
                $data = $this->get_list_data_cashbon($limit, $page, $search);
                $this->load->view('transaksi/cashbon-table', $data);
                break;
            case 'save': 
                $data = $this->m_transaksi->save_cashbon();
                die(json_encode($data));
                break;
            case 'print':
                $id = $_GET['id'];
                $data['data'] = $this->m_transaksi->get_data_cashbon_detail($id)->row();
                $this->load->view('transaksi/print-cashbon', $data);
                break;
            case 'delete': 
                $this->m_transaksi->delete_cashbon($_GET['id']);
                break;
            
        }
    }
    
    function get_list_data_cashbon($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_transaksi->get_data_cashbon($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        return $data;
    }
    
    /*PAGU ANGGARAN*/
    function pagu() {
        $data['title'] = 'Entri Data Pagu Kegiatan';
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $this->load->view('transaksi/pagu', $data);
    }
    
    function manage_pagu($action, $page = null) {
        $limit = 10;
        switch ($action) {
            case 'list':
                $search['key'] = $_GET['search'];
                $search['id']  = $_GET['id'];
                $data = $this->get_list_data_pagu($limit, $page, $search);
                $this->load->view('transaksi/pagu-table', $data);
                break;
            case 'save': 
                $data = $this->m_transaksi->save_pagu();
                die(json_encode($data));
                break;
            case 'delete': 
                $this->m_transaksi->delete_pagu($_GET['id']);
                break;
            
        }
    }
    
    function get_list_data_pagu($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_transaksi->get_data_pagu($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, $search['key']);
        return $data;
    }
    
    /*PEMASUKAN*/
    function pemasukan() {
        $data['title'] = 'Pemasukkan (BKM)';
        $this->load->view('transaksi/pemasukkan', $data);
    }
    
    function manage_pemasukkan($action, $page = null) {
        $limit = 10;
        switch ($action) {
            case 'list':
                $search['key'] = $_GET['search'];
                $search['id']  = $_GET['id'];
                $search['bulan'] = $_GET['bulan'];
                $search['satker']= $_GET['id_satker'];
                $data = $this->get_list_data_pemasukkan($limit, $page, $search);
                $this->load->view('transaksi/pemasukkan-table', $data);
                break;
            case 'save': 
                $data = $this->m_transaksi->save_pemasukkan();
                die(json_encode($data));
                break;
            case 'print_bukti_kas':
                $id = $_GET['id'];
                $data['list_data'] = $this->m_transaksi->get_data_pemasukkan_detail($id)->result();
                $this->load->view('transaksi/print-bkm', $data);
                break;
            case 'delete': 
                $this->m_transaksi->delete_pemasukkan($_GET['id']);
                break;
            
        }
    }
    
    function get_list_data_pemasukkan($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_transaksi->get_data_pemasukkan($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, $search);
        return $data;
    }
    
    function kasir() {
        $data['title'] = 'Form Kasir (Penerimaan / Pengeluaran / Mutasi)';
        $this->load->view('transaksi/kasir', $data);
    }
    
    function kasir_save() {
        $data = $this->m_transaksi->kasir_save();
        die(json_encode($data));
    }
    
    function kode_perkiraan() {
        $data['title'] = 'Form Lawan Perkiraan';
        $this->load->view('transaksi/kode-perkiraan', $data);
    }
    
    function print_bukti_kas() {
        $id = $_GET['id'];
        $data['list_data'] = $this->m_transaksi->print_bukti_kas($id)->result();
        $this->load->view('transaksi/print-bukti-kas', $data);
    }
    
    function jurnal_save() {
        $data = $this->m_transaksi->jurnal_save();
        die(json_encode($data));
    }
    
    function jurnal() {
        $data['title'] = 'Jurnal Transaksi';
        $this->load->view('transaksi/jurnal', $data);
    }
    
    function manage_jurnal($action, $page = null) {
        switch ($action) {
            case 'list':
                $limit = 15;
                $search = array(
                    'awal' => date2mysql(get_safe('awal')),
                    'akhir' => date2mysql(get_safe('akhir')),
                    'norekening' => get_safe('kode_rekening'),
                    'nobukti' => get_safe('nobukti')
                );
                $data = $this->get_list_data_jurnal($limit, $page, $search);
                $this->load->view('transaksi/jurnal-table', $data);
                break;
            case 'verifikasi':
                $limit = 15;
                $search = array(
                    'awal' => date2mysql(get_safe('awal')),
                    'akhir' => date2mysql(get_safe('akhir')),
                    'jenis' => get_safe('jenis'),
                    'kodema' => '',
                    'kegiatan' => get_safe('kegiatan'),
                    'png_jwb' => get_safe('png_jwb'),
                    'nomorbukti' => get_safe('nomorbukti')
                );
                $data = $this->get_list_data_kasir($limit, $page, $search);
                $this->load->view('transaksi/jurnal-verifikasi-table', $data);
                break;
            case 'save_verifikasi':
                $param = array(
                    'id' => get_safe('id')
                );
                
                $data = $this->m_transaksi->save_verifikasi($param);
                die(json_encode($data));
                break;
            case 'save': 
                $data = $this->m_transaksi->save_jurnal();
                die(json_encode($data));
                break;
            case 'delete': 
                $this->m_transaksi->delete_jurnal(get_safe('id'));
                break;
            
        }
    }
    
    function save_jurnal_transaksi() {
        $data = $this->m_transaksi->save_jurnal_transaksi();
        die(json_encode($data));
    }
    
    function get_list_data_jurnal($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_transaksi->get_data_jurnal($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 2, NULL);
        return $data;
    }
    
    function manage_kasir($action, $page = null) {
        $limit = 10;
        switch ($action) {
            case 'list':
                $search = array(
                    'awal' => date2mysql(get_safe('awal')),
                    'akhir' => date2mysql(get_safe('akhir')),
                    'jenis' => get_safe('jenis'),
                    'kodema' => get_safe('id_kode'),
                    'kegiatan' => get_safe('kegiatan'),
                    'png_jwb' => get_safe('png_jwb'),
                    'nomorbukti' => get_safe('nomorbukti')
                );
                $data = $this->get_list_data_kasir($limit, $page, $search);
                $this->load->view('transaksi/kasir-table', $data);
                break;
            case 'delete':
                $id    = get_safe('id');
                $this->db->delete('kasir', array('id' => $id));
                break;
            case 'export_excel':
                $search = array(
                    'awal' => date2mysql(get_safe('awal')),
                    'akhir' => date2mysql(get_safe('akhir')),
                    'jenis' => get_safe('jenis'),
                    'kodema' => get_safe('id_kode'),
                    'kegiatan' => get_safe('kegiatan'),
                    'png_jwb' => get_safe('png_jwb'),
                    'nomorbukti' => get_safe('nomorbukti')
                );
                $query = $this->m_transaksi->get_data_kasir(NULL, NULL, $search);
                $data['list_data'] = $query['data'];
                $this->load->view('transaksi/excel-kasir', $data);
                break;
        }
    }
    
    function get_list_data_kasir($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_transaksi->get_data_kasir($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1,'');
        $data['infopage'] = page_summary($data['jumlah'], $page, $limit);
        return $data;
    }
    
    /*PERWABKU*/
    function perwabku() {
        $data['title'] = 'Perwabku';
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $data['bulan'] = array(
            array('','Semua'),
            array('01','Januari'),
            array('02','Februari'),
            array('03','Maret'),
            array('04', 'April'),
            array('05', 'Mei'),
            array('06', 'Juni'),
            array('07', 'Juli'),
            array('08', 'Agustus'),
            array('09', 'September'),
            array('10', 'Oktober'),
            array('11', 'November'),
            array('12', 'Desember')
        );
        $this->load->view('transaksi/perwabku', $data);
    }
    
    function manage_perwabku($action, $page = null) {
        $limit = 10;
        
        switch ($action) {
            case 'list':
                $search['id'] = get_safe('id');
                $search['awal'] = date2mysql(get_safe('awal'));
                $search['akhir']= date2mysql(get_safe('akhir'));
                $search['nomorpwk'] = get_safe('nomorpwk');
                $search['nomorbkk']= get_safe('nomorbkk');
                $data = $this->get_list_data_perwabku($limit, $page, $search);
                $this->load->view('transaksi/perwabku-table', $data);
                break;
            case 'save': 
                $data = $this->m_transaksi->save_perwabku();
                die(json_encode($data));
                break;
            case 'print': 
                $search['id'] = get_safe('id');
                $search['awal'] = date2mysql(get_safe('awal'));
                $search['akhir']= date2mysql(get_safe('akhir'));
                $search['nomorpwk'] = get_safe('nomorpwk');
                $search['nomorbkk']= get_safe('nomorbkk');
                $query = $this->m_transaksi->get_data_perwabku(NULL, NULL, $search);
                $data['list_data'] = $query['data'];
                $this->load->view('transaksi/print-perwabku', $data);
                break;
            case 'delete': 
                $this->m_transaksi->delete_perwabku(get_safe('id'));
                break;
            
        }
    }
    
    function get_list_data_perwabku($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_transaksi->get_data_perwabku($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['infopage'] = page_summary($data['jumlah'], $page, $limit);
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        return $data;
    }
    
    function get_data_kasir($id) {
        $data = $this->m_transaksi->get_data_kasir_by_id($id)->row();
        die(json_encode($data));
    }
    
    function anggaran_kegiatan() {
        $data['title'] = 'Anggaran Kegiatan';
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $this->load->view('transaksi/anggaran-kegiatan', $data);
    }
    
    function update_dana_pwk() {
        $query = $this->db->query("select p.*, sum(k.pengeluaran) as digunakan from perwabku p join detail_perwabku dp on (dp.id_perwabku = p.id) join kasir k on (dp.id_pengeluaran = k.id) group by p.id")->result();
        foreach ($query as $data) {
            $update = "update perwabku set dana_digunakan = '".$data->digunakan."' where id = '".$data->id."'";
            $this->db->query($update);
        }
        echo "OK";
    }
}