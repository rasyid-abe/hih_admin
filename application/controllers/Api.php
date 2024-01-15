<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH .'controllers/Appauth.php';

class Api extends Appauth {

    function __construct()
    {
        parent::__construct();
        $this->validation();
        $this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation', 'upload'));
        $this->load->model('user_m', 'user');
        $this->load->model('documents_m', 'documents');
    }

    public function home_get()
    {
        $result = $this->documents->get_home($_GET['nik']);
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }
    
    public function list_document_get()
    {
        $result = $this->documents->get_list_document($_GET);
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }

    public function document_text_get()
    {
        $result = $this->documents->get_document_text($_GET);
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }

    public function document_pdf_get()
    {
        $result = $this->documents->get_document_pdf($_GET);
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }

    public function change_password_post()
    {
        $result = $this->user->post_change_password($_POST);
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }

    public function profile_get()
    {
        $result = $this->user->get_profile($_GET);
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }

    public function profile_post()
    {
        $result = $this->user->post_profile($_POST, $_FILES);
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }
   
    public function fraud_post()
    {
        $result = $this->documents->post_fraud($_POST, $_FILES);
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }
   
    public function fraud_list_get()
    {
        $result = $this->documents->get_fraud_list($_GET['nik']);
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }
   
    public function fraud_detail_get()
    {
        $result = $this->documents->get_fraud_detail($_GET);
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }
   
    public function search_get()
    {
        $result = $this->documents->get_search($_GET);
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }
    
    public function termcondition_get()
    {
        $result = $this->documents->get_termcondition($_GET);
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }



    // if(true) {
    //     $this->response([
    //         'status' => true,
    //         'data' => [$_FILES, $_POST]
    //     ], self::HTTP_OK);
    // } else {
    //     $this->response([
    //         'status' => FALSE,
    //         'data' => 'Account is not registered!'
    //     ], self::HTTP_NOT_FOUND);
    // }
    
}
