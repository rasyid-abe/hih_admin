<link href="<?= base_url() ?>assets/css/jquery.dataTables.min.css"  rel="stylesheet">
<link href="<?= base_url() ?>assets/css/responsive.dataTables.min.css"  rel="stylesheet">
<div class="card">
    <div class="card-body pt-4">
        <a href="<?= base_url() ?>term_condition/add_term_condition" type="button" class="btn btn-success mb-3"><i class="bi bi-plus-square"></i> Add</a>
        <table id="term_tbl" class=" table datatable display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-left">NAME</th>
                    <th class="text-center">ACTION</th>
                </tr>
            </thead>
                <?php foreach ($rows as $k => $v): ?>
                    <tr>
                        <td class="text-center"><?= $k + 1 ?></td>
                        <td class="text-left"><?= $v['name'] ?></td>
                        <td class="text-center">
                            <a href="<?= base_url('term_condition/view_term_condition/'.$v['id']) ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="View"><i class="bi bi-border-width"></i></a>
                            <a href="<?= base_url('term_condition/edit_term_condition/'.$v['id']) ?>" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <a href="<?= base_url('term_condition/delete_term_condition/'.$v['id']) ?>" onclick="return confirm('Are you sure delete data ?')" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="bi bi-trash-fill"></i></a>
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
new DataTable('#term_tbl', {
    responsive: true,
    pagingType: 'simple'
});
</script>
