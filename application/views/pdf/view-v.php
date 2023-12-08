<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body pt-3">
                <embed src="<?= base_url() .'assets/documents/'.str_replace(' ', '_', $pdf['file_name']).'.pdf' ?>" width="100%" height="600" />
            </div>
            <div class="card-footer">
                <div class="row mb-3">
                    <div class="col-sm-12" style="margin-left: 6px;">
                        <a href="<?= base_url()?>pdf" type="button" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
