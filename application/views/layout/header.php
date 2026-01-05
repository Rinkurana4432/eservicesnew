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
<body>
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
