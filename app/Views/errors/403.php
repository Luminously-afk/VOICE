<?php require_once '../app/Views/layout/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="text-center">
        <i class="fas fa-shield-alt text-danger mb-4" style="font-size: 6rem;"></i>
        <h1 class="display-1 fw-bold text-dark">403</h1>
        <h3 class="text-uppercase mb-3">Access Denied</h3>
        <p class="text-muted mb-4 lead">
            Oops! You do not have permission to view this page. <br>
            If you think this is a mistake, please contact the V.O.I.C.E. admin.
        </p>
        <a href="<?= URLROOT ?>/" class="btn btn-success btn-lg px-5" style="background-color: #006400;">
            <i class="fas fa-home me-2"></i> Return to Feed
        </a>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>