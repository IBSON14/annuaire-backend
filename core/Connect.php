<?php

class Connect extends PDO {

    private static $_instance_C;
    
    public static function getInstanceC() {
        if (!isset(self::$_instance_C)) {
            try {
                self::$_instance_C = new PDO('mysql:host=' . HOST . ';dbname=' . DB_NAME, USER, MDP, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            } catch (PDOException $e) {
                error_log($e->getMessage());
                die('Erreur de connexion a la base [' . $e->getMessage() . "]");
            }
        } else {
        }
        return self::$_instance_C;
    }

    

}
