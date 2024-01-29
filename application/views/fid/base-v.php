<link href="<?= base_url() ?>assets/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/css/responsive.dataTables.min.css" rel="stylesheet">
<div class="card">
    <div class="card-body pt-4">
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalfid">
            <i class="bi bi-upload"></i> Excel
        </button>
        <a href="<?= base_url() ?>fid/export" type="button" class="btn btn-primary mb-3"><i class="bi bi-printer"></i>
            Excel</a>
        <table id="group_tbl" class=" table datatable display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">BRANCH</th>
                    <th class="text-center">CONTRACT NO</th>
                    <th class="text-center">CUSTOMER NAME</th>
                    <th class="text-center">SALES</th>
                    <th class="text-center">CHM NAME</th>
                    <th class="text-center">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $k => $v): ?>
                    <tr>
                        <td>
                            <?= $k + 1 ?>
                        </td>
                        <td>
                            <?= $v['branch_name'] ?>
                        </td>
                        <td>
                            <?= $v['contract_no'] ?>
                        </td>
                        <td>
                            <?= $v['customer_name'] ?>
                        </td>
                        <td>
                            <?= $v['name_sales'] ?>
                        </td>
                        <td>
                            <?= $v['name_chm'] ?>
                        </td>
                        <td>
                            <div class="btn btn-sm btn-primary" onclick="detail(<?= $v['id'] ?>)"><i
                                    class="bi bi-card-heading"></i></div>
                            <a href="<?= base_url('fid/delete_fid/' . $v['id']) ?>"
                                onclick="return confirm('Are you sure delete data ?')" class="btn btn-sm btn-danger"
                                data-toggle="tooltip" data-placement="top" title="Delete"><i
                                    class="bi bi-trash-fill"></i></a>
                        </td>
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
                    <input type="file" name="excelfile" class="" id="excelfile" value=""
                        accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-fotos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_detail">Detail Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="content_mdl"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
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

    function detail(id) {
        $.ajax({
            url: '<?= base_url('fid/detail') ?>',
            method: 'post',
            data: { id },
            success: function (res) {
                let row = JSON.parse(res)
                let view = `
                    <table class="table">
                        <tr>
                            <th width="35%">Customer Name</th>
                            <td>${row.customer_name}</td>
                        </tr>
                        <tr>
                            <th width="35%">Address</th>
                            <td>${row.address}</td>
                        </tr>
                        <tr>
                            <th width="35%">Portfolio</th>
                            <td>${row.portfolio}</td>
                        </tr>
                        <tr>
                            <th width="35%">Principal Ammount</th>
                            <td>${new Intl.NumberFormat('id-ID').format(row.principal_ammount)}</td>
                        </tr>
                        <tr>
                            <th width="35%">Status</th>
                            <td>${row.status_fid}</td>
                        </tr>
                        <tr>
                            <th width="35%">NIK Sales</th>
                            <td>${row.nik_sales}</td>
                        </tr>
                        <tr>
                            <th width="35%">Sales</th>
                            <td>${row.name_sales}</td>
                        </tr>
                        <tr>
                            <th width="35%">Do Date Sales</th>
                            <td>${row.do_date_sales != null ? row.do_date_sales : '-'}</td>
                        </tr>
                        <tr>
                            <th width="35%">Prediction Sales</th>
                            <td>${row.prediction_sales != null ? row.prediction_sales : '-'}</td>
                        </tr>
                        <tr>
                            <th width="35%">Reason Sales</th>
                            <td>${row.reason_sales != null ? row.reason_sales : '-'}</td>
                        </tr>
                        <tr>
                            <th width="35%">NIK CHM</th>
                            <td>${row.nik_chm}</td>
                        </tr>
                        <tr>
                            <th width="35%">CHM</th>
                            <td>${row.name_chm}</td>
                        </tr>
                        <tr>
                            <th width="35%">Do Date CHM</th>
                            <td>${row.do_date_chm != null ? row.do_date_chm : '-'}</td>
                        </tr>
                        <tr>
                            <th width="35%">Prediction CHM</th>
                            <td>${row.prediction_chm != null ? row.prediction_chm : '-'}</td>
                        </tr>
                        <tr>
                            <th width="35%">Reason CHM</th>
                            <td>${row.reason_chm != null ? row.reason_chm : '-'}</td>
                        </tr>
                    </table>
                `;
                $('#title_detail').html('<b>[' + row.branch_name +' - '+ row.contract_no + ']</b>')
                $('#content_mdl').html(view)
                $('#modal-fotos').modal('show');
            }
        })
    }
</script>