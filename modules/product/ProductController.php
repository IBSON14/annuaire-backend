<?php

class ProductController {

    protected $m_product;
    

    public function __construct() {
        $this->m_product = new Product();
    }

    public function productsByCategory() {
        $response = array();
        try {
            
        } catch (PDOException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        } catch (ErrorException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        }
        echo json_encode($response);
    }

    public function productDetails($id) {
        $response = array();
        try {
            $response = $this->m_product->findProductById($id);
        } catch (PDOException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        } catch (ErrorException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        }
        echo json_encode($response);
    }

    public function allProducts() {
        $response = array();
        try {
            $response = $this->m_product->findAll();
        } catch (PDOException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        } catch (ErrorException $exc) {
            $response = array("status" => "1", "errorcode" => "111", "errormessage" => "Internal error");
        }
        echo json_encode($response);
    }

}
