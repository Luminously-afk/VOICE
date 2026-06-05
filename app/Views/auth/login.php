<?php require_once '../app/Views/layout/header.php'; ?>

<div class="position-absolute top-50 start-50 translate-middle w-100 px-3 login-bg-wrapper" style="height: 100vh;">
    <div class="card shadow-sm border-0 position-absolute top-50 start-50 translate-middle" style="width: calc(100% - 24px); max-width: 480px; border-radius: 4px; overflow: hidden; z-index: 10;">
        
        <div class="bg-success text-white text-center py-4" style="background-color: #00701a !important;">
            <h3 class="fw-bold mb-1">V.O.I.C.E. Login</h3>
            <p class="mb-0" style="font-size: 0.9rem;">Olivarez College Insight System</p>
        </div>
        
        <div class="card-body p-4 bg-white">
            <?php require_once '../app/Core/Flash.php'; Flash::display('auth_error'); Flash::display('auth_success'); ?>
            <form action="<?= URLROOT ?>/auth/login" method="POST">
                <div class="mb-3">
                    <input type="text" name="login_id" class="form-control p-3" placeholder="Student ID or Email" required>
                </div>
                
                <div class="mb-4">
                    <input type="password" name="password" class="form-control p-3" placeholder="Password" required>
                </div>
                
                <button type="submit" class="btn btn-success w-100 py-3" style="background-color: #007f1b; border: none; font-size: 1.1rem;">Login</button>
            </form>
        </div>

        <div class="card-footer bg-light text-center py-3" style="border-top: 1px solid #eaeaea;">
            <span class="text-dark">Don't have an account?</span> <a href="<?= URLROOT ?>/auth/register" class="text-success text-decoration-none fw-bold" style="color: #007f1b !important;">Sign up now</a>
        </div>
        
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>