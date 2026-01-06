<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <h4 class="text-center mb-4">
                Registration :: Step 1 of 2 (Generate OTP)
            </h4>
<div id="error" style="display:none; color:red; margin-bottom:10px;"></div>

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?= $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <div class="account-wall">

                <!-- SEND OTP FORM -->
                <form method="post" >

                    <!-- CSRF -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token">

                    <div class="mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" name="mobile" maxlength="10" class="form-control numbersOnly" placeholder="Enter mobile number" required autocomplete="off" id="mobile_number">
                    </div>
					<button type="submit" class="btn btn-primary w-30 text-center"   id="sendOtp">
                        Send OTP
                    </button>
                   	

                </form>

                <form class="form-signin" action="<?php echo base_url();?>registration/step2" id="veriftotp" method="post" style="display:none">
                	<div class="form-control" id="error1" style="color:red"></div> <br />                	
			        <input type="text" class="form-control" id="otp" placeholder="Enter OTP" maxlength="6"> <br /><br />
			        <input type="hidden" class="form-control" name="otp_mobile_number" id="otp_mobile_number" placeholder="Enter OTP" maxlength="6" autocomplete="off">
			        <input type="hidden" class="form-control" name="HMAC_TOKEN" id="hmac_token" value="" maxlength="6"> 
			        
			        <button onclick="isvalidOtp()" class="btn btn-sm btn-primary btn-block" type="button" style="width:300px">
			            Verify OTP
			        </button>
		        
                </form>

            </div>

        </div>
    </div>

	</div>
