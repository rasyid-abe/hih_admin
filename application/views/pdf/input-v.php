<div class="row">
    <div class="col-sm-8">
        <div class="card">
            <form id="demo-form2" class="form-horizontal form-label-left mt-4" method="post" action="<?= base_url()?>pdf/add_pdf"  enctype="multipart/form-data">
                <div class="card-body">

                    <div class="row mb-3">
                        <label for="name" class="col-sm-3 col-form-label">Name <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" id="file_name" name="file_name" class="form-control" value="<?= set_value('file_name') ?>">
                            <small class="text-danger"><?= form_error('file_name') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="name" class="col-sm-3 col-form-label">Description <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="description" rows="2" cols="80"><?= set_value('description') ?></textarea>
                            <small class="text-danger"><?= form_error('description') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="role" class="col-sm-3 col-form-label">Group Document  <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="group" id="group" required>
                                <option selected="">Select Group</option>
                                <?php foreach ($groups as $k => $v): ?>
                                    <option value="<?= $v['id'] ?>" <?= set_value('group') == $v['id'] ? 'selected' : '' ?> ><?= $v['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger"><?= form_error('group') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="description" class="col-sm-3 col-form-label">Upload File <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="file" name="pdf" class="" id="pdf" value="" accept="application/pdf">
                            <small class="text-danger"><?= form_error('pdf') ?></small>
                        </div>
                    </div>


                </div>
                <div class="card-footer">
                    <div class="row mb-3">
                        <div class="col-sm-12" style="margin-left: 6px;">
                            <a href="<?= base_url()?>pdf" type="button" class="btn btn-primary">Back</a>
                            <button type="submit" class="btn btn-success">Upload</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
