<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserProfil
 *
 * @author <ahmet.thiam@uvs.edu.sn>
 */
class Service extends BaseDao {

    var $table = 'service';
    var $db;

    public function __construct() {
        parent::__construct();
    }

    public function findAll() {
    $sql = "SELECT s.id as sv_id, s.sv_name, s.sv_description, sc.id as sc_id, sc.sc_name, sc.sc_description, 
            u.id as u_id, u.first_name, u.last_name, u.phone, u.email, u.address, ud.id as dm_id, 
            ud.dm_name, ud.dm_description FROM service s
            JOIN user u ON u.id = s.sv_user
            JOIN service_category sc ON sc.id = s.sv_category
            JOIN user_domaine ud ON ud.id = u.fk_domaine";
       return $this->executerReq($sql);
    }
    
     public function findById($id) {
     $sql =  "SELECT s.id as sv_id, s.sv_name, s.sv_description, sc.id as sc_id, sc.sc_name, sc.sc_description, 
        u.id as u_id, u.first_name, u.last_name, u.phone, u.email, u.address, ud.id as dm_id, 
        ud.dm_name, ud.dm_description FROM service s
        JOIN user u ON u.id = s.sv_user
        JOIN service_category sc ON sc.id = s.sv_category
        JOIN user_domaine ud ON ud.id = u.fk_domaine  where s.id = ".$id;
       $res = $this->executerReq($sql);
       return (!empty($res)) ? $res[0] : array();
    }

    public function findAllByKey($cle) {
        $sql = "SELECT s.id as id_sv,sc.id as id_sc, s.sv_name,sc.sc_name,u.id, u.first_name,u.last_name, u.phone, u.email,u.address, c.id, c.ct_name FROM service s "
                . "JOIN user u ON u.id = s.sv_user AND  s.sv_name LIKE '%" . $cle . "%'"
                . "JOIN service_category sc ON sc.id = s.sv_category"
                . "JOIN city c ON c.id = u.fk_city";
        return $this->executerReq($sql);
    }

    public function findServiceByCategory($sc_id) {
        $sql ="SELECT s.id as sv_id, s.sv_name, s.sv_description, sc.sc_name, 
                u.id as u_id, u.first_name, u.last_name, u.phone, u.email, u.address, ud.id as dm_id, 
                ud.dm_name, ud.dm_description FROM service s
                JOIN user u ON u.id = s.sv_user
                JOIN service_category sc ON sc.id = s.sv_category
                JOIN user_domaine ud ON ud.id = u.fk_domaine  where s.sv_category = ".$sc_id;
        return $this->executerReq($sql);
    }

    public function detailsService($cle, $id) {
        $sql = "SELECT s.id as id_sv,s.sv_description, sc.id as id_sc,s.sv_name,sc.sc_name,u.id, u.first_name,u.last_name, u.phone, u.email,u.address, c.id, c.ct_name FROM service s "
                . "JOIN user u ON u.id = s.sv_user AND  s.sv_name LIKE '%" . $cle . "%'"
                . "JOIN service_category sc ON sc.id = s.sv_category AND s.id='" . $id . "'"
                . "JOIN city c ON c.id = u.fk_city";
        return $this->executerReq($sql);
    }

}
