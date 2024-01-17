<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body pt-4">
                <h5><span class="badge bg-dark mb-2">Role <?= $name_role ?></span></h5>
                <?php foreach ($groups as $k => $v): ?>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" <?= check_access($id_role, $v['id']) ?> data-role="<?= $id_role ?>" data-group="<?= $v['id']?>" data-name="<?= $name_role ?>">
                        <label class="form-check-label" for="flexSwitchCheckDefault"><?= $v['name'] ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="card-footer">
                <div class="row mb-3">
                    <div class="col-sm-12" style="margin-left: 6px;">
                        <a href="<?= base_url()?>role" type="button" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.form-check-input').on('click', function() {
        const role = $(this).data('role');
        const group = $(this).data('group');
        const name = $(this).data('name');

        $.ajax({
            url: '<?= base_url('role/change_access') ?>',
            method: 'post',
            data: {role, group},
            success: function() {
                document.location.href = '<?= base_url('role/config_role/')?>' + role + '/' + name;
            }
        })
    })
</script>
