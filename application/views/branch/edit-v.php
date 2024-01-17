<div class="row">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-body">

                <form id="demo-form2" class="form-horizontal form-label-left mt-4" method="post" action="<?= base_url()?>branch/edit_branch">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="row mb-3">
                        <label for="branch" class="col-sm-3 col-form-label">Code <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" id="code" name="code" class="form-control" value="<?= $row['branch_code'] ?>">
                            <small class="text-danger"><?= form_error('code') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="branch" class="col-sm-3 col-form-label">Name <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" id="branamench" name="name" class="form-control" value="<?= $row['branch_name'] ?>">
                            <small class="text-danger"><?= form_error('name') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="role" class="col-sm-3 col-form-label">Area <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="area" id="area" required>
                                <option selected="">Select Area</option>
                                <?php foreach ($areas as $k => $v): ?>
                                    <option value="<?= $v ?>" <?= $row['branch_area'] == $v ? 'selected' : '' ?>><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger"><?= form_error('area') ?></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="description" class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="description" name="description" style="height: 100px"><?= $row['branch_description'] ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <a href="<?= base_url()?>branch" type="button" class="btn btn-primary">Back</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
