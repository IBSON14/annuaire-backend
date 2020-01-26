<?php

mb_internal_encoding('UTF-8');

define('ROOT', dirname(__FILE__) . '/');

error_log($_SERVER['SERVER_NAME'] . " " . $_SERVER['SERVER_ADDR']." ". ROOT);

if (($_SERVER['SERVER_NAME'] == 'localhost') || ($_SERVER['SERVER_ADDR'] == '::1')) {
    require_once 'params_local.php';
    define('APP_NAME', '/annuaire-backend');
} else {
    require_once 'params.php';
    define('APP_NAME', '');
    define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');
}


define('LIB_DIR', ROOT . 'libs/');
define('SRC_DIR', ROOT . 'modules/');
define('MOD_DIR', ROOT . 'modeles/');
define('CFG_DIR', dirname(__FILE__) . '/');

define('FILE_DIR', APP_NAME . '/web/upload/');
define('BASE_PATH', ROOT . '/web/index.php');

// - Transformer toutes les erreurs en ErrorException
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log($errno . "-" . $errstr . "-" . $errfile . "-" . $errline);
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
});

// - Traiter toutes les ErrorException non Catches
set_exception_handler(function($ex) {
    http_response_code(500);
    error_log("Internal Error " . $ex->getMessage());
    echo "Internal Error";
});

require_once 'autoload.php';

$autoloader = DirectoriesAutoloader::instance('cache');
$autoloader->addDirectory('libs')
        ->addDirectory('modules')
        ->addDirectory('core')
        ->addDirectory('modeles');
spl_autoload_register(array($autoloader, 'autoload'));

require_once 'libs/autoload.php';
