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
class ProductCategory extends Model {

    var $table = 'product_category';
    var $db;

    public function __construct() {
        parent::__construct();
    }
    
    public function findAll() {
        return $this->recherche();
    }
    public function findAllAndDetails(){
        $sql = "SELECT pc.id as pc_id,pc.pc_name, COUNT(pro_name) as nb_pro_category FROM product_category pc  JOIN product p ON pc.id=p.pro_category GROUP BY pc.id";
        return $this->executerReq($sql);
    }
}

