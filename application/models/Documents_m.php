<?php

class Documents_m extends CI_Model
{
    public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}

    public function get_documents_group($nik)
    {
        $res = [];
        $data = $this->db->get_where('user', ['nik' => $nik]);
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

    public function post_profile($post, $file)
    {
        $res = [];
        $img = $file['foto']['name'];
        $old = $this->db->get_where('user', ['nik' => $post['nik']])->row('foto');

        if ($img) {
            $config['upload_path'] = './assets/images/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size'] = '2048';

            $this->upload->initialize($config);

            if ($this->upload->do_upload('foto')) {
                if ($old != 'default.png') {
                    unlink(FCPATH.'assets/images/'.$old);
                }
                $new_image = $this->upload->data('file_name');
                $this->db->set('foto', $new_image);
            } else {
                echo '<pre>';
                print_r($this->upload->display_errors());
                exit();
            }
        }

        $this->db->set('fullname', htmlspecialchars($post['fullname']));
        $this->db->set('gender', htmlspecialchars($post['gender']));
        $this->db->set('email', htmlspecialchars($post['email']));
        $this->db->set('phone', htmlspecialchars($post['phone']));
        $this->db->set('updated_by',  $this->session->userdata('id'));
        $this->db->where('nik', $post['nik']);

        if ($this->db->update('user')) {
            $this->db->select('nik, fullname, gender, email, phone');
            $data = $this->db->get_where('user', ['nik' => $post['nik']]);
            $res = [
                'status' => true,
                'message' => 'Success update profile',
                'data' => $data->row_array()
            ];
        } else {
            $res = [
                'status' => false,
                'message' => 'Invalid request!'
            ];
        }

        return $res;
    }

    public function post_change_password($post)
    {
        $res = [];
        $res['status'] = false;

        $new_password = $post['new_password'];
        $match_password = $post['match_password'];
        $current = $post['current_password'];
        $old =  $this->db->get_where('user', ['nik' => $post['nik']])->row('password');

        if ($old) {
            if (!password_verify($current, $old)) {
                $res['message'] = 'Current password is wrong!';
            } else {
                if ($new_password == $current) {
                    $res['message'] = 'New password is same as current password!';
                } else {
                    if ($new_password != $match_password) {
                        $res['message'] = "Password is not match!";
                    } else {
                        $hash_pass = password_hash($new_password, PASSWORD_DEFAULT);

                        $this->db->set('password', $hash_pass);
                        $this->db->where('nik', $post['nik']);
                        if ($this->db->update('user')) {
                            $res['status'] = true;
                            $res['message'] = 'Success change password!';
                        } else {
                            $res['message'] = 'Change password is failed!';
                        }
                    }
                }
            }
        } else {
            $res['message'] = 'Something Wrong!';
        }


        return $res;
    }
}
