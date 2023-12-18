<link href="<?= base_url() ?>assets/css/jquery.dataTables.min.css"  rel="stylesheet">
<link href="<?= base_url() ?>assets/css/responsive.dataTables.min.css"  rel="stylesheet">
<div class="card">
    <div class="card-body pt-4">
        <a href="<?= base_url() ?>user/add_user" type="button" class="btn btn-success mb-3"><i class="bi bi-person-plus-fill"></i> Add</a>
        <a href="<?= base_url() ?>user/export" type="button" class="btn btn-primary mb-3"><i class="bi bi-printer-fill"></i> Excel</a>
        <table id="user_tbl" class=" table datatable display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">PICT</th>
                    <th class="text-center">NIK</th>
                    <th class="text-center">FULLNAME</th>
                    <th class="text-center">EMAIL</th>
                    <th class="text-center">GENDER</th>
                    <th class="text-center">PHONE</th>
                    <th class="text-center">ROLE</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $k => $v): ?>
                    <tr>
                        <td class="text-center"><?php echo $k + 1 ?></td>
                        <td class="text-center"> <img src="<?php echo base_url('assets/images/'.$v['foto']) ?>" height="25" alt=""></td>
                        <td><?php echo $v['nik'] ?></td>
                        <td><?php echo $v['fullname'] ?></td>
                        <td><?php echo $v['email'] ?></td>
                        <td class="text-center"><?php echo $v['gender'] == 1 ? 'Male' : 'Female' ?></td>
                        <td><?php echo $v['phone'] == 0 ? '' : $v['phone'] ?></td>
                        <td class="text-center"><?php echo $v['name'] ?></td>
                        <td class="text-center">
                            <?php if ($v['is_active'] == 0): ?>
                                <h5><span class="badge bg-secondary">Non-Active</span></h5>
                            <?php else: ?>
                                <h5><span class="badge bg-success">Active</span></h5>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?= base_url('user/edit_user/'.$v['nik']) ?>" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <?php if ($v['is_active'] == 0): ?>
                                <a href="<?= base_url('user/is_active/1/'.$v['nik']) ?>" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Activate"><i class="bi bi-toggle-off"></i></a>
                            <?php else: ?>
                                <a href="<?= base_url('user/is_active/0/'.$v['nik']) ?>" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="bi bi-toggle-on"></i></a>
                            <?php endif; ?>
                            <a href="<?= base_url('user/reset_password/'.$v['nik']) ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Reset Password"><i class="bi bi-key-fill"></i></a>
                            <a href="<?= base_url('user/delete_user/'.$v['nik']) ?>" onclick="return confirm('Are you sure delete data ?')" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="bi bi-trash-fill"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>

<script src="<?= base_url() ?>assets/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?= base_url() ?>assets/js/dataTables.responsive.min.js" charset="utf-8"></script>

<script type="text/javascript">
new DataTable('#user_tbl', {
    responsive: true,
    pagingType: 'simple'
});
</script>
