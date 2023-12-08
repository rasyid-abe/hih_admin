<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>HIH Admin</title>
    <link href="<?= base_url() ?>assets/css/jquery.toast.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="<?= base_url() ?>assets/logo/hihlogo.png">
    <style media="screen">
        h1 {
            font-weight: bold;
            margin: 0;
        }

        button {
            border-radius: 10px;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: bold;
            padding: 12px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in;
        }

        input {
            border-radius: 10px;
            background-color: #eee;
            border: none;
            padding: 12px 15px;
            margin: 8px 0;
            width: 100%;
        }

</style>
<script src="<?= base_url() ?>/assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>/assets/js/jquery.toast.js"></script>
</head>
<body>

    <section class="h-100 gradient-form" style="background-color: #fff;">
        <div class="container py-5 my-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-xl-10">
                    <div class="card rounded-3 text-black">
                        <div class="row g-0">
                            <div class="col-lg-6 bg-dark">
                                <div class="card-body p-md-5 mx-md-4 mt-5 mb-5">

                                    <form method="post" action="<?= base_url( 'auth/login' );?>">
                                        <h1 class="mb-4 text-left text-white text-bold">Sign in</h1>
                                        <input name="nik" type="text" placeholder="NIK" required />
                                        <input name="password" type="password" placeholder="Password" id="passtext" required />
                                        <button class="btn-block mt-2 btn-primary" type="submit">Sign In</button>
                                    </form>

                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center" style="background-color: #feebd8;">
                                <div class="text-dark px-3 py-4 p-md-5 mx-md-4">
                                    <div class="mb-3 text-center">
                                        <img src="<?= base_url() ?>assets/img/hihbig.png" height="100" alt="" style="z-index: -1;">
                                    </div>
                                    <h6 class="text-center">Enter your personal details and start journey with us</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script type="text/javascript">
function toast_act(heading, text, status, time=5000) {
    $.toast({
        heading: heading,
        text: text,
        showHideTransition: 'fade',
        position: 'top-right',
        icon: status,
        hideAfter: time
    })
}
</script>
<?php if ($this->session->flashdata('alert_msg')): ?>
    <?=
    '<script type="text/javascript">',
    'toast_act( "<h6>'.ucfirst($this->session->flashdata('alert_head')).'</h6>",',
    '"'.$this->session->flashdata('alert_msg').'",',
    '"'.$this->session->flashdata('alert_head').'",',
    '5000 )</script>';
    ?>
<?php endif; ?>

</body>
</html>
