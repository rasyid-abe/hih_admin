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

function notif( $params )
{
    $ci = get_instance();
    $ci->db->replace('notification', $params);
}

function read_notif( $params )
{
    $ci = get_instance();
    $ci->db->insert('notif_read', $params);
}
function get_area()
{
    $areas = ['Aceh', 'Bali', 'Banten', 'Bengkulu', 'DI Yogyakarta', 'DKI Jakarta', 'Gorontalo', 'Jambi', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Kalimantan Barat', 'Kalimantan Selatan', 'Kalimantan Tengah', 'Kalimantan Timur', 'Kalimantan Utara', 'Kep. Bangka Belitung', 'Kep. Riau', 'Lampung', 'Maluku', 'Maluku Utara', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur', 'Papua', 'Papua Barat', 'Riau', 'Sulawesi Barat', 'Sulawesi Selatan', 'Sulawesi Tenggara', 'Sulawesi Utara', 'Sumatera Barat', 'Sumatera Selatan', 'Sumatera Utara'];

    return $areas;
}
