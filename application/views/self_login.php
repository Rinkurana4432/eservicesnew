<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <!-- Flash Error -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger text-center">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <!-- Card -->
            <div class="card shadow">
                <div class="card-header bg-info text-white text-center">
                    <h5 class="mb-0">Two Factor Authentication</h5>
                </div>
                
				<form style="" method="post" action="<?=base_url();?>selflogin">
                <div class="card-body">
                        <input type="hidden"
                               name="<?= $this->security->get_csrf_token_name(); ?>"
                               value="<?= $this->security->get_csrf_hash(); ?>">

                        <input type="hidden"
                               name="<?= base64_encode('submissionType'); ?>"
                               value="<?= base64_encode('selfLogin'); ?>">

                        <!-- Mobile -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mobile No</label>
                            <input type="text" name="mobile"  maxlength="10" class="form-control numbersOnly" placeholder="Enter Mobile Number" required>
                        </div>

                        <!-- Year -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Year of Birth</label>
                            <input type="text" name="year" maxlength="4" class="form-control numbersOnly" placeholder="YYYY" required>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <input  type="submit" class="btn btn-primary " value="Authenticate">
                                
                            
							<a href="<?= base_url('registration'); ?>" class="btn btn-outline-primary">
                                Register
                            </a>
                        </div>
                    </form>    
                   

                    <hr>

                    <p class="medium text-muted mb-0 text-center">
                        For any queries/issues related to self login, please call
                        <strong>0172-6619061</strong>
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>