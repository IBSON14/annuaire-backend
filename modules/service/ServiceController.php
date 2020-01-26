<?php

class ServiceController {

    protected $m_service;

    public function __construct() {
        $this->m_service = new Service();
    }
    
     public function addServices() {
        $response = array();
        try {
            $data = json_decode(file_get_contents("php://input"));
            
            $infos['sv_name'] = $data->sv_name;
            $infos['sv_description'] = $data->sv_description;
            $infos['sv_category'] = $data->sv_category;
            $infos['sv_user'] = $data->sv_user;
            
            $id = $this->m_service->ajouter($infos, TRUE);
            if($id != 0){
                $infos['id'] = $id;
                $response = array("status" => "0", "data"=>$infos);
            } else {
                $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Ajout error.");
            }
        } catch (PDOException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        } catch (ErrorException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        }
        echo json_encode($response);
    }
    
    public function servicesByCategory($id) {
        $response = array();
        try {
            $response = $this->m_service->findServiceByCategory($id);
        } catch (PDOException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        } catch (ErrorException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        }
        echo json_encode($response);
    }

    public function serviceDetails($id) {
        $response = array();
        try {
            $response = $this->m_service->findById($id);
        } catch (PDOException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        } catch (ErrorException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        }
        echo json_encode($response);
    }
    
    public function allServices() {
        $response = array();
        try {
            $response = $this->m_service->findAll();
        } catch (PDOException $exc) {;
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        } catch (ErrorException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        }
        echo json_encode($response);
    }

}
