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
class Publicite extends Model {

    var $table = 'publicite';
    var $db;

    public function __construct() {
        parent::__construct();
    }

    public function findAll() {
        $sql = "SELECT s.id, u.* FROM $this->table s JOIN crm_user u ON u.id = s.cus_fk_user";
        return $this->executerReq($sql);
    }
}
