<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LDAPUtils
 *
 * @author <ahmet.thiam@uvs.edu.sn>
 */
class LDAPUtils {

    public static function countUser($user) {
        $query = "uid=" . $user . "";
        $value = array("*", "+");
        try {
            $connec = Connect::getLDAPConnection(LDAP_SERVER);
            $result = ldap_search($connec, LDAP_BASE_DN, $query, $value);
            $info = ldap_get_entries($connec, $result);
            return $info['count'];
        } catch (ErrorException $ex) {
            throw new ErrorException($ex);
        }
    }

    public static function addEntry($infos) {
        try {
            $connec = Connect::getLDAPConnection(LDAP_SERVER);
            return ldap_add($connec, "uid=" . $infos['uid'] . ",ou=people,dc=uvs,dc=sn", $infos);
        } catch (ErrorException $ex) {
            throw new ErrorException($ex);
        }
    }

    public static function majEntry($uid, $infos) {
        try {
            $connec = Connect::getLDAPConnection(LDAP_SERVER);
            $userSR = ldap_search($connec, LDAP_BASE_DN, "(uid=" . $uid . ")");
            if ($userSR) {
                $userEn = ldap_get_entries($connec, $userSR);
                $countA = $userEn['count'];
            }
            error_log("Verification LDAP " . $uid . " " . $countA . "\n");
            if ($countA == 1) {
                $userDN = ldap_get_dn($connec, ldap_first_entry($connec, $userSR));
                if (ldap_mod_replace($connec, $userDN, $infos)) {
                    return "0;" . $uid;
                } else {
                    return "1;" . $uid;
                }
            }
        } catch (ErrorException $ex) {
            throw new ErrorException($ex);
        }
    }

    public static function changeLDAPPassword($uid, $pwd) {
        try {
            $connec = Connect::getLDAPConnection(LDAP_SERVER);
            $result = ldap_search($connec, LDAP_BASE_DN, "(uid=" . $uid . ")");
            $count = 0;
            if ($result) {
                $userE = ldap_get_entries($connec, $result);
                $count = $userE['count'];
            }
            error_log("searchResult4 " . $uid . "[" . $count . "]");
            if ($count == 1) {
                $userDN = ldap_get_dn($connec, ldap_first_entry($connec, $result));
                if (ldap_mod_replace($connec, $userDN, array('userPassword' => $pwd))) {
                    return true;
                } else {
                    return false;
                }
            }
        } catch (ErrorException $ex) {
            throw new ErrorException($ex);
        }
    }

}
