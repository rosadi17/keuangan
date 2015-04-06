<?php

class Laporan extends CI_Controller {
    
    function realisasi() {
        $data['title'] = 'Laporan Anggaran dan Realisasi';
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
        $this->load->view('laporan/realisasi', $data);
    }
    
    function manage_realisasi($action, $page = null) {
        switch ($action) {
            case 'list':
                $search['tahun'] = get_safe('tahun');
                $search['satker']= get_safe('satker');
                $data = $this->get_list_data_realisasi($search);
                $this->load->view('laporan/realisasi-table', $data);
                break;
        }
    }
    
    function get_list_data_realisasi($tahun) {
        $data['list_data'] = $this->m_laporan->load_satker($tahun)->result();
        return $data;
    }
    
    function pengeluaran() {
        $data['title'] = 'Laporan Pengeluaran Uang';
        $this->load->view('laporan/pengeluaran', $data);
    }
    
    function pencairan_normal() {
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
        $this->load->view('laporan/pencairan-normal', $data);
    }
    
    function manage_pencairan_normal($action, $page = null) {
        $limit = 100;
        switch ($action) {
            case 'list':
                $search['bulan'] = get_safe('year').'-'.get_safe('bln');
                $search['satker']= get_safe('id_satker');
                $search['proja'] = get_safe('uraian');
                $search['pjawab']= get_safe('png_jawab');
                $data = $this->get_list_data_pencairan_normal($limit, $page, $search);
                $this->load->view('laporan/pencairan-normal-table', $data);
                break;
        }
    }
    
    function get_list_data_pencairan_normal($limit, $page, $search) {
        if ($page == 'undefined') {
            $page = 1;
        }
        //$str = 'null';
        $start = ($page - 1) * $limit;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['auto'] = $start+1;
        $query = $this->m_laporan->get_data_pencairan_normal($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, $search);
        return $data;
    }
    
    function cashbon() {
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
        $this->load->view('laporan/cashbon', $data);
    }
    
    function manage_cashbon($action, $page = null) {
        $limit = 100;
        switch ($action) {
            case 'list':
                $search['key'] = $_GET['search'];
                $search['id']  = $_GET['id'];
                $search['bulan'] = $_GET['bulan'];
                $search['satker']= $_GET['id_satker'];
                $search['proja'] = $_GET['proja'];
                $search['pjawab']= $_GET['pjawab'];
                $data = $this->get_list_data_cashbon($limit, $page, $search);
                $this->load->view('laporan/cashbon-table', $data);
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
        $query = $this->m_laporan->get_data_cashbon($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, $search);
        return $data;
    }
    
    function grafik() {
        $data['title'] = 'Grafik Renbut dan Realisasi Tiap Satker';
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
        $data['satker']= $this->m_masterdata->load_satker()->result();
        $this->load->view('laporan/grafik', $data);
    }
    
    function grafik_list() {
        $bulan  = get_safe('bulan');
        $satker = get_safe('id_satker');
        $result = $this->m_laporan->grafik_penggunaan_load_data($bulan, $satker)->result();
        $realisasi[] = array();
        $bln[] = array();
        $rata[] = array();
        $data = "";
        foreach ($result as $hasil) {
            $data = $hasil->satker;
            $realisasi[] = (int)$hasil->realisasi;
            $bln[] = tampil_bulan($hasil->bulan);
            $rata[]   = (int)$hasil->rata_pagu;
        }
        
        die(json_encode(array('satker' => $data, 'realisasi' => $realisasi, 'bulan' => $bln, 'ratarata' => $rata)));
    }
    
    function grafik_home() {
        $agama = array(
            'Pusat Biaya' => 'Sudah',
            'Down Payment' => 'Belum',
            'Default' => 'Default'
        );
        $data = array();

        $total = 0;

        foreach ($agama as $key => $row) {
            //$db = "select B_07 as nama,count(*) as jml from MASTFIP08 where `B_07` = '$row' and A_01<>'99' $q and (B_07<>'' or B_07 is not null)";
            $query = $this->m_laporan->load_data_grafik_pengeluaran($row);
            //echo $db;
            $data[] = array($row, (int)$query->pengeluaran);
            $total += (int)$query->pengeluaran;
        }
        //return array('data' => $data, 'total' => $total);
        $title = strtoupper("Rekap Pengeluaran Kasir Berdasarkan Perwabku");
        die(json_encode(array('data' => $data, 'total' => $total, 'title' => $title)));
    }
    
    function kasbank() {
        $data['title'] = 'Catatan Kas & Bank';
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
        $this->load->view('laporan/kasbank', $data);
    }
    
    function get_data_kas_bank() {
        $bulan = get_safe('tahun');
        $data['awal_kas']  = $this->m_laporan->get_saldo_awal_kas($bulan)->row();
        $data['list_data'] = $this->m_laporan->get_data_kas_bank($bulan)->result();
        
        $data['awal_kas_dropping']  = $this->m_laporan->get_saldo_awal_kas($bulan)->row();
        $data['list_data_dropping'] = $this->m_laporan->get_data_kas_bank($bulan, 'Rekening Rektor')->result();
        
        $data['awal_kas_kelas_int']  = $this->m_laporan->get_saldo_awal_kas($bulan)->row();
        $data['list_data_kelas_int'] = $this->m_laporan->get_data_kas_bank($bulan, 'Kelas Internasional')->result();
        $this->load->view('laporan/kasbank-list', $data);
    }
    
    function renbut() {
        $data['title'] = 'Rekap Rencana Kebutuhan';
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
        $this->load->view('laporan/rekap-renbut', $data);
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
                $this->load->view('laporan/rekap-renbut-table', $data);
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
        $query = $this->m_laporan->get_data_renbut($limit, $start, $search);
        $data['list_data'] = $query['data'];
        $data['jumlah'] = $query['jumlah'];
        
        $data['paging'] = paging_ajax($data['jumlah'], $limit, $page, 1, null);
        return $data;
    }
    
    function export_excel_renbut() {
        $search = array(
            'satker' =>  get_safe('id_satker'),
            'awal' => date2mysql(get_safe('awal')),
            'akhir' => date2mysql(get_safe('akhir')),
            'awal_keg' => date2mysql(get_safe('awal_keg')),
            'akhir_keg' => date2mysql(get_safe('akhir_keg')),
            'kegiatan' => get_safe('kegiatan'),
            'jenis' => get_safe('jenis_renbut')
        );
        $data = $this->get_list_data_renbut(null, null, $search);
        $data['bulan']   = get_safe('year').'-'.get_safe('bln').'-01';
        $data['tahun']   = get_safe('year');
        $this->load->view('laporan/excel-rekap-renbut', $data);
    }
}