<?php

class Documents_m extends CI_Model
{
    public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}

    public function get_home($nik)
    {
        $res = [];
        $data = [];

        $query = "
        SELECT
            group_document.id,
            group_document.name,
            group_document.icon
        FROM
            user
        LEFT JOIN role_access ON role_access.id_role = user.role_id
        LEFT JOIN group_document ON role_access.id_group = group_document.id
        JOIN document_text ON document_text.group_id = group_document.id
        WHERE
            nik = ".$nik." AND group_document.is_active = 1 AND document_text.is_active = 1
        UNION
        SELECT
            group_document.id,
            group_document.name,
            group_document.icon
        FROM
            user
        LEFT JOIN role_access ON role_access.id_role = user.role_id
        LEFT JOIN group_document ON role_access.id_group = group_document.id
        JOIN document_pdf ON document_pdf.group_id = group_document.id
        WHERE
            nik = ".$nik ." AND group_document.is_active = 1 AND document_pdf.is_active = 1 "
        ;
        
        $groups = $this->db->query($query)->result_array();
        $chunk = count($groups) > 3 ? array_chunk($groups, 3) : [$groups];

        $data['group']   = $chunk;
        $data['slide']   = $this->db->get_where('slide_banner', ['is_active' => 1])->result_array();
        $data['profile'] = $this->db->get_where('user', ['nik' => $nik])->row_array();

        if ($data['profile']) {
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

    public function get_list_document($get)
    {
        $res = [];

        $query = "
        SELECT 
            document_text.id,
            document_text.document_name as title,
            document_text.description as descr,
            'text' as type
        FROM document_text
        WHERE document_text.group_id = ".$get['group_id']." AND document_text.is_active = 1
        UNION 
        SELECT
            document_pdf.id,
            document_pdf.file_name as title,
            document_pdf.description as descr,
            'pdf' as type
        FROM document_pdf
        WHERE document_pdf.group_id = ".$get['group_id']." AND document_pdf.is_active = 1;
        ";

        $data = $this->db->query($query)->result_array();
        if ($data) {
            $val_log = $this->db->get_where('group_document', ['id' => $get['group_id']])->row('name');
            $logs = [
                'nik' => $get['nik'],
                'log' => 'View Document Group "'. $val_log .'"'
            ];
            activity($logs);

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

    public function get_search($get)
    {
        $res = [];

        $query = "
        SELECT
			group_document.id,
            document_text.id,
            document_text.document_name as title,
            document_text.description as descr,
             'text' as type
        FROM
            user
        LEFT JOIN role_access ON role_access.id_role = user.role_id
        LEFT JOIN group_document ON role_access.id_group = group_document.id
        JOIN document_text ON document_text.group_id = group_document.id
        WHERE
            nik = ".$get['nik']." AND document_text.is_active = 1 AND document_text.document_name like '%".$get['key']."%'
        UNION
        SELECT
        	group_document.id,
            document_pdf.id,
            document_pdf.file_name as title,
            document_pdf.description as descr,
            'pdf' as type
        FROM
            user
        LEFT JOIN role_access ON role_access.id_role = user.role_id
        LEFT JOIN group_document ON role_access.id_group = group_document.id
        JOIN document_pdf ON document_pdf.group_id = group_document.id
        WHERE
            nik = ".$get['nik']." AND document_pdf = 1 AND document_pdf.file_name like '%".$get['key']."%'
        ";

        $data = $this->db->query($query)->result_array();
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

    public function get_document_text($get)
    {
        $res = [];

        $data = $this->db->get_where('document_text', ['id' => $get['text_id']])->row_array();
        if ($data) {
            $logs = [
                'nik' => $get['nik'],
                'log' => 'View Document Text "'. $data['document_name'] .'"'
            ];
            activity($logs);

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
    
    public function get_document_pdf($get)
    {
        $res = [];

        $data = $this->db->get_where('document_pdf', ['id' => $get['pdf_id']])->row_array();
        if ($data) {
            $logs = [
                'nik' => $get['nik'],
                'log' => 'View Document Pdf "'. $data['file_name'] .'"'
            ];
            activity($logs);

            $res = [
                'status' => true,
                'data' => [
                    'id' => $data['id'],
                    'file_name' => str_replace(' ', '_', $data['file_name']).'.pdf',
                    'doc_name' => $data['file_name']
                ]
            ];
        } else {
            $res = [
                'status' => false,
                'message' => 'Invalid request!'
            ];
        }

        return $res;
    }

    public function post_fraud($post, $file)
    {
        $res = [];

        $images = [];
        for($i=0; $i < (int)$post['count']; $i++){
            $name_file = 'file_' . $i;
            $img = $file[$name_file]['name'];
            $ext = explode('.', $img);
            if ($img) {
                $config['upload_path'] = './assets/fraudimg/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = '2048';
                $config['file_name'] = $post['nik'].'_'.date("YmdHis").'_'.$i.'.'.end($ext);
    
                $images[] = $config['file_name'];
                
                $this->upload->initialize($config);
    
                if (!$this->upload->do_upload($name_file)) {
                    $res = [
                        'status' => false,
                        'message' => $this->upload->display_errors()
                    ];

                    return $res;
                }
            }

        }

        $arr_insert = [
            'nik' => $post['nik'],
            'branch_name' => $post['branch'],
            'contract_no' => $post['contract'],
            'installments' => $post['installments'],
            'customer_name' => $post['customer'],
            'marketing_name' => $post['marketing'],
            'report_type' => $post['type'],
            'description' => $post['desc'],
            'images' => json_encode($images),
        ];

        if ($this->db->insert('fraud_report', $arr_insert)) {
            $logs = [
                'nik' => $post['nik'],
                'log' => 'Add Fraud No '. $post['contract']
            ];
            activity($logs);

            $res = [
                'status' => true,
                'data' => $this->db->get_where('fraud_report', ['nik' => $post['nik']])->result_array(),
                'message' => 'Success add fraud!'
            ];
        } else {
            $res = [
                'status' => false,
                'message' => 'Invalid request!'
            ];
        }

        return $res;
    }

    public function get_fraud_list($nik)
    {
        $res = [];

        $data = $this->db->get_where('fraud_report', ['nik' => $nik])->result_array();
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

    public function get_fraud_detail($get)
    {
        $res = [];

        $data = $this->db->get_where('fraud_report', ['id' => $get['id']])->row_array();
        if ($data) {
            $logs = [
                'nik' => $get['nik'],
                'log' => 'View Fraud No '. $data['contract_no']
            ];
            activity($logs);

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

    public function get_termcondition($get)
    {
        $res = [];

        $this->db->limit(1);
        $this->db->order_by('date_updated', 'desc');
        $data = $this->db->get('term_condition')->row_array();
        if ($data) {
            $logs = [
                'nik' => $get['nik'],
                'log' => 'View Term & Condition'
            ];
            activity($logs);

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



}
