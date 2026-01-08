<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Top Navbar -->


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
                        <td><a href="<?php echo base_url() ?>files/pdf/PermissionforfellingoftreesinGeneralSection4AreasHaryanaLandandPreservationAct1900.pdf" target="_blank">Click here to download</a></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>NOC in respect of Haryana Land Preservation Act, 1900 or Forest / Restricted lands</td>
                        <td>30 Days</td>
                        <td><a href="<?php echo base_url() ?>files/pdf/NOCinrespectofPLPAorForestorRestrictedLands.pdf">Click here to download</a></td>
                    </tr>
                    <tr>
						<td colspan="4">
						Areas of  Haryana State under Punjab Land Preservation Act, 1900 (PLPA) : <a target="_blank" href="<?php echo base_url() ?>files/pdf/PLPA Areas.pdf"><b>1. PLPA Areas</b></a><br/>	
						<a style="margin-left:405px" target="_blank" href="<?php echo base_url() ?>files/pdf/Sonepat District PLPA Areas.pdf"><b>2. Sonepat District PLPA Area</b></a>
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
       
        ?>
        <div class="col-md-3">
            <div class="border text-center p-4 h-100">
                <h5>Department Login</h5>
                <button class="btn btn-primary btn-sm mt-2">Login</button>
                  
             </div>
        </div>
         <div class="col-md-3">
            <div class="border text-center p-4 h-100">
                <h5>Self Login</h5>
                <form class="form-signin" id="self" method="post" action="<?=base_url(); ?>selfloginview">
                    <button class="btn btn-primary btn-sm mt-2">Login</button>
                </form>
                  <a href="<?php echo base_url(); ?>files/pdf/SelfloginUserManual.pdf" class="d-block mt-2 small" target="_blank">Download User Manual</a>
             </div>
        </div>
        <div class="col-md-3">
            <div class="border text-center p-4 h-100">
                <h5>CSC Connect</h5>
                 <form class="form-signin" id="csclogin" method="post" action="<?=base_url();?>cscconnect">
                <input type="submit" class="btn btn-primary btn-sm mt-2" data-loading-text="Loading..." value="Login" />
            </form>
                  <a href="<?php echo base_url(); ?>files/pdf/CSCUserManual.pdf" class="d-block mt-2 small" target="_blank">Download User Manual</a>
             </div>
        </div>
        <?php  $industrialConnect = INVEST_HARYANA_CONNECT; ?>
         <div class="col-md-3">
            <div class="border text-center p-4 h-100">
                <h5>Industrial Login</h5>
                <form class="form-signin" action="<?=$industrialConnect?>">
                <button class="btn btn-primary btn-sm mt-2">Login</button>
                  <a href="<?php echo base_url(); ?>files/pdf/InvestHaryanaUserManual.pdf" class="d-block mt-2 small" target="_blank">Download User Manual</a>
              </form>
             </div>
        </div>
      
    </div>
</div>

<!-- Info Section -->
<div class="container mt-5 text-center">
	 <div class="text-center">
                    <a href="http://saralharyana.gov.in/" target="_blank">
				    <img src="<?= base_url('files/img/Antyodaya-Saral-Logo-new.png') ?>" width="120" alt="Antyodaya Saral">
				</a>                    
               </div> 
    <p class="fw-bold">
        Forest Department eServices are now also available on Antyodaya SARAL
    </p>
   <p>To avail schemes or services, please visit <a href="http://saralharyana.gov.in/" target="_blank">www.saralharyana.gov.in

	 </a><a target="_blank" href="<?php echo base_url(); ?>files/pdf/SARALHARYAN.pdf"><b>(Download User Manual for SARAL Portal)</b> </a>
	</p>
    <p>OR</p>
    <p>visit Antyodaya Kendras (<a href="/eservices/dashboard/downloadfile?doc=saral-kendras.pdf">List of  Antyodaya SARAL Kendras</a>)</p>
    <p>For any queries to schemes/services, please call 0172-3968400 <sup style="color: red;">*7:00 AM - 8:00 PM (Monday to Saturday, excluding Government Holidays)</sup></p>


</div>
