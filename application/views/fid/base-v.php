<link href="<?= base_url() ?>assets/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/css/responsive.dataTables.min.css" rel="stylesheet">
<div class="card">
    <div class="card-body pt-4">
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalfid">
            <i class="bi bi-upload"></i> Excel
        </button>
        <!-- <a href="<?= base_url() ?>fid/export" type="button" class="btn btn-primary mb-3"><i class="bi bi-printer"></i>
            Excel</a> -->
        <table id="group_tbl" class=" table datatable display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">BRANCH</th>
                    <th class="text-center">CONTRACT NO</th>
                    <th class="text-center">CUSTOMER NAME</th>
                    <th class="text-center">SALES</th>
                    <th class="text-center">CHM NAME</th>
                    <th class="text-center">DO DATE</th>
                    <th class="text-center">PREDICTION</th>
                    <th class="text-center">REASON</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $k => $v): ?>
                    <tr>
                        <td><?= $k+1 ?></td>
                        <td><?= $v['branch_name'] ?></td>
                        <td><?= $v['contract_no'] ?></td>
                        <td><?= $v['customer_name'] ?></td>
                        <td><?= $v['name_sales'] ?></td>
                        <td><?= $v['name_chm'] ?></td>
                        <td><?= $v['do_date'] ?></td>
                        <td><?= $v['prediction'] ?></td>
                        <td><?= $v['reason'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalfid" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titlemdl">Upload Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="" action="<?= base_url() ?>fid/import" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="file" name="excelfile" class="" id="excelfile" value="" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
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