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

        $query = "
        SELECT
            group_document.id,
            group_document.name,
            group_document.icon
        FROM
            USER
        LEFT JOIN role_access ON role_access.id_role = user.role_id
        LEFT JOIN group_document ON role_access.id_group = group_document.id
        JOIN document_text ON document_text.group_id = group_document.id
        WHERE
            nik = ".$nik."
        UNION
        SELECT
            group_document.id,
            group_document.name,
            group_document.icon
        FROM
            USER
        LEFT JOIN role_access ON role_access.id_role = user.role_id
        LEFT JOIN group_document ON role_access.id_group = group_document.id
        JOIN document_pdf ON document_pdf.group_id = group_document.id
        WHERE
            nik = ".$nik.";
        ";

        $data = $this->db->query($query)->result_array();
        if ($data) {
            $chunk = array_chunk($data, 3);
            
            $res = [
                'status' => true,
                'data' => $chunk
            ];
        } else {
            $res = [
                'status' => false,
                'message' => 'Invalid request!'
            ];
        }

        return $res;
    }

    public function get_list_document($group_id)
    {
        $res = [];

        $query = "
        SELECT 
            document_text.id,
            document_text.document_name as title,
            document_text.description as descr,
            'text' as type
        FROM document_text
        WHERE document_text.group_id = ".$group_id." AND document_text.is_active = 1
        UNION 
        SELECT
            document_pdf.id,
            document_pdf.file_name as title,
            document_pdf.description as descr,
            'pdf' as type
        FROM document_pdf
        WHERE document_pdf.group_id = ".$group_id." AND document_pdf.is_active = 1;
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

    public function get_document_text($id)
    {
        $res = [];

        $data = $this->db->get_where('document_text', ['id' => $id])->row_array();
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

}
