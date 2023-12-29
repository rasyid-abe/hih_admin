<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>HIH - <?= $title ?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?= base_url() ?>assets/img/favicon.png" rel="icon">
    <link href="<?= base_url() ?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->

    <link href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/jquery.toast.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/style.css" rel="stylesheet">

    <script src="<?= base_url()?>assets/js/jquery.min.js" charset="utf-8"></script>
    <script src="<?= base_url()?>assets/js/jquery.toast.js" charset="utf-8"></script>

    <style media="screen">
    #footer {
        position: fixed;
        height: 50px;
        bottom: 0px;
        left: 0px;
        right: 0px;
        margin-bottom: 0px;
    }
    </style>
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center bg-dark">

        <div class="d-flex align-items-center justify-content-between text-white">
            <a href="index.html" class="logo d-flex align-items-center">
                <!-- <h3 class="text-white">NICE ADMIN</h3> -->
                <!-- <img src="<?= base_url() ?>assets/img/logo.png" alt=""> -->
                <span class="d-none d-lg-block text-white">HIH Admin</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn text-white"></i>
        </div><!-- End Logo -->


        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li><!-- End Search Icon-->

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="<?= base_url('assets/profiles/'.$this->session->userdata('foto')) ?>" alt="Profile" class="rounded-circle">
                        <span class="text-white d-none d-md-block dropdown-toggle ps-2"><?= $this->session->userdata('fullname') ?></span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?= $this->session->userdata('fullname') ?></h6>
                            <span>Administrator</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('user/profile') ?>">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('user/password') ?>">
                                <i class="bi bi-key"></i>
                                <span>Change Password</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('auth/logout') ?>">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('home') ?>">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#user-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-people-fill"></i><span>User</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="user-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li><a href="<?= base_url('user') ?>"><i class="bi bi-circle"></i><span>List User</span></a></li>
                    <li><a href="<?= base_url('activity') ?>"><i class="bi bi-circle"></i><span>User Activity</span></a></li>
                </ul>
            </li><!-- End Components Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#document-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-folder-fill"></i><span>Document</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="document-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li><a href="<?= base_url('pdf') ?>"><i class="bi bi-circle"></i><span>PDF</span></a></li>
                    <li><a href="<?= base_url('text') ?>"><i class="bi bi-circle"></i><span>Text</span></a></li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-archive-fill"></i><span>Master</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="master-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li><a href="<?= base_url('role') ?>"><i class="bi bi-circle"></i><span>User Role</span></a></li>
                    <li><a href="<?= base_url('group') ?>"><i class="bi bi-circle"></i><span>Document Group</span></a></li>
                </ul>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#utility-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-layers-fill"></i><span>Utility</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="utility-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li><a href="<?= base_url('fraud') ?>"><i class="bi bi-circle"></i><span>Fraud</span></a></li>
                    <li><a href="<?= base_url('slide') ?>"><i class="bi bi-circle"></i><span>Slide Banner</span></a></li>
                    <li><a href="<?= base_url('term_condition') ?>"><i class="bi bi-circle"></i><span>Term & Condition</span></a></li>
                </ul>
            </li><!-- End Forms Nav -->

        </ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1><?= $title ?></h1>
        </div><!-- End Page Title -->

        <section class="section">
          <div class="row">
            <div class="col-lg-12">
                <?= $contents ?>
            </div>
          </div>
        </section>

    </main><!-- End #main -->

    <!-- <footer id="footer" class="footer">
        <div class="credits">
            &copy; Copyright <strong><span>deegevo</span></strong>. All Rights Reserved
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </footer> -->

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
        <?= '<script type="text/javascript">',
            'toast_act( "<h6>'.ucfirst($this->session->flashdata('alert_head')).'</h6>",',
            '"'.$this->session->flashdata('alert_msg').'",',
            '"'.$this->session->flashdata('alert_head').'",',
            '5000 )</script>';
        ?>
    <?php endif; ?>

    <script src="<?= base_url() ?>assets/js/jquery.dataTables.min.js" charset="utf-8"></script>
    <script src="<?= base_url() ?>assets/js/dataTables.responsive.min.js" charset="utf-8"></script>
    <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() ?>assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="<?= base_url() ?>assets/js/main.js"></script>

</body>

</html>
