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
                <form method="post" id="sendotp"  >

                   <!-- CSRF -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token">

                    <div class="mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" name="mobile" maxlength="10" class="form-control numbersOnly" placeholder="Enter mobile number" required autocomplete="off" id="mobile_number">
                    </div>
					
                   <button type="button"  class="btn btn-primary w-30 text-center" onclick="sendOtp()">
					    Send OTP
					</button>


                </form>

                <form class="form-signin" action="<?php echo base_url();?>registration/step2" id="veriftotp" method="post" style="display:none">
                	        <div class="form-control" id="error1" style="color:red"></div> <br />        	
			        <input type="text" class="form-control" id="otp" placeholder="Enter OTP" maxlength="6"> <br /><br />
			        <input type="hidden" class="form-control" name="otp_mobile_number" id="otp_mobile_number" value="">
			        <input type="hidden" class="form-control" name="HMAC_TOKEN" id="hmac_token" value="" maxlength="6"> 
			        
			        <button onclick="isvalidOtp()" class="btn btn-sm btn-primary btn-block" type="button" style="width:300px">
			            Verify OTP
			        </button>
		        
                </form>

            </div>

        </div>
    </div>
<div id="modalwindow">                
		<div><h1>Communicating with server. Please wait...</h1>
		    <img src="<?php echo  base_url(); ?>assets/img/anim-loading.gif" alt="Please wait..." />
		</div>
	</div>
	
</div>

<script>
	document.addEventListener('DOMContentLoaded', function () {

    const BASE_URL = document.body.getAttribute("data-baseurl");

    document.getElementById("sendOtp").addEventListener("click", sendOtp);

});
	function sendOtp() {
		const BASE_URL = document.body.getAttribute("data-baseurl");
		 var mobNo = $.trim($("#mobile_number").val());
        // var csrf = $("#csrf_token").val();

          $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: BASE_URL + "/registration/sendotp",
                    data: {
                        mobile_number: mobNo
                    },
                    success: function (data) {
                                   	
                       // stopPreloader();

                        if (data === 'MISSING_PARAMS') {
                            alert("Missing parameters");

                        } else if (data === 'CSRF_ERROR') {
                            alert("Fraudulent data :: Bad Request");

                        } else if (data.error === 'ALREADY_REGISTERED') {
                            alert("A user with provided no. already exists!");

                        } else if (data.STATUS_ID === "111" || data.STATUS_ID === "222") {
                            alert(data.RESPONSE);

                        } else if (data.STATUS_ID === "000") {
                        	$("#mobile_number").val("");
                            $('#sendotp').hide();
                            $('#veriftotp').show();

                            $("#hmac_token").val(data.hmac_token);

                            //alert(data.otpmobilenumber);

                           // var encodedMob = base64_encode(data.otpmobilenumber);
                            $("#otp_mobile_number").val(data.otpmobilenumber);

                          

                        } else if (data.STATUS_ID === "555") {
                            alert(data.RESPONSE || "Unable to send otp! Please try again later.");
                           

                        } else {
                            alert("Unable to process request! Please try again");
                        }
                    }
                });
		
	}



	function isvalidOtp() {
		const BASE_URL = document.body.getAttribute("data-baseurl");
		var otp = $.trim($("#otp").val());
		var mobNo = $.trim($("#otp_mobile_number").val());

		//var csrf=$("#csrf_token").val();
		if(otp.length < 1){
			var error = "Please enter valid mobile number";
			$("#error1").html(error);
			$( "#error1" ).show( "slow" );
		}else if(mobNo.length < 1){
			var error = "Mobile number required";
			$("#error1").html(error);
			$( "#error1" ).show( "slow" );
		} else {
			$( "#error" ).hide( "slow" );
			//Add Loader
			//startPreloader();
			jQuery.ajax({
				type: 'POST',
				url: BASE_URL + "registration/validateotp",
				dataType: "json",
				data: { 'mobile_number':mobNo, 'otp':otp },
				success: function(data, textStatus, XMLHttpRequest) {

				
					// var obj = jQuery.parseJSON(data);	
					if(data.STATUS_ID == "111") {
						//stopPreloader();
						var error = "Error :: "+data.RESPONSE;
						$("#error1").html(error);
						$( "#error1" ).show( "slow" );
					} else if(data.STATUS_ID == "000") {
						$( "#veriftotp" ).submit();
					} else if(data.STATUS_ID == "222") {
						//stopPreloader();
						var error = "Error :: "+data.RESPONSE;
						$("#error1").html(error);
						$( "#error1" ).show( "slow" );
					} else {
						//stopPreloader();
						var error = "Please try again";
						$("#error1").html(error);
						$( "#error1" ).show( "slow" );
					}				
				}
			});

		}
	}
</script>