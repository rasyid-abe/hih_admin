<div class="row">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-body">

                <form id="demo-form2" class="form-horizontal form-label-left mt-4" method="post" action="<?= base_url()?>role/edit_role">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="row mb-3">
                        <label for="role" class="col-sm-3 col-form-label">Role <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" id="role" name="role" class="form-control" value="<?= $row['name'] ?>">
                            <small class="text-danger"><?= form_error('role') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="description" class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="description" name="description" style="height: 100px"><?= $row['description'] ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <a href="<?= base_url()?>role" type="button" class="btn btn-primary">Back</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
