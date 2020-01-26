<?php

Class MailUtils {

    var $mInscription;
    var $mUser;

    public function __construct() {
        $this->mInscription = new Inscription();
        $this->mUser = new User();
    }

    private function creationCompte($prenom, $nom) {
        $prenom1 = $prenom;
        $nom1 = $nom;

        $prenom2 = str_replace(' ', '', $prenom1);
        $prenom2 = str_replace('\'', '', $prenom2);

        $nom2 = str_replace(' ', '', $nom1);
        $nom2 = str_replace('\'', '', $nom2);

        $prenom = strtolower($prenom2);
        $nom = strtolower($nom2);

        $prenom_nom = $prenom . "." . $nom;
        $prenom_nom = strtr(utf8_encode($prenom_nom), 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');

        // return substr(trim($prenom_nom), 0, -1);
        return trim($prenom_nom);
    }

    private function suppr_accents($str, $encoding = 'utf-8') {
        // transformer les caractères accentués en entités HTML
        $str = htmlentities($str, ENT_NOQUOTES, $encoding);

        // remplacer les entités HTML pour avoir juste le premier caractères non accentués
        // Exemple : "&ecute;" => "e", "&Ecute;" => "E", "à" => "a" ...
        $str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);

        // Remplacer les ligatures tel que : <8c>, Æ ...
        // Exemple "œ" => "oe"
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
        // Supprimer tout le reste
        $str = preg_replace('#&[^;]+;#', '', $str);

        return $str;
    }

    private function mdp() {
        $codeMaj = 'AZERTYUIOPQSDFGHJKLMWXCVBN';
        $code = 'aertyuiopqsdhjklmcvbn';
        $cpt = strlen($code) - 1;
        $pass = "";

        $j = rand(0, strlen($codeMaj) - 1);
        $pass = $pass . $codeMaj[$j];

        for ($i = 0; $i < 4; $i++) {
            $j = rand(0, $cpt);
            $pass = $pass . $code[$j];
        }
        return $pass . '@' . rand(1000, 9999);
    }

    public function createAccount($user) {
        $prenom = $pnom = $user['prenom'];
        $nom = $nnom = $user['nom'];

        if (strlen($prenom) > 20) {
            $tabpnom = explode(" ", $prenom);
            $prenom = $tabpnom[0];
            $prenom .= $tabpnom[1];
        }

        if (!empty($prenom) && !empty($nom)) {
            $nameE = $this->mInscription->findNameByMailPerso($user['mail_perso']);
            if (isset($nameE) && !empty($nameE)) {
                $maill = $nameE['mail_inst_2'];
            } else {
                $login = $this->creationCompte($this->suppr_accents(utf8_encode($prenom)), $nom);
                if ($login) {
                    $maill = $login . '@uvs.edu.sn';
                    if ($this->mInscription->mailExist($maill)) {
                        $j = 1;
                        while ($this->mInscription->mailExist($maill)) {
                            $maill = str_replace('.', $j . ".", $login) . '@uvs.edu.sn';
                            $j++;
                        }
                    }
                }
            }

            $mdp = (isset($user['mdp'])) ? $user['mdp'] : $this->mdp();
            $rsl = $this->mInscription->saveMail($prenom, $nom, $user['mail_perso'], $maill, $mdp, $user['id']);
            if ($rsl) {
                $res = "0;" . $user['mail_perso'] . ";" . $maill . ";" . $mdp;
            } else {
                $res = "1;" . $user['mail_perso'] . ";" . $maill . ";" . $mdp;
            }
            error_log("Creation compte [" . $user['mail_perso'] . " - " . $maill . "] - " . substr($res, 0, 1));
            return $res;
        }
    }

    public function createLDAPAccount($value) {
        try {
            $name = $uid = rand(1000, 10000000);
            $infosLd = array();

            $infosLd["objectClass"][0] = "inetOrgPerson";
            $infosLd["objectClass"][1] = "organizationalPerson";
            $infosLd["objectClass"][2] = "supannPerson";
            $infosLd['objectClass'][3] = "person";
            $infosLd["objectClass"][4] = "supannOrg";
            $infosLd["objectClass"][5] = "eduPerson";
            $infosLd["objectClass"][6] = "posixAccount";

            $infosLd["givenName"] = ucfirst(utf8_encode($value["prenom"]));
            $infosLd["sn"] = utf8_encode($value["nom"]);
            $infosLd["displayName"] = $infosLd["givenName"] . " " . $infosLd["sn"];
            $infosLd["cn"] = $infosLd["displayName"];
            $infosLd["mail"] = $value["mail"];
            $infosLd['edupersonprimaryaffiliation'] = "Personnel";
            $infosLd["mobile"] = !empty($value["tel"]) ? $value["tel"] : "NULL";
            $infosLd["preferredLanguage"] = 'fr-FR';
            $infosLd["uid"] = $value["mail_inst_2"];
            $infosLd["l"] = $value['eno'];
            $infosLd["supannCivilite"] = substr($value["genre"], 0, 1);
            $infosLd["supannCivilite"] = !empty($value["genre"]) ? substr($value["genre"], 0, 1) : "NULL";
            $infosLd["supannMailPerso"] = !empty($value["mail_inst_2"]) ? $value["mail_inst_2"] : "NULL";
            $infosLd["telephoneNumber"] = !empty($value["tel"]) ? $value["tel"] : "NULL";
            $infosLd["street"] = !empty($value["adresse"]) ? $value["adresse"] : "NULL";
            $infosLd["userPassword"] = $value['pwd'];
            $infosLd["uidNumber"] = $uid;
            $infosLd["gidNumber"] = $uid;
            $infosLd["homeDirectory"] = "/home/" . $value['matricule'];

            if (LDAPUtils::countUser($infosLd['uid']) > 0) {
                if (LDAPUtils::addEntry($infosLd)) {
                    $this->mUser->modifier(array("id" => $value['id'], "ldap" => TRUE));
                    $res = "0;" . $infosLd['uid'];
                } else {
                    $res = "1;" . $infosLd['uid'];
                }
            } else {
                $this->mUser->modifier(array("id" => $value['id'], "ldap" => TRUE));
                $res = "2;" . $infosLd['uid'];
            }
        } catch (ErrorException $ex) {
            error_log("ErrorException " . $ex->getMessage());
            $res = "1;" . $ex->getMessage();
        }
        return $res;
        error_log("Creation LDAP [" . $value['mail_perso'] . " - " . $value['mail'] . "] - " . $res);
    }

    public function generateMatricule($perso) {
        try {
            $counts = $this->mUser->getLastMatricules();
            if (!empty($perso) && !empty($counts)) {
                $pos = 0;
                if ($pos == 0) {
                    $pos = (isset($counts[$perso['year']])) ? intval(substr($counts[$perso['year']], 3, strlen($counts[$perso['year']]))) : 1;
                }
                $matricule = "T" . $perso['year'] . str_pad(++$pos, 3, "0", STR_PAD_LEFT);
                /*if (LDAPUtils::majEntry($perso['mail_inst_2'], array('supannEmpId' => $matricule))) {
                    error_log("maj matricule LDAP" . $perso['mail_inst_2'] . " " . $matricule . " - OK");
                } else {
                    error_log("maj matricule LDAP " . $perso['mail_inst_2'] . " " . $matricule . " - KO");
                }*/

                if ($this->mUser->modifier(array("id" => $perso['id'], "matricule" => $matricule))) {
                    error_log("maj matricule BD " . $perso['mail_inst_2'] . " " . $matricule . " - OK");
                } else {
                    error_log("maj matricule BD " . $perso['mail_inst_2'] . " " . $matricule . " - KO");
                }
                return "0;" . $matricule;
            }
        } catch (ErrorException $ex) {
            throw new ErrorException($ex);
        }
    }

}
