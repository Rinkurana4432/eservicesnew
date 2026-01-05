<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Top Navbar -->
<nav class="navbar topbar navbar-expand-lg py-1">
    <div class="container">
        <span class="navbar-brand text-white fw-bold">Forest Department</span>
        <div class="ms-auto">
             <a href="<?= site_url('dashboard') ?>">Dashboard</a>
            <!-- <a href="<?= base_url('dashboard') ?>">Dashboard</a> -->
            <a href="#">GIS Based Application Summary</a>
            <a href="#">RO Inspection Reports</a>
            <a href="#">Login</a>
        </div>
    </div>
</nav>
<div class="container  maincls bg-white p-4">
<div class="container mt-3">
    <div class="bg-light p-2 rounded">
        <small>Dashboard</small>
    </div>
</div>

<?php
$fromdate = $from_date ?? '';
$todate   = $to_date ?? '';

$service = $rawData['service'] ?? '';
$total_rec = $rawData['total_rec'] ?? '';
$total_pending = $rawData['total_pending'] ?? '';
$beyond_time_pen = $rawData['beyond_time_pen'] ?? '';
$within_time_pen = $rawData['within_time_pen'] ?? '';
$total_approved = $rawData['total_approved'] ?? '';
$beyond_time_app = $rawData['beyond_time_app'] ?? '';
$within_time_app = $rawData['within_time_app'] ?? '';

$service_clar = $rawData['service_clar'] ?? '';
$total_rec_clar = $rawData['total_rec_clar'] ?? '';
$total_pen_clar = $rawData['total_pen_clar'] ?? '';
$beyond_pen_clar = $rawData['beyond_pen_clar'] ?? '';
$within_pen_clar = $rawData['within_pen_clar'] ?? '';
$total_app_clar = $rawData['total_app_clar'] ?? '';
$beyond_app_clar = $rawData['beyond_app_clar'] ?? '';
$within_app_clar = $rawData['within_app_clar'] ?? '';

$total_rejected = $rawData['total_rejected'] ?? '';
$beyond_time_rej = $rawData['beyond_time_rej'] ?? '';
$within_time_rej = $rawData['within_time_rej'] ?? '';
$total_rej_clar = $rawData['total_rej_clar'] ?? '';
$beyond_rej_clar = $rawData['beyond_rej_clar'] ?? '';
$within_rej_clar = $rawData['within_rej_clar'] ?? '';
?>

<div id="progress_report" class="text-center p-2 bg-danger-subtle"></div>

<br>

<form method="POST" action="<?= base_url('dashboard/index') ?>">
<table class="table table-bordered table-striped">
    <tr>
        <td>From Date <span class="text-danger">*</span></td>
        <td>
            <input type="date" name="from_date" class="form-control" value="<?= $fromdate ?>" required>
        </td>
        <td>To Date</td>
        <td>
            <input type="date" name="to_date" class="form-control" value="<?= $todate ?>" required>
        </td>
        <td rowspan="2" class="text-center align-middle">
            <button type="submit" class="btn btn-primary">View Dashboard</button>
        </td>
    </tr>
</table>
</form>

<?php if (!empty($rawData)) : ?>

<table class="table table-bordered table-hover">
    <thead class="table-info text-center">
        <tr>
            <th>Services</th>
            <th>Applications Received</th>
            <th>Approved</th>
            <th>Pending</th>
            <th>Rejected</th>
        </tr>
    </thead>

    <tbody class="text-center">
        <tr>
            <td class="bg-primary text-white"><b><?= $service ?></b></td>
            <td class="bg-secondary">
                <a target="_blank" href="view?records=7&from_date=<?= $fromdate ?>&to_date=<?= $todate ?>">
                    <?= $total_rec ?>
                </a>
            </td>

            <td>
                Total: <?= $total_approved ?><br>
                <span class="text-success">Within: <?= $within_time_app ?></span><br>
                <span class="text-warning">Beyond: <?= $beyond_time_app ?></span>
            </td>

            <td>
                Total: <?= $total_pending ?><br>
                <span class="text-success">Within: <?= $within_time_pen ?></span><br>
                <span class="text-warning">Beyond: <?= $beyond_time_pen ?></span>
            </td>

            <td>
                Total: <?= $total_rejected ?><br>
                <span class="text-success">Within: <?= $within_time_rej ?></span><br>
                <span class="text-warning">Beyond: <?= $beyond_time_rej ?></span>
            </td>
        </tr>

        <tr>
            <td class="bg-primary text-white"><b><?= $service_clar ?></b></td>
            <td class="bg-secondary">
                <?= $total_rec_clar ?>
            </td>

            <td>
                Total: <?= $total_app_clar ?><br>
                <span class="text-success">Within: <?= $within_app_clar ?></span><br>
                <span class="text-warning">Beyond: <?= $beyond_app_clar ?></span>
            </td>

            <td>
                Total: <?= $total_pen_clar ?><br>
                <span class="text-success">Within: <?= $within_pen_clar ?></span><br>
                <span class="text-warning">Beyond: <?= $beyond_pen_clar ?></span>
            </td>

            <td>
                Total: <?= $total_rej_clar ?><br>
                <span class="text-success">Within: <?= $within_rej_clar ?></span><br>
                <span class="text-warning">Beyond: <?= $beyond_rej_clar ?></span>
            </td>
        </tr>
    </tbody>
</table>

<table class="table w-50 float-end">
    <tr>
        <td class="bg-primary text-white"><b>Legends</b></td>
        <td class="bg-secondary"><b>Total</b></td>
        <td class="bg-success text-white"><b>Within Time</b></td>
        <td class="bg-warning text-white"><b>Beyond Time</b></td>
    </tr>
</table>

<?php endif; ?>
</div>