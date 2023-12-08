<div class="row">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-body">

                <form id="demo-form2" class="form-horizontal form-label-left mt-4" method="post" action="<?= base_url()?>user/edit_user">
                    <div class="row mb-3">
                        <label for="fullname" class="col-sm-3 col-form-label">Fullname <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" id="fullname" name="fullname" class="form-control" value="<?= $row['fullname'] ?>">
                            <small class="text-danger"><?= form_error('fullname') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="nik" class="col-sm-3 col-form-label">NIK</label>
                        <div class="col-sm-9">
                            <input type="number" id="nik" name="nik" class="form-control"  value="<?= $row['nik'] ?>" readonly>
                            <small class="text-danger"><?= form_error('nik') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input id="email" class="form-control" type="text" name="email" value="<?= $row['email'] ?>">
                            <small class="text-danger"><?= form_error('email') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                        <div class="col-sm-9">
                            <input id="phone" class="form-control" type="number" name="phone" value="<?= $row['phone'] == 0 ? '' : $row['phone'] ?>">
                            <small class="text-danger"><?= form_error('phone') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="" class="col-sm-3 col-form-label">Gender</label>
                        <div class="col-sm-9">
                            <input type="radio" class="flat" name="gender" id="genderM" value=1 <?= $row['gender'] == 1 ? 'checked' : '' ?> required /> : Male <br>
                            <input type="radio" class="flat" name="gender" id="genderF" value=2 <?= $row['gender'] == 2 ? 'checked' : '' ?> /> : Female
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="role" class="col-sm-3 col-form-label">User Role</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="role" id="role" required>
                                <option selected="">Select Role</option>
                                <?php foreach ($role as $k => $v): ?>
                                    <option value="<?= $v['id'] ?>" <?= $row['role_id'] == $v['id'] ? 'selected' : '' ?>><?= $v['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger"><?= form_error('role') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <a href="<?= base_url()?>user" type="button" class="btn btn-primary">Back</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
