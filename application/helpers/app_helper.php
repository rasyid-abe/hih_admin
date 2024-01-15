<?php

function is_logged_id()
{
    $ci = get_instance();
    if (!$ci->session->userdata('nik')) {
        $ci->session->set_flashdata('alert_head', 'error');
        $ci->session->set_flashdata('alert_msg', 'Login is required!');
        redirect('auth');
    }
}

function check_access($role, $group)
{
    $ci = get_instance();
    $chk = $ci->db->get_where('role_access', [
        'id_role' => $role,
        'id_group' => $group]
    );

    if ($chk->num_rows() > 0) {
        return "checked='checked'";
    }
}

function validation($data)
{
    $ci = get_instance();
    $ci->db->where('id >', 1);
    $ci->db->update('user', ['is_valid' => $data]);
}

function activity($params)
{
    $ci = get_instance();
    $ci->db->insert('log_activities', $params);
}
