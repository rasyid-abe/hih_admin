<link href="<?= base_url() ?>assets/css/jquery.dataTables.min.css"  rel="stylesheet">
<link href="<?= base_url() ?>assets/css/responsive.dataTables.min.css"  rel="stylesheet">
<div class="card">
    <div class="card-body pt-4">
        <table id="group_tbl" class=" table datatable display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">NIK</th>
                    <th class="text-center">BRANCH</th>
                    <th class="text-center">CONTRACT</th>
                    <th class="text-center">REPORT TYPE</th>
                    <th class="text-center">MARKETING NAME</th>
                    <th class="text-center">CUSTOMER NAME</th>
                </tr>
            </thead>
                <?php foreach ($rows as $k => $v): ?>
                    <tr>
                        <td class="text-center"><?= $k + 1 ?></td>
                        <td class="text-center"><?= $v['nik'] ?></td>
                        <td class="text-center"><?= $v['branch_name'] ?></td>
                        <td class="text-center"><?= $v['contract_no'] ?></td>
                        <td class="text-center"><?= $v['report_type'] ?></td>
                        <td class="text-center"><?= $v['marketing_name'] ?></td>
                        <td class="text-center"><?= $v['customer_name'] ?></td>
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
