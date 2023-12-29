<div class="row">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-body">

                <form id="demo-form2" class="form-horizontal form-label-left mt-4" method="post" action="<?= base_url()?>group/add_group">
                    <div class="row mb-3">
                        <label for="name" class="col-sm-3 col-form-label">Group <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" id="name" name="name" class="form-control" value="<?= set_value('name') ?>">
                            <small class="text-danger"><?= form_error('name') ?></small>
                        </div>
                    </div>

                    <!-- <div class="row mb-3">
                        <label for="role" class="col-sm-3 col-form-label">Group Document  <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-select" name="group" id="group" required>
                                <option selected="">Select Group</option>
                                <?php foreach ($icons as $k => $v): ?>
                                    <option><?= $v->name ?><?= '<ion-icon name="'.$v->name.'"></ion-icon>'?><ion-icon name="<?= $v->name ?>"></ion-icon></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger"><?= form_error('group') ?></small>
                        </div>
                    </div> -->
                    
                    <div class="row mb-3">
                        <label for="description" class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="description" name="description" style="height: 100px"><?= set_value('description') ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <a href="<?= base_url()?>group" type="button" class="btn btn-primary">Back</a>
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
