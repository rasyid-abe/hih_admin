<link href="<?= base_url() ?>assets/css/jquery.dataTables.min.css"  rel="stylesheet">
<link href="<?= base_url() ?>assets/css/responsive.dataTables.min.css"  rel="stylesheet">
<div class="card">
    <div class="card-body pt-4">
    <a href="<?= base_url() ?>fraud/export" type="button" class="btn btn-primary mb-3"><i class="bi bi-printer-fill"></i> Excel</a>
        <table id="group_tbl" class=" table datatable display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">DATE</th>
                    <th class="text-center">NIK</th>
                    <th class="text-center">USER</th>
                    <th class="text-center">BRANCH</th>
                    <th class="text-center">CONTRACT</th>
                    <th class="text-center">REPORT TYPE</th>
                    <th class="text-center">MARKETING NAME</th>
                    <th class="text-center">CUSTOMER NAME</th>
                    <th class="text-center">ACTION</th>
                </tr>
            </thead>
                <?php foreach ($rows as $k => $v): ?>
                    <tr>
                        <td class="text-center"><?= $k + 1 ?></td>
                        <td class="text-center"><?= $v['date_submited'] ?></td>
                        <td class="text-center"><?= $v['nik'] ?></td>
                        <td class="text-center"><?= $v['fullname'] ?></td>
                        <td class="text-center"><?= $v['branch_name'] ?></td>
                        <td class="text-center"><?= $v['contract_no'] ?></td>
                        <td class="text-center"><?= $v['report_type'] ?></td>
                        <td class="text-center"><?= $v['marketing_name'] ?></td>
                        <td class="text-center"><?= $v['customer_name'] ?></td>
                        <td class="text-center"> <div class="btn btn-sm btn-primary" onclick="detail(<?= $v['id'] ?>)">Desc</div>
                        <div class="btn btn-sm btn-danger" onclick="show_image(<?= $v['id'] ?>)">Fotos</div> </td>
                    </tr>
                <?php endforeach; ?>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal-fotos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titlemdl"></h5>
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
    url: '<?= base_url('fraud/descrip') ?>',
    method: 'post',
    data: {id},
    success: function(res) {
      $('#titlemdl').html('Description')
      $('#content_mdl').html(`<p>${res}</p>`)
      $('#modal-fotos').modal('show');
    }
  })
}

function show_image(id) {
  $.ajax({
    url: '<?= base_url('fraud/get_image') ?>',
    method: 'post',
    data: {id},
    success: function(res) {
      let images = JSON.parse(res)
      let view = '';
      for(let i=0; i < images.length; i++){
        if (i < 1){
          view += `
            <div class="carousel-item active" data-bs-interval="10000">
              <img src="<?= base_url() ?>assets/fraudimg/${images[i]}" width="100%" alt="" style="z-index: -1;">
            </div>
          `
        } else {
          view += `
            <div class="carousel-item" data-bs-interval="10000">
              <img src="<?= base_url() ?>assets/fraudimg/${images[i]}" width="100%" alt="" style="z-index: -1;">
            </div>
          `
        }
      }

      let slideimage = `
        <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
          <div class="carousel-inner">
          ${view}
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      `
      $('#titlemdl').html('Foto Fraud Report')
      $('#content_mdl').html(slideimage)
      $('#modal-fotos').modal('show');
    }
  })


}
</script>
