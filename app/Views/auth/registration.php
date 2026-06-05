<?php require_once '../app/Views/layout/header.php'; ?>

<div class="w-100 register-bg-wrapper" style="min-height: 100vh; display: flex; align-items: center; padding-top: 5vh; padding-bottom: 5vh;">
    <div class="card shadow-sm border-0 w-100 mx-auto" style="max-width: 480px; border-radius: 4px; overflow: hidden; z-index: 10;">
        
        <div class="bg-success text-white text-center py-4" style="background-color: #00701a !important;">
            <h3 class="fw-bold mb-1">Create Account</h3>
            <p class="mb-0" style="font-size: 0.9rem;">Join the V.O.I.C.E. of Olivarez College</p>
        </div>
        
        <div class="card-body p-4 bg-white">
            <?php require_once '../app/Core/Flash.php'; Flash::display('auth_error'); ?>
            <form action="<?= URLROOT ?>/auth/register" method="POST">
                <div class="mb-3">
                    <input type="text" name="student_num" class="form-control p-3" placeholder="Student Number" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="name" class="form-control p-3" placeholder="Full Name" required>
                </div>

                <div class="mb-3">
                    <input type="email" name="email" class="form-control p-3" placeholder="Email address" pattern="[a-zA-Z]+\.[a-zA-Z]+@olivarezcollege\.edu\.ph" title="Must be firstname.lastname@olivarezcollege.edu.ph" required>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <input type="password" name="password" class="form-control p-3" placeholder="Password" required>
                    </div>
                    <div class="col-md-6">
                        <input type="password" name="confirm_password" class="form-control p-3" placeholder="Confirm Password" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success w-100 py-3" style="background-color: #007f1b; border: none; font-size: 1.1rem;">Create Account</button>
            </form>
        </div>

        <div class="card-footer bg-light text-center py-3" style="border-top: 1px solid #eaeaea;">
            <span class="text-dark">Have an account?</span> <a href="<?= URLROOT ?>/auth/login" class="text-success text-decoration-none fw-bold" style="color: #007f1b !important;">Go to login</a>
        </div>
        
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>