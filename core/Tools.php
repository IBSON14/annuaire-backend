<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tools
 *
 * @author <ahmet.thiam@uvs.edu.sn>
 */
use \Firebase\JWT\JWT;

class Tools {

    public static function generateURL($url) {
        return APP_NAME . '/' . $url;
    }

    public static function contains($contenu, $contenant) {
        return strpos($contenant, $contenu) !== false;
    }

    public static function loadFile($name, $file_name) {

        $target_file = ROOT . "web/upload/" . $file_name . "_" . basename($_FILES[$name]["name"]);
        $fileMimeTyp = pathinfo($target_file, PATHINFO_EXTENSION);

        // Check if file already exists
        if (file_exists($target_file)) {
            return "1;Désolé. Le fichier existe déjà.";
        }
        // Check file size
        if ($_FILES[$name]["size"] > 500000) {
            return "1;La taille est trop grande.";
        }
        // Allow certain file formats
        if (!in_array($fileMimeTyp, array('xlsx', 'jpeg', 'jpg', 'png', 'docx', 'csv'))) {
            return "1;Format non reconnu.";
        }
        // Check if $uploadOk is set to 0 by an error
        if (move_uploaded_file($_FILES[$name]["tmp_name"], $target_file)) {
            return "0;" . $target_file;
        } else {
            return "1;Désolé. Erreur lors du chargement.";
        }
    }

    public static function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public static function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }

    public static function getClass4showDocument($url) {
        if (($url == null) || empty($url) || empty($url)) {
            return "";
        }
        if (!self::contains(".", $url)) {
            return "";
        }
        error_log($url);

        $parse = explode(".", $url);
        if (($parse[1] == 'pdf') || ($parse[1] == 'PDF')) {
            return "class='showDocumentDialog'";
        } else {
            return "class='showDocumentImgBtn'";
        }
    }

    public static function verifyJWTToken() {
        $token = self::getBearerToken();
        if (!isset($token) || empty($token)) {
            return array("code" => "111", "message" => "Access denied", "error" => "Header is missing");
        }

        if ($token) {
            try {
                $decoded = JWT::decode($token, SECRET_KEY, array('HS256'));
                return array("code" => "000", "message" => "Access granted:", "error" => "");
            } catch (Exception $e) {
                return array("code" => "111", "message" => "Access denied.", "error" => $e->getMessage());
            }
        }
    }

    public static function array_to_xml($data, $root) {
        $xml_data = new SimpleXMLElement(sprintf('<?xml version="1.0"?><%s></%s>',$root, $root));
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item' . $key; //dealing with <0/>..<n/> issues
            }
            if (is_array($value)) {
                $subnode = $xml_data->addChild($key);
                array_to_xml($value, $subnode);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
        return $xml_data->asXML();
    }

    /**
     * Get header Authorization
     * */
    public static function getAuthorizationHeader() {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * get access token from header
     * */
    public static function getBearerToken() {
        $headers = self::getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
    
    public static function getBasicToken() {
        $headers = self::getAuthorizationHeader();
        error_log("getBasicToken " . $headers);
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Basic\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        } else {
            error_log("header is empty...");
        }
        return null;
    }

}
