<?php

$utilisateur = new User();
$mUserProful = new UserProfil();
$logsActions = new Log();
$tuteurModel = new Tuteur();

$valid_user = FALSE;
$user = array();


phpCAS::setDebug();
phpCAS::setVerbose(true);
phpCAS::client(CAS_VERSION_2_0, CAS, 443, '/cas');
phpCAS::setNoCasServerValidation();

phpCAS::handleLogoutRequests();
phpCAS::forceAuthentication();

if (!empty(phpCAS::getUser()) && empty($_SESSION['phpCAS']['connexion'])) {

    $mail = phpCAS::getUser();

    if (LDAPUtils::countUser($mail) == 1) {
        $valid_user = TRUE;
    }

    if ($valid_user) {
        $_SESSION['phpCAS']['profil'] = 0;

        $req = $utilisateur->recherche(array("conditions" => "mail='" . $mail . "'", "limit" => 1));
        $use = !empty($req) ? $req[0] : array();

        if (!empty($use)) {
            $userprofil = $mUserProful->findProfilsByUser($use['id']);
            $listProfil = array();
            $idsProfils = array();
            if (!empty($userprofil)) {
                foreach ($userprofil as $value) {
                    error_log("profils " . $value['code']);
                    $listProfil[] = $value['code'];
                    $idsProfils[] = $value['id_profil'];
                }
            } else {
                error_log("userProfil empty...");
                phpCAS::logout();
            }

            if (!empty($userprofil)) {
                $_SESSION['phpCAS']['connexion'] = TRUE;
                $_SESSION['phpCAS']['utilisateur'] = intval($userprofil[0]['user']);

                if (!empty($use[0]['eno'])) {
                    $_SESSION['phpCAS']['eno'] = intval($use['eno']);
                }

                $_SESSION['phpCAS']['prenom'] = utf8_encode($use['prenom']);
                $_SESSION['phpCAS']['nom'] = $use['nom'];
                $_SESSION['phpCAS']['profil'] = $listProfil;
                $_SESSION['phpCAS']['annee_scolaire'] = DEFAULT_YEAR;

                // - Si PROFIL = TUTO_TUTEUR
                if (in_array("TUTO_TUTEUR", $_SESSION['phpCAS']['profil'])) {
                    $response = $tuteurModel->recherche(array("conditions" => "user = " . intval($userprofil[0]['user'])));
                    $tuteurIn = array();
                    if (!empty($response)) {
                        $tuteurIn = $response[0];
                        $_SESSION['phpCAS']['id_tuteur'] = intval($tuteurIn['id']);
                        $_SESSION['phpCAS']['eno'] = intval($tuteurIn['eno']);
                        header("location:" . Tools::generateURL("tuteurs/" . $_SESSION['phpCAS']['id_tuteur']));
                    } else {
                        header("location:" . Tools::generateURL("logout"));
                    }
                }

                // - Si PROFIL = TUTO_POLE
                if (in_array("TUTO_POLE", $_SESSION['phpCAS']['profil'])) {
                    $_SESSION['phpCAS']['pole'] = intval($use['pole']);
                }
                
                // - Si PROFIL = TUTO_POLE
                if (in_array("TUTO_ENO", $_SESSION['phpCAS']['profil'])) {
                    
                }

                // - Menus
                $_SESSION['phpCAS']['menus'] = $mUserProful->findMenusByProfil($idsProfils);

                $logsActions->ajouter(array('type' => CNX, 'user' => $_SESSION['phpCAS']['utilisateur'], "valeur" => serialize($_SESSION['phpCAS'])));
            } else {
                header("location:" . Tools::generateURL("logout"));
            }
        } else {
            header("location:" . Tools::generateURL("logout"));
        }
    }
}

