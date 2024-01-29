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
        
        $this->db->select('user_role.name rolename, user.*');
        $this->db->join('user_role', 'user.role_id = user_role.id');
        $myprofile = $this->db->get_where('user', ['nik' => $nik]);


        $data['showfid'] = $myprofile->row('rolename') == 'Marketing' ? true : false;
        $data['group']   = $chunk;
        $data['slide']   = $this->db->get_where('slide_banner', ['is_active' => 1])->result_array();
        $data['profile'] = $myprofile->row_array();

        $data['notif'] = $this->query_notif($nik)->num_rows();

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

    function query_notif($nik)
    {
        $query_ids = "SELECT group_concat(concat(notif_read.id_notification) separator ',') ids FROM notif_read WHERE notif_read.nik = ". $nik;
		$ids_read = $this->db->query($query_ids)->row('ids');

		
		$filter_read = '';
		if ($ids_read != '') {
			$filter_read = 'AND notification.id NOT IN ('.$ids_read.')';
		}
		

		$this->db->where('user.nik', $nik);
        $this->db->join('user', 'user.role_id = user_role.id');
        $chk = $this->db->get('user_role')->row('name');
        
        $is_marketing = $chk == 'Marketing' ? true : false;

		$query_fid = "";
		if($is_marketing) {
			$query_fid = "
			UNION
			    SELECT notification.*, 'sales' as fid_type
				FROM fid_data fd
				JOIN branch b ON fd.branch = b.branch_code
				JOIN notification ON notification.id_content = fd.id
				WHERE 
					nik_sales = $nik
					$filter_read
			UNION
				SELECT notification.*, 'chm' as fid_type
				FROM fid_data fd
				JOIN branch b ON fd.branch = b.branch_code
				JOIN notification ON notification.id_content = fd.id
				WHERE 
					nik_chm = $nik
					$filter_read
			";
		}
		

        $query = "
			select notification.*, '' as fid_type
            from user
            left join role_access on role_access.id_role = user.role_id
            left join group_document on group_document.id = role_access.id_group
            join document_pdf on document_pdf.group_id = group_document.id
            join notification on document_pdf.id = notification.id_content
            WHERE 
				user.nik = $nik 
				AND group_document.is_active = 1 
				AND notification.status = 1
				AND notification.type = 'pdf'
				$filter_read
		UNION
			select notification.*, '' as fid_type
			from user
			left join role_access on role_access.id_role = user.role_id
			left join group_document on group_document.id = role_access.id_group
			join document_text on document_text.group_id = group_document.id
			join notification on document_text.id = notification.id_content
			WHERE 
				user.nik = $nik 
				AND group_document.is_active = 1 
				AND notification.status = 1
				AND notification.type = 'text'
				$filter_read
        UNION
            select notification.*, '' as fid_type from notification
			where 
				notification.type = 'term' 
				$filter_read		
			" .$query_fid;

        return $this->db->query($query);
    }

    
    public function get_notification($get)
    {
        $res = [];
        $query = $this->query_notif($get['nik'])->result_array();
        if ($query) {
            $res = [
                'status' => true,
                'data' => $query
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
            nik = ".$get['nik']." 
            AND document_text.is_active = 1 
            AND document_text.document_name like '%".$get['key']."%'
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
            nik = ".$get['nik']." 
            AND document_pdf.is_active = 1 
            AND document_pdf.file_name like '%".$get['key']."%'
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

    public function get_slides()
    {
        $res = [];

        $data = $this->db->get_where('slide_banner', ['is_active' => 1])->result_array();
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

            if ((int)$get['id_notif'] > 0) {
                $read = [
                    'nik' => $get['nik'],
                    'id_notification' => (int)$get['id_notif']
                ];

                read_notif($read);
            } 

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

            if ((int)$get['id_notif'] > 0) {
                $read = [
                    'nik' => $get['nik'],
                    'id_notification' => (int)$get['id_notif']
                ];

                read_notif($read);
            } 

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

            if ((int)$get['id_notif'] > 0) {
                $read = [
                    'nik' => $get['nik'],
                    'id_notification' => (int)$get['id_notif']
                ];

                read_notif($read);
            } 

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
