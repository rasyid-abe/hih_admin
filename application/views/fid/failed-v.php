<link href="<?= base_url() ?>assets/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/css/responsive.dataTables.min.css" rel="stylesheet">
<div class="card">
    <div class="card-body pt-4">
        <table id="group_tbl" class=" table datatable display nowrap">
            <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th>CUSTOMER NAME</th>
                    <th class="text-center">CONTRACT NO</th>
                    <th class="text-center">ERROR MESSAGE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $k => $v): ?>
                    <tr>
                        <td class="text-center">
                            <?= $k + 1 ?>
                        </td>
                        <td>
                            <?= $v['customer_name'] ?>
                        </td>
                        <td class="text-center">
                            <?= $v['contract_no'] ?>
                        </td>
                        <td class="text-center">
                            <?= $v['error'] ?>
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
    const table = new DataTable('#group_tbl', {
        responsive: true,
        pagingType: 'simple'
    });
</script>