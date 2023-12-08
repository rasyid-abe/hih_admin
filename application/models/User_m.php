<?php

class user_m extends CI_Model
{
    public function list_user()
    {
        $res = [];
        $data = $this->db->get('term_condition');
        if ($data) {
            $res = [
                'status' => true,
                'data' => $data->result_array()
            ];
        } else {
            $res = [
                'status' => false,
                'message' => 'Invalid request!'
            ];
        }

        return $res;
    }
}
