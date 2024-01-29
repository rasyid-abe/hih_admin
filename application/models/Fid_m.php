<?php

class Fid_m extends CI_Model
{
    public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}

    public function get_fid_data($get)
    {
        $res = [];
        $data = [];

        $query = "
        SELECT fd.id, b.branch_name, fd.contract_no, fd.customer_name, fd.principal_ammount, fd.status_fid, 'sales' as type
        FROM fid_data fd
        JOIN branch b ON fd.branch = b.branch_code
        WHERE nik_sales =  ".$get['nik']."
        UNION
        SELECT fd.id, b.branch_name, fd.contract_no, fd.customer_name, fd.principal_ammount, fd.status_fid, 'chm' as type
        FROM fid_data fd
        JOIN branch b ON fd.branch = b.branch_code
        WHERE nik_chm =  ".$get['nik']
        ;
        
        $result = $this->db->query($query)->result_array();

        $data['result'] = $result;

        if ($result) {
            $res = [
                'status' => true,
                'data' => $data
            ];
        } else {
            $res = [
                'status' => false,
                'message' => 'Invalid request!'
            ];
        }

        return $res;
    }
    
    public function get_fid_detail($get)
    {
        $this->db->select("branch.branch_name, fid_data.*");
		$this->db->join('branch', 'branch.branch_code = fid_data.branch');
		$data = $this->db->get_where('fid_data', ['fid_data.id'=> $get['id']])->row_array();
        
        if ($data) {
            $res = [
                'status' => true,
                'data' => $data
            ];
        } else {
            $res = [
                'status' => false,
                'message' => 'Invalid request!'
            ];
        }

        return $res;
    }
    
    public function post_save_fid($post)
    {
        if ($post['type'] == 'chm') {
            $data = [
                'do_date_chm' => $post['dodate'],
                'prediction_chm' => $post['prediction'],
                'reason_chm' => $post['reason'],
            ];
        }
            
        if ($post['type'] == 'sales') {
            $data = [
                'do_date_sales' => $post['dodate'],
                'prediction_sales' => $post['prediction'],
                'reason_sales' => $post['reason'],
            ];
        }
        
        $this->db->where('id', $post['id']);
        $store = $this->db->update('fid_data', $data);
        
        if ($store) {

            if ((int)$post['id_notif'] > 0) {
                $read = [
                    'nik' => $post['nik'],
                    'id_notification' => (int)$post['id_notif']
                ];

                read_notif($read);
            } 

            $res = [
                'status' => true,
                'message' => 'Success send Feedback!'
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
