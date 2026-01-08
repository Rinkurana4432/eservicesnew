<div class="container my-4">

    <!-- Page Title -->
    <div class="row justify-content-center mb-3">
        <div class="col-lg-6 col-md-8 text-center">
            <h3 class="fw-bold">Step 2: OTP Verification</h3>
        </div>
    </div>

    <!-- Flash / Error Messages -->
    <?php if ($this->session->flashdata('error')): ?>
        <div class="row justify-content-center mb-3">
            <div class="col-lg-6 col-md-8">
                <div class="alert alert-danger">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="row justify-content-center mb-3">
            <div class="col-lg-6 col-md-8">
                <div class="alert alert-danger">
                    <?= $error; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- OTP Card -->
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <?php if (isset($post)): ?>
                        <form method="post" action="<?= base_url('landing/validateotp'); ?>">

                            <!-- CSRF -->
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                            <!-- Hidden Values -->
                            <input type="hidden" name="<?= base64_encode('mo'); ?>" value="<?= base64_encode($post['mobile']); ?>">

                            <input type="hidden" name="HMAC" value="<?= $post['HMAC']; ?>">

                            <!-- OTP -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    OTP <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="otp" class="form-control" placeholder="Enter OTP" maxlength="4" required>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Authenticate
                                </button>

                                <a href="<?= base_url('registration'); ?>"
                                   class="btn btn-outline-primary">
                                    Register
                                </a>
                            </div>

                        </form>
                    <?php endif; ?>

                    <!-- Error fallback -->
                    <?php if (isset($error)): ?>
                        <form method="post" action="<?= base_url('landing/selflogin'); ?>" class="mt-4">
                            <input type="hidden"
                                   name="<?= base64_encode('submissionType'); ?>"
                                   value="<?= base64_encode('selfLogin'); ?>">

                            <button type="submit" class="btn btn-secondary">
                                Back to Self Login
                            </button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>
