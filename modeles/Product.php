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
class Product extends BaseDao {

    var $table = 'product';
    var $db;

    public function __construct() {
        parent::__construct();
    }
    
    public function findAll() {
        $sql = "SELECT p.id as pro_id, p.pro_description, p.pro_name, pc.id as pc_id, pc.pc_name, pc.pc_description, u.id, u.first_name,u.last_name, u.phone, u.email,u.address FROM product p
                 JOIN user u ON u.id = p.pro_user
                 JOIN product_category pc ON pc.id = p.pro_category";
        return $this->executerReq($sql);
    }
    
    public function findSerciceResearch($cle,$pc_id) {
        $sql = "SELECT p.id as id_pro,pc.pc_name,p.pro_name,u.id, u.first_name,u.last_name, u.phone, u.email,u.address, c.id, c.ct_name FROM product p "
                ."JOIN user u ON u.id = p.pro_user AND  p.pro_name LIKE '%".$cle."%'"
                ."JOIN product_category pc ON pc.id = p.pro_category AND pc.id='".$pc_id."'"
                ."JOIN city c ON c.id = u.fk_city";
        return $this->executerReq($sql);
    } 
    
    public function detailsProduct($cle, $id) {
        $sql = "SELECT p.id as id_pro,p.pro_description,pc.id as id_pc,pc.pc_name,p.pro_name,u.id, u.first_name,u.last_name, u.phone, u.email,u.address, c.id, c.ct_name FROM product p "
                ."JOIN user u ON u.id = p.pro_user AND  p.pro_name LIKE '%".$cle."%'"
                ."JOIN product_category pc ON pc.id = p.pro_category AND p.id='".$id."'"
                ."JOIN city c ON c.id = u.fk_city";
        return $this->executerReq($sql);
    } 
    
    public function findProductById($id) {
        $res = $this->recherche(array("conditions"=> "id = ".$id));
        return (!empty($res)) ? $res[0] : array();
    } 
}
