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

<!-- Breadcrumb -->
<div class="maincls bg-white p-4">
<div class="container mt-3">
    <div class="bg-light p-2 rounded">
        <small>Dashboard &gt; Login</small>
    </div>
</div>

<!-- Information Table -->
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">
            Information regarding Services notified under Haryana Right to Services Act, 2014
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Name of Service</th>
                        <th>Time Limit</th>
                        <th>Documents Required</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Permission for felling of trees in areas notified under section-4 of Haryana Land Preservation Act, 1900</td>
                        <td>30 Days</td>
                        <td><a href="https://164.100.137.243/eservices/iviss/pdf/manuals/PermissionforfellingoftreesinGeneralSection4AreasHaryanaLandandPreservationAct1900.pdf">Click here to download</a></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>NOC in respect of Haryana Land Preservation Act, 1900 or Forest / Restricted lands</td>
                        <td>30 Days</td>
                        <td><a href="https://164.100.137.243/eservices/iviss/pdf/manuals/NOCinrespectofPLPAorForestorRestrictedLands.pdf">Click here to download</a></td>
                    </tr>
                    <tr>
						<td colspan="4">
						Areas of  Haryana State under Punjab Land Preservation Act, 1900 (PLPA) : <a target="_blank" href="https://164.100.137.243/eservices/iviss/pdf/landingPagePDF/PLPA Areas.pdf"><b>1. PLPA Areas</b></a><br/>	
						<a style="margin-left:405px" target="_blank" href="https://164.100.137.243/eservices/iviss/pdf/landingPagePDF/Sonepat District PLPA Areas.pdf"><b>2. Sonepat District PLPA Area</b></a>
						</td>
					</tr>
					<tr>
						<td colspan="4">
						<b>Online Portals and User Manuals for applying applications for the above mentioned Services are given below:-</b></td>
					</tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<!-- Login Section -->
<div class="container mt-5">
    <h3 class="fw-bold">
        Login <span class="fw-normal text-muted">to your account</span>
    </h3>

    <div class="row mt-4 g-3">
        <?php
        $logins = [
            'Department Login',
            'Self Login',
            'CSC Connect',
            'Industrial Login'
        ];
        foreach ($logins as $login):
        ?>
        <div class="col-md-3">
            <div class="border text-center p-4 h-100">
                <h5><?= $login ?></h5>
                <button class="btn btn-primary btn-sm mt-2">Login</button>
                <?php if ($login !== 'Department Login'): ?>
                    <a href="#" class="d-block mt-2 small">Download User Manual</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Info Section -->
<div class="container mt-5 text-center">
	 <div class="text-center">
                    <a href="http://saralharyana.gov.in/" target="_blank">
				    <img src="<?= base_url('assets/img/Antyodaya-Saral-Logo-new.png') ?>" width="120" alt="Antyodaya Saral">
				</a>                    
               </div> 
    <p class="fw-bold">
        Forest Department eServices are now also available on Antyodaya SARAL
    </p>
   <p>To avail schemes or services, please visit <a href="http://saralharyana.gov.in/" target="_blank">www.saralharyana.gov.in
					 </a><a target="_blank" href="https://164.100.137.243/eservices/iviss/pdf/manuals/SARALHARYAN.pdf"><b>(Download User Manual for SARAL Portal)</b> </a>
					</p>


</div>
