<?php

class M_user extends CI_Model {
    
    function cek_login() {
        $query="select u.id, u.username, u.nama, u.password, ug.id as id_group
            from users u
            join user_group ug on (ug.id = u.id_user_group)
        where u.username = '".post_safe('username')."' and u.password = '".md5(post_safe('password'))."'";
        //echo $query;
        $hasil=$this->db->query($query);
        return $hasil->row();
    }
    
    function module_load_data($id=null) {
        $q = null;
        if ($id != null) {
            $q.="where pp.user_group_id = '$id' ";
        }else{
            $q = "where pp.user_group_id = '0'";
        }
        $sql = "select m.* from user_group_privileges pp
            join privileges p on (pp.privileges_id = p.id)
            join module m on (p.module_id = m.id)
            $q group by p.module_id";
        //echo $sql;
        return $this->db->query($sql);
    }
    
    function menu_user_load_data($id = null, $module = null) {
        $q = null;
        if ($id !== NULL) {
            $q.=" and u.id = '$id'";
        }
        if ($module !== NULL) {
            $q .=  "and p.module_id = '$module' ";
        }
        $sql = "select m.*, p.form_nama, p.url, p.module_id, p.id as id_privileges 
            from user_group_privileges pp
            join privileges p on (pp.privileges_id = p.id)
            join user_group ug on (pp.user_group_id = ug.id)
            join users u on (ug.id = u.id_user_group)
            join module m on (p.module_id = m.id)
            where p.id is not null $q and ug.id = '".$this->session->userdata('id_group')."' and p.show_desktop = '1'
            order by p.urut";
        //echo $sql;
        return $this->db->query($sql);
    }
}
?>