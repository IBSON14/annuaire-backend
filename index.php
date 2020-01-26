<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/xml,application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/* Les configs */
require_once 'configs/config.php';
require_once 'configs/routes.php';
//require_once 'core/connexion.php';

$match = $router->match();
$async = true;
$login = false;
$erreurs = array();
$module = '';

if (isset($match['name'])) {
    $explode = explode('_', $match['name']);
    $module = $explode[0];
} else {
    $module = 'home';
}

set_include_path('.'
        . PATH_SEPARATOR . 'libs'
        . PATH_SEPARATOR . 'core'
        . PATH_SEPARATOR . 'modeles'
        . PATH_SEPARATOR . 'modules' . get_include_path());

// Debut de la tamporisation de sortie
ob_start();
try {
    $auth = TRUE;
    if (!$auth) {
        $erreurs[] = "Vous n'êtes pas autorisé à acceder à cette page.";
    } else {
        if (empty($match['target'])) {
            $erreurs[] = 'Pas de controleur pour cet url :' . $_SERVER['REDIRECT_URL'];
        } else {
            $control = $match['target']['c'];
            /* Le controleur */
            if (class_exists($control)) {
                $action = (isset($match['target']['a'])) ? $match['target']['a'] : 'actionIndex';
                $params = (!empty($match['params'])) ? $match['params'] : array();
                /* l'action */
                if (!method_exists($control, $action)) {
                    $erreurs[] = "Le controleur <b><font color='red'>$control</b></font> ne possède pas d'action $action ";
                } else {
                    call_user_func_array(array(new $control(), $action), $params);
                }
            } else {
                $erreurs[] = "Le controleur <b><font color='red'>$control</b></font> n'existe pas";
            }
        }
    }
} catch (Exception $ex) {
    $erreurs[] = $ex->getMessage();
} catch (ErrorException $ex) {
    $erreurs[] = $ex->getMessage();
}

/* Fin de la tamporisation de sortie */
$contenu = ob_get_clean();

/* affichage du tampon */
if ($async) {
    if (!empty($erreurs)) {
        echo json_encode($erreurs);
    } else {
        echo $contenu;
    }
} else {
    echo $contenu;
}



