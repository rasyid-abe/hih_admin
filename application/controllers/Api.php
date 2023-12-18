<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH .'controllers/AppAuth.php';

class Api extends AppAuth {

    function __construct()
    {
        parent::__construct();
        $this->validation();
        $this->load->model('user_m', 'user');
        $this->load->model('documents_m', 'documents');
    }

    public function list_user_get()
    {
        // $users = $this->user->list_user();
        $users = $this->db->get('user');
        if ($users) {
            $this->response([
                'status' => true,
                'data' => $users->result_array()
            ], self::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'data' => 'Account is not registered!'
            ], self::HTTP_NOT_FOUND);
        }
    }
    
    public function index_get()
    {
        $result = $this->user->list_user();
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }

    public function profile_get()
    {
        $result = $this->user->get_profile($this->get('nik'));
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }

    public function profile_post()
    {
        $this->response([
            'status' => true,
            'data' => $_FILES
        ], self::HTTP_OK);
        // $result = $this->user->post_profile($this->input->post(),  $_FILES);
        // $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }

    public function change_password_post()
    {
        $result = $this->user->post_change_password($this->input->post(),  $_FILES);
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }

    public function documents_group_get()
    {
        $result = $this->user->get_documents_group();
        $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }
}
