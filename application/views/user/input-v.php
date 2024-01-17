<div class="row">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-body">

                <form id="demo-form2" class="form-horizontal form-label-left mt-4" method="post" action="<?= base_url()?>user/add_user">
                    <div class="row mb-3">
                        <label for="fullname" class="col-sm-3 col-form-label">Fullname <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" id="fullname" name="fullname" class="form-control" value="<?= set_value('fullname') ?>">
                            <small class="text-danger"><?= form_error('fullname') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="nik" class="col-sm-3 col-form-label">NIK <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" id="nik" name="nik" class="form-control"  value="<?= set_value('nik') ?>">
                            <small class="text-danger"><?= form_error('nik') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input id="email" class="form-control" type="text" name="email" value="<?= set_value('email') ?>">
                            <small class="text-danger"><?= form_error('email') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                        <div class="col-sm-9">
                            <input id="phone" class="form-control" type="number" name="phone" value="<?= set_value('phone') ?>">
                            <small class="text-danger"><?= form_error('phone') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="" class="col-sm-3 col-form-label">Gender</label>
                        <div class="col-sm-9">
                            <input type="radio" class="flat" name="gender" id="genderM" value=1 checked="" required /> : Male <br>
                            <input type="radio" class="flat" name="gender" id="genderF" value=2 /> : Female
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="role" class="col-sm-3 col-form-label">User Role <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="role" id="role" required>
                                <option selected="">Select Role</option>
                                <?php foreach ($role as $k => $v): ?>
                                    <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger"><?= form_error('role') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="role" class="col-sm-3 col-form-label">User Branch <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="branch" id="branch" required>
                                <option selected="">Select Branch</option>
                                <?php foreach ($branch as $k => $v): ?>
                                    <option value="<?= $v['id'] ?>"><?= $v['branch_code'] ?> - <?= $v['branch_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger"><?= form_error('branch') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <a href="<?= base_url()?>user" type="button" class="btn btn-primary">Back</a>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
