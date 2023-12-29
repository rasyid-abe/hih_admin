<link href="<?= base_url() ?>assets/css/jquery.dataTables.min.css"  rel="stylesheet">
<link href="<?= base_url() ?>assets/css/responsive.dataTables.min.css"  rel="stylesheet">
<div class="card">
    <div class="card-body pt-4">
        <a href="<?= base_url() ?>slide/add_slide" type="button" class="btn btn-success mb-3"><i class="bi bi-plus-square"></i> Add</a>
        <table id="slide_tbl" class=" table datatable display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">IMAGE</th>
                    <th class="text-center">DESC</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">ACTION</th>
                </tr>
            </thead>
                <?php foreach ($rows as $k => $v): ?>
                    <tr>
                        <td class="text-center"><?= $k + 1 ?></td>
                        <td class="text-center"> <img src="<?php echo base_url('assets/slides/'.$v['image']) ?>" height="60" alt=""></td>
                        <td class="text-center"><?= $v['description'] ?></td>
                        <td class="text-center">
                            <?php if ($v['is_active'] == 0): ?>
                                <h5><span class="badge bg-secondary">Non-Active</span></h5>
                            <?php else: ?>
                                <h5><span class="badge bg-success">Active</span></h5>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($v['is_active'] == 0): ?>
                                <a href="<?= base_url('slide/is_active/1/'.$v['id']) ?>" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Activate"><i class="bi bi-toggle-off"></i></a>
                            <?php else: ?>
                                <a href="<?= base_url('slide/is_active/0/'.$v['id']) ?>" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Deactivate"><i class="bi bi-toggle-on"></i></a>
                            <?php endif; ?>
                            <a href="<?= base_url('slide/delete_slide/'.$v['id']) ?>" onclick="return confirm('Are you sure delete data ?')" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="bi bi-trash-fill"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="<?= base_url() ?>assets/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?= base_url() ?>assets/js/dataTables.responsive.min.js" charset="utf-8"></script>

<script type="text/javascript">
new DataTable('#slide_tbl', {
    responsive: true,
    pagingType: 'simple'
});
</script>
