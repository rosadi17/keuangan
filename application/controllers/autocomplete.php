<?php

class Autocomplete extends CI_Controller {
  
    function satker() {
        $q = $_GET['q'];
        $data = $this->m_autocomplete->get_satker($q)->result();
        die(json_encode($data));
    }
    
    function program() {
        $q = $_GET['q'];
        $id_satker = $_GET['id_satker'];
        $status    = $_GET['status'];
        $data = $this->m_autocomplete->get_program($q, $id_satker, $status)->result();
        die(json_encode($data));
    }
    
    function kegiatan() {
        $q = $_GET['q'];
        $id_satker = $_GET['id_satker'];
        $status    = $_GET['status'];
        $data = $this->m_autocomplete->get_kegiatan($q, $id_satker, $status)->result();
        die(json_encode($data));
    }
    
    function sub_kegiatan() {
        $q = $_GET['q'];
        $id_satker = $_GET['id_satker'];
        $status    = $_GET['status'];
        $data = $this->m_autocomplete->get_sub_kegiatan($q, $id_satker, $status)->result();
        die(json_encode($data));
    }
    
    function uraian() {
        $q = $_GET['q'];
        $id_satker = $_GET['id_satker'];
        $status    = $_GET['status'];
        $data = $this->m_autocomplete->get_uraian($q, $id_satker, $status)->result();
        die(json_encode($data));
    }
    
    function sub_uraian() {
        $q = $_GET['q'];
        $id_satker = $_GET['id_satker'];
        $status    = $_GET['status'];
        $data = $this->m_autocomplete->get_sub_uraian($q, $id_satker, $status)->result();
        die(json_encode($data));
    }
    
    function ma_proja() {
        $q = $_GET['q'];
        $data = $this->m_autocomplete->get_ma_proja($q)->result();
        die(json_encode($data));
    }
    
    function get_last_code($table, $kolom, $id_parent) {
        $data = $this->m_autocomplete->get_last_code($table, $kolom, $id_parent)->row();
        die(json_encode($data));
    }
    
    function get_last_code_kasir($trans) {
        $data = $this->m_autocomplete->get_last_code_kasir($trans);
        die(json_encode($data));
    }
    
    function get_auto_last_code_program($satker, $status) {
        $data = $this->m_autocomplete->get_auto_last_code_program($satker, $status)->row();
        die(json_encode($data));
    }
    
    function get_auto_last_code_kegiatan($id_program) {
        $data = $this->m_autocomplete->get_auto_last_code_kegiatan($id_program)->row();
        die(json_encode($data));
    }
    
    function get_auto_last_code_sub_kegiatan($id_kegiatan) {
        $data = $this->m_autocomplete->get_auto_last_code_sub_kegiatan($id_kegiatan)->row();
        die(json_encode($data));
    }
    
    function get_auto_last_code_uraian($id_sub_kegiatan) {
        $data = $this->m_autocomplete->get_auto_last_code_uraian($id_sub_kegiatan)->row();
        die(json_encode($data));
    }
    
    function get_auto_last_code($table, $kolom, $id_parent) {
        $data = $this->m_autocomplete->get_auto_last_code($table, $kolom, $id_parent)->row();
        die(json_encode($data));
    }
    
    function kode_perkiraan() {
        $q = $_GET['q'];
        $data = $this->m_autocomplete->get_kode_perkiraan($q)->result();
        die(json_encode($data));
    }
    
    function get_nominal_renbut($id_uraian) {
        $data = $this->m_autocomplete->get_nominal_renbut($id_uraian)->row();
        die(json_encode($data));
    }
    
    function kode_renbut() {
        $data = $this->m_autocomplete->get_kode_renbut(get_safe('q'))->result();
        die(json_encode($data));
    }
}