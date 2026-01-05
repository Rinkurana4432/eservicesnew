<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="container mt-4">

    <h2 class="text-center mb-4">RO Inspection Reports</h2>

    <!-- Error Message -->
    <div id="progress_report" class="alert alert-danger text-center" style="display:none;"></div>

    <!-- FILTER FORM -->
    <form method="get" action="<?= site_url('dashboard/roinspectionReport') ?>">

        <table class="table table-bordered table-striped">
            <tr>
                <td width="20%">
                    <label>From Date <span class="text-danger">*</span></label>
                </td>
                <td width="20%">
                    <input type="date"
                           id="from_date"
                           name="from_date"
                           value="<?= $fromdate ?? '' ?>"
                           class="form-control"
                           required>
                </td>

                <td width="20%">
                    <label>To Date</label>
                </td>
                <td width="20%">
                    <input type="date"
                           id="to_date"
                           name="to_date"
                           value="<?= $todate ?? '' ?>"
                           class="form-control">
                </td>

                <td width="20%" class="text-center align-middle">
                    <button type="submit"
                            id="inspection_report"
                            class="btn btn-primary">
                        View Reports
                    </button>
                </td>
            </tr>
        </table>

    </form>

    <!-- REPORT TABLE -->
    <?php if (!empty($records)) : ?>

        <table class="table table-bordered table-hover table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th>SRN / Sr No.</th>
                    <th>Applicant Name</th>
                    <th>Status</th>
                    <th>Land Location</th>
                    <th>Division</th>
                    <th>Request Date</th>
                    <th>Response Date</th>
                    <th>Inspection Report</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($records as $row): ?>

                <?php
                $report = $row['Report'] ?? '';
                $img_src = base_url('assets/images/noimg.jpeg');
                $pdf_icon = base_url('assets/images/pdf_view.jpeg');

                $preview = $img_src;
                if (strpos($report, 'application/pdf') !== false) {
                    $preview = $pdf_icon;
                }
                ?>

                <tr>
                    <td><?= $row['SRN'] ?? '-' ?></td>
                    <td><?= $row['Applicant_Name'] ?? '-' ?></td>
                    <td><?= $row['Status'] ?? '-' ?></td>
                    <td><?= $row['Address'] ?? '-' ?></td>
                    <td><?= $row['Division'] ?? '-' ?></td>
                    <td><?= $row['Request_Date'] ?? '-' ?></td>
                    <td><?= $row['Response_Date'] ?? '-' ?></td>
                    <td class="text-center">
                        <?php if (!empty($report)) : ?>
                            <a href="<?= $report ?>" target="_blank">
                                <img src="<?= $preview ?>" height="40">
                            </a>
                        <?php else : ?>
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>

            <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif; ?>

</div>


