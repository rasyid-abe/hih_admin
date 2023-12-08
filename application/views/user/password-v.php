<div class="row">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-body">

                <form id="demo-form2" class="form-horizontal form-label-left mt-4" method="post" action="<?= base_url()?>user/password">
                    <div class="row mb-3">
                        <label for="fullname" class="col-sm-3 col-form-label">Current Password <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="password" id="current_password" name="current_password" class="form-control" value="<?= set_value('current_password') ?>">
                            <small class="text-danger"><?= form_error('current_password') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="nik" class="col-sm-3 col-form-label">New Password <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="password" id="new_password" name="new_password" class="form-control" value="<?= set_value('new_password') ?>">
                            <small class="text-danger"><?= form_error('new_password') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="email" class="col-sm-3 col-form-label">Repeat Password <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="password" id="match_password" name="match_password" class="form-control" value="<?= set_value('match_password') ?>">
                            <small class="text-danger"><?= form_error('match_password') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
