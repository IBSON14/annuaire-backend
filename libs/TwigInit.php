<?php

/**
 * @author ahmet.thiam
 */
class TwigInit {

    private static $_instance;

    public function __construct() {
        
    }

    /* Singleton */
    public static function getInstance($module) {
        if (!isset(self::$_instance)) {
            try {
                $loader = new Twig_Loader_Filesystem(array('templates', 'modules/' . $module . '/vues'));
                self::$_instance = new Twig_Environment($loader, array('cache' => false));
            } catch (Exception $e) {
                die('Erreur intialisation Twig ' . $e->getMessage());
            }
        }
        return self::$_instance;
    }
    
}
