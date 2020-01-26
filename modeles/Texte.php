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
class Texte extends Model {

    var $prefix = 'prd';
    var $table = 'texte';
    var $db;

    public function __construct() {
        parent::__construct();
    }

    public function findAll() {
        $sql = "SELECT * From texte where statut=1";
        
        return $this->recherche(array(),$conditions);
    }

}
