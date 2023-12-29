<div class="row">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-body">

                <form id="demo-form2" class="form-horizontal form-label-left mt-4" method="post" action="<?= base_url()?>slide/add_slide" enctype="multipart/form-data">

                    <div class="row mb-3">
                        <label for="description" class="col-sm-3 col-form-label">Image <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="file" name="image" class="" id="image" value=""  accept="image/png, image/jpeg">
                            <small class="text-danger"><?= form_error('image') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="name" class="col-sm-3 col-form-label">Name <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" id="name" name="name" class="form-control" value="">
                            <small class="text-danger"><?= form_error('name') ?></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="description" class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="description" name="description" style="height: 100px"><?= set_value('description') ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <a href="<?= base_url()?>slide" type="button" class="btn btn-primary">Back</a>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
