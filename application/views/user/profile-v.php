<div class="row">
    <div class="col-sm-12">
        <div class="card pt-4">
            <div class="card-body">

                <form class="" action="<?= base_url()?>user/profile" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="row mb-3">
                                <label for="fullname" class="col-sm-3 col-form-label">Fullname <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" id="fullname" name="fullname" class="form-control" value="<?= $row['fullname'] ?>">
                                    <small class="text-danger"><?= form_error('fullname') ?></small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nik" class="col-sm-3 col-form-label">NIK </label>
                                <div class="col-sm-9">
                                    <input type="number" id="nik" name="nik" class="form-control"  value="<?= $row['nik'] ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-3 col-form-label">Email </label>
                                <div class="col-sm-9">
                                    <input id="email" class="form-control" type="text" name="email" value="<?= $row['email'] ?>">
                                    <small class="text-danger"><?= form_error('email') ?></small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-3 col-form-label">Phone </label>
                                <div class="col-sm-9">
                                    <input id="phone" class="form-control" type="number" name="phone" value="<?= $row['phone'] == 0 ? '' : $row['phone'] ?>">
                                    <small class="text-danger"><?= form_error('phone') ?></small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-3 col-form-label">Gender </label>
                                <div class="col-sm-9">
                                    <input type="radio" class="flat" name="gender" id="genderM" value=1 checked="" required /> : Male <br>
                                    <input type="radio" class="flat" name="gender" id="genderF" value=2 /> : Female
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-4">
                            <div class="custom-file">
                                <label for="foto">Picture</label><br>
                                <img src="<?= base_url('assets/profiles/'.$row['foto']) ?>" alt="" class="img-thumbnail" width="175">
                                <input type="file" name="foto" class="" id="foto" value=""  accept="image/png, image/gif, image/jpeg">
                                <div class="ln_solid"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-1">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
