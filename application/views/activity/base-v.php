<link href="<?= base_url() ?>assets/css/jquery.dataTables.min.css"  rel="stylesheet">
<link href="<?= base_url() ?>assets/css/responsive.dataTables.min.css"  rel="stylesheet">
<div class="card">
    <div class="card-body pt-4">
        <table id="group_tbl" class=" table datatable display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">USER</th>
                    <th class="text-center">DATITIME</th>
                    <th class="text-center">ACTIVITIES</th>
                </tr>
            </thead>
                <?php foreach ($rows as $k => $v): ?>
                    <tr>
                        <td class="text-center"><?= $k + 1 ?></td>
                        <td class="text-center"><?= $v['id_user'] ?></td>
                        <td class="text-center"><?= $v['datetime'] ?></td>
                        <td class="text-center"><?= $v['activities'] ?></td>
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
new DataTable('#group_tbl', {
    responsive: true,
    pagingType: 'simple'
});
</script>
