<?php require_once '../app/Views/layout/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="text-center">
        <i class="fas fa-search text-success mb-4" style="font-size: 6rem;"></i>
        <h1 class="display-1 fw-bold text-dark">404</h1>
        <h3 class="text-uppercase mb-3">Page Not Found</h3>
        <p class="text-muted mb-4 lead">
            We couldn't find the page or event you are looking for. <br>
            It might have been removed or the link is broken.
        </p>
        <a href="<?= URLROOT ?>/" class="btn btn-success btn-lg px-5" style="background-color: #006400;">
            <i class="fas fa-arrow-left me-2"></i> Go Back Home
        </a>
    </div>
</div>

<?php require_once '../app/Views/layout/footer.php'; ?>