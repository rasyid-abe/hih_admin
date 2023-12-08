<div class="row">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-body">

                <form id="demo-form2" class="form-horizontal form-label-left mt-4" method="post" action="<?= base_url()?>pdf/edit_pdf" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="old_name" value="<?= $row['file_name'] ?>">
                    <div class="row mb-3">
                        <label for="name" class="col-sm-3 col-form-label">Name <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" id="file_name" name="file_name" class="form-control" value="<?= $row['file_name'] ?>">
                            <small class="text-danger"><?= form_error('file_name') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="role" class="col-sm-3 col-form-label">Group Document  <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="group" id="group" required>
                                <option selected="">Select Group</option>
                                <?php foreach ($groups as $k => $v): ?>
                                    <option value="<?= $v['id'] ?>" <?= $row['group_id'] == $v['id'] ? 'selected' : '' ?>><?= $v['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger"><?= form_error('group') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="description" class="col-sm-3 col-form-label">Description <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="description" id="description"><?= $row['description'] ?></textarea>
                            <small class="text-danger"><?= form_error('description') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="description" class="col-sm-3 col-form-label">Changes File </label>
                        <div class="col-sm-9">
                            <!-- <embed src="<?= base_url() .'assets/documents/'.str_replace(' ', '_', $row['file_name']).'.pdf' ?>" /> -->
                            <input type="file" name="pdf" class="" id="pdf" value="" accept="application/pdf">
                            <small class="text-danger"><?= form_error('pdf') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-9">
                            <div class="col-sm-12">
                                <a href="<?= base_url()?>pdf" type="button" class="btn btn-primary">Back</a>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
