<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Forest Department' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 + Other CSS -->
    <?php if (!empty($css_files)): ?>
        <?php foreach ($css_files as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body data-baseurl="<?= base_url(); ?>">
    <nav class="navbar topbar navbar-expand-lg py-1">
        <div class="container">
            <a href="<?= base_url() ?>"><span class="navbar-brand text-white fw-bold">Forest Department</span></a>
            <div class="ms-auto">
                 <a href="<?= base_url('dashboard') ?>">Dashboard</a>
                 <a href="<?= base_url('gisdetails') ?>">GIS Based Application Summary</a>
                <a href="<?= base_url('roinspection') ?>">RO Inspection Reports</a>
                <a href="#">Login</a>
            </div>
        </div>
    </nav>
<div class="container my-4">
 <!-- Content -->
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <h3 class="text-center mb-4">
                Registration :: Step 2 of 2
            </h3>

            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-4">

                    <?php if (isset($error)): ?>

                        <div class="alert alert-danger">
                            <?= $error; ?>
                        </div>

                    <?php else: ?>

                        <?php if (isset($response) && $response->STATUS_ID === '000'): ?>

                            <div class="alert alert-success">
                                <h4 class="alert-heading mb-3">
                                    Thank you for Registration
                                </h4>
                                <p>
                                    Click
                                    <a href="<?= base_url(); ?>" class="fw-bold">
                                        here
                                    </a>
                                    to navigate to the login page.
                                </p>
                            </div>

                        <?php elseif (
                            isset($response) &&
                            ($response->STATUS_ID === '111' || $response->STATUS_ID === '222')
                        ): ?>

                            <div class="alert alert-warning">
                                <?= ucfirst(strtolower($response->RESPONSE)); ?>
                            </div>

                        <?php else: ?>

                            <div class="alert alert-danger">
                                Please try again.
                            </div>

                        <?php endif; ?>

                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>
<footer class="text-center mt-5 py-3 bg-light">
    <small>
        For any queries, kindly mail at <strong>forestry[at]gmail[dot]com</strong><br>
        © 2026 Haryana Government. All Rights Reserved.
    </small>
</footer>

<!-- Bootstrap 5 + Other JS -->
<?php if (!empty($js_files)): ?>
    <?php foreach ($js_files as $js): ?>
        <script src="<?= $js ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
