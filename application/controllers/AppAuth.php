<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Appauth extends RestController {

    private $uniq;
    function __construct()
    {
        parent::__construct();
        $this->uniq = 'hihapp_v1.0';
        $this->load->model('user_m', 'user');
        $this->load->model('documents_m', 'documents');
    }

    public function show_post()
    {
        if(true) {
            $this->response([
                'status' => true,
                'data' => 'yey, we did i22t'
            ], self::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'data' => 'Account is not registered!'
            ], self::HTTP_NOT_FOUND);
        }

        // $result = $this->documents->get_home($_GET['nik']);
        // $this->response($result, $result['status'] ? self::HTTP_OK : self::HTTP_BAD_REQUEST);
    }

    public function login_axios_post()
    {
        $date = new DateTime();
        // $post = $this->input->post();
        // $nik = $this->input->post('nik');
		// $password = $this->input->post('password');
        
        $obj = json_decode(file_get_contents('php://input'));
        $nik = $obj->nik;
		$password = $obj->password;
        
		$user = $this->db->get_where('user', ['nik' => $nik])->row_array();
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $payload = [
                    'id' => $user['id'],
                    'fullname' => $user['fullname'],
                    'nik' => $user['nik'],
                    'iat' => $date->getTimestamp(),
                    'exp' => $date->getTimestamp() + (60*(60*3))
                ];
                
                $logs = [
                    'nik' => $nik,
                    'log' => 'Login App'
                ];
                activity($logs);

                $token = JWT::encode($payload, $this->uniq, 'HS256');

                $this->response([
                    'status' => true,
                    'data' => [
                        'id' => $user['id'],
                        'fullname' => $user['fullname'],
                        'foto' => $user['foto'],
                        'nik' => $user['nik'],
                    ],
                    'token' => $token
                ], self::HTTP_OK);

            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Wrong Password!'
                ], self::HTTP_FORBIDDEN);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Account is not registered!'
            ], self::HTTP_NOT_FOUND);
        }

    }
    public function index_post()
    {
        $date = new DateTime();
        $post = $this->input->post();
        $nik = $this->input->post('nik');
		$password = $this->input->post('password');
        
        // $obj = json_decode(file_get_contents('php://input'));
        // $nik = $obj->nik;
		// $password = $obj->password;
        
		$user = $this->db->get_where('user', ['nik' => $nik])->row_array();
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $payload = [
                    'id' => $user['id'],
                    'fullname' => $user['fullname'],
                    'nik' => $user['nik'],
                    'iat' => $date->getTimestamp(),
                    'exp' => $date->getTimestamp() + (60*(60*3))
                ];
                
                $logs = [
                    'nik' => $nik,
                    'log' => 'Login App'
                ];
                activity($logs);

                $token = JWT::encode($payload, $this->uniq, 'HS256');

                $this->response([
                    'status' => true,
                    'data' => [
                        'id' => $user['id'],
                        'fullname' => $user['fullname'],
                        'foto' => $user['foto'],
                        'nik' => $user['nik'],
                    ],
                    'token' => $token
                ], self::HTTP_OK);

            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Wrong Password!'
                ], self::HTTP_FORBIDDEN);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Account is not registered!'
            ], self::HTTP_NOT_FOUND);
        }

    }

    protected function validation()
    {
        $jwt = $this->input->get_request_header('Authorization');

        if (!isset($jwt)) {
            $this->response([
                'status' => false,
                'message' => 'Token is required!'
            ], self::HTTP_UNAUTHORIZED);
        }

        try {
            JWT::decode($jwt, new Key($this->uniq, 'HS256'));
        } catch (Exception $e) {
            $this->response([
                'status' => false,
                'message' => 'Invalid Token!'
            ], self::HTTP_UNAUTHORIZED);
        }

    }

}
