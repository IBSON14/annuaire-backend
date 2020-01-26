<?php

class ServiceCategoryController {

    protected $m_servicecategorie;

    public function __construct() {
        $this->m_servicecategorie = new ServiceCategory();
    }
    
     public function addServicesCategories() {
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
    

    public function categoryDetails($id) {
        $response = array();
        try {
            $response = $this->m_servicecategorie->findById($id);
        } catch (PDOException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        } catch (ErrorException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        }
        echo json_encode($response);
    }
    
    public function allCategories() {
        $response = array();
        try {
            $response = $this->m_servicecategorie->findAll();
        } catch (PDOException $exc) {;
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        } catch (ErrorException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        }
        echo json_encode($response);
    }

}
