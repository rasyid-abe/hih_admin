<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">

                <form id="demo-form2" class="form-horizontal form-label-left mt-4" method="post" action="<?= base_url()?>term_condition/edit_term_condition">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="row mb-3">
                        <label for="name" class="col-sm-2 col-form-label">Document Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" id="name" name="name" class="form-control" value="<?= $row['name'] ?>">
                            <small class="text-danger"><?= form_error('name') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="description" class="col-sm-3 col-form-label">Body <span class="text-danger">*</span></label>
                        <small class="text-danger"><?= form_error('body') ?></small>
                        <div class="col-sm-12">
                            <textarea class="tinymce-editor" name="body" id="body"><?= $row['body'] ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-9">
                            <div class="col-sm-12">
                                <a href="<?= base_url()?>term_condition" type="button" class="btn btn-primary">Back</a>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
