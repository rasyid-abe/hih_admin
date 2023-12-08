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