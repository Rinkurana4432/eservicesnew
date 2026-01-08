<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <h3 class="mb-4 fw-bold text-center">
                Registration :: Step 2 of 2
            </h3>

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

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <div class="text-end text-muted mb-3 fw-bold">
                        Fields with <span class="text-danger">*</span> are required.
                    </div>

                    <form method="post" action="<?= base_url('registration/saveuserdetails'); ?>">

                        <!-- CSRF -->
                        <input type="hidden"
                               name="<?= $this->security->get_csrf_token_name(); ?>"
                               value="<?= $this->security->get_csrf_hash(); ?>">

                        <!-- Mobile -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mobile Number</label>
                            <input type="text" class="form-control" readonly
                                   style="background-color:#DDD"
                                   id="mobile_number" name="mobile_number"
                                   value="<?php if(isset($post['otp_mobile_number'])){ echo $post['otp_mobile_number']; }?>">
                            <input type="hidden" name="HMAC_TOKEN"
                                   value="<?php if(isset($post['HMAC_TOKEN'])){ echo $post['HMAC_TOKEN']; }?>">
                        </div>

                        <!-- Full Name + Gender -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="full_name" name="full_name"
                                       class="form-control" required
                                       placeholder="Enter your name" autocomplete="off">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    Gender <span class="text-danger">*</span>
                                </label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="gender" id="male" value="M" required>
                                    <label class="btn btn-outline-primary" for="male">Male</label>

                                    <input type="radio" class="btn-check" name="gender" id="female" value="F">
                                    <label class="btn btn-outline-primary" for="female">Female</label>

                                    <input type="radio" class="btn-check" name="gender" id="transgender" value="T">
                                    <label class="btn btn-outline-primary" for="transgender">Transgender</label>
                                </div>
                            </div>
                        </div>

                        <!-- DOB + Email -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    Date of Birth <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="date_of_birth" name="date_of_birth"
                                       class="form-control" required readonly
                                       placeholder="Enter Date of Birth">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" id="email" name="email"
                                       class="form-control" placeholder="Enter Email" autocomplete="off">
                            </div>
                        </div>

                        <!-- Birth Place -->
                        <?php $stateId = 3; ?>
                        <input type="hidden" name="birthplace_state_id" value="<?= $stateId; ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    District <span class="text-danger">*</span>
                                </label>
                                <select name="birthplace_dist_id" id="birthplace_dist_id"
                                        class="form-select" required>
                                    <option value="">Select District</option>
                                    <?php
                                    $district = activedistrictsbystateid($stateId);
                                    foreach ($district as $value) {
                                    ?>
                                        <option value="<?= $value['LR_ID']; ?>">
                                            <?= $value['LR_Name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">City</label>
                                <select name="birthplace_city_id" id="birthplace_city_id"
                                        class="form-select">
                                    <option value="">Select City</option>
                                </select>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Address <span class="text-danger">*</span>
                            </label>
                            <textarea name="address" id="address"
                                      class="form-control" rows="3"
                                      placeholder="Enter your address" required></textarea>
                        </div>

                        <!-- UID / EID (hidden, unchanged) -->
                        <div class="mb-4 hidden">
                            <label class="form-label">UID</label>
                            <input type="text" id="uid" name="uid"
                                   class="input-xlarge hidden aadhar_number"
                                   autocomplete="off">
                        </div>

                        <div class="mb-4 hidden">
                            <label class="form-label">EID</label>
                            <input type="text" id="eid" name="eid"
                                   class="input-xlarge hidden eid"
                                   autocomplete="off">
                        </div>

                        <!-- Submit -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-md px-4">
                                Register
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

    const BASE_URL = document.body.getAttribute("data-baseurl");

    $('#birthplace_dist_id').on('change', function () {
        var distID = $("#birthplace_dist_id option:selected").val();
       //var citySelect = $(distID).parents('.dist-city-combo').find('select.city-select');
        //var distID = $(distID).val();   
          
        var url = BASE_URL + 'registration/getallcitiesindistrict';

        if(distID){
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: { 'distID': distID },
                beforeSend: function(){
                   // startPreloader();
                },
                success: function (data){
                   // stopPreloader();
                    $('#birthplace_city_id').html('');
                    $('#birthplace_city_id').html('<option value="">Select City</option>');
                    $.each(data, function (i, val) {
                        $('#birthplace_city_id').append('<option value=' + val.City_ID + '>' + val.CITY_NAME + '</option>');
                    });
                }
            });
        }
   
});

});
  
</script>