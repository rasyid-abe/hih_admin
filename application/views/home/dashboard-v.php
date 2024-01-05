<div class="row">
    
    <div class="col-sm-3">
    <div class="card info-card sales-card">
    <!-- 
    <div class="filter">
        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <li class="dropdown-header text-start">
            <h6>Filter</h6>
        </li>
    
        <li><a class="dropdown-item" href="#">Total</a></li>
        <li><a class="dropdown-item" href="#">This Month</a></li>
        <li><a class="dropdown-item" href="#">This Year</a></li>
        </ul>
    </div> -->
    
    <div class="card-body">
        <h5 class="card-title">Document PDF <span>| Total</span></h5>
    
        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <h2><i class="bi bi-file-earmark-pdf-fill"></i></h2>
        </div>
        <div class="ps-3">
            <h3><?= $pdf ?></h3>    
        </div>
        </div>
    </div>
    
    </div>
    </div>
    
    <div class="col-sm-3">
    <div class="card info-card sales-card">
    
    <div class="card-body">
        <h5 class="card-title">Document Text <span>| Total</span></h5>
    
        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <h2><i class="bi bi-file-earmark-medical-fill"></i></h2>
        </div>
        <div class="ps-3">
            <h3><?= $text ?></h3>    
        </div>
        </div>
    </div>
    
    </div>
    </div>
    
    <div class="col-sm-3">
    <div class="card info-card sales-card">
    
    <div class="card-body">
        <h5 class="card-title">Fraud Report <span>| Total</span></h5>
    
        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <h2><i class="bi bi-stack"></i></h2>
        </div>
        <div class="ps-3">
            <h3><?= $fraud ?></h3>    
        </div>
        </div>
    </div>
    
    </div>
    </div>
    
    <div class="col-sm-3">
    <div class="card info-card sales-card">
    
    <div class="card-body">
        <h5 class="card-title">User <span>| Total</span></h5>
    
        <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <h2><i class="bi bi-person-lines-fill"></i></h2>
        </div>
        <div class="ps-3">
            <h3><?= $user ?></h3>    
        </div>
        </div>
    </div>
    
    </div>
    </div>

</div>

 <!-- Recent Sales -->
 <div class="col-12">
    <div class="card recent-sales overflow-auto">

      <div class="card-body">
        <h5 class="card-title">Recent Activities <a class="btn btn-sm btn-info" href="<?=base_url('activity')?>">View All</a> </h5>

        <table class="table table-borderless datatable">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">NIK</th>
              <th scope="col">User</th>
              <th scope="col">DateTime</th>
              <th scope="col">Activities</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($activities as $k => $v): ?>
            <tr>
              <td><?= $k +1 ?></td>
              <td><?= $v['nik']?></td>
              <td><?= $v['fullname']?></td>
              <td><?= $v['datetime']?></td>
              <td><?= $v['log']?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>

      </div>

    </div>
  </div><!-- End Recent Sales -->