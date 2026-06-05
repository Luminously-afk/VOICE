<?php 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | V.O.I.C.E.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background-color: #f4f4f4;">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="text-center">
        <i class="fas fa-server text-warning mb-4" style="font-size: 6rem;"></i>
        <h1 class="display-1 fw-bold text-dark">500</h1>
        <h3 class="text-uppercase mb-3">Internal Server Error</h3>
        <p class="text-muted mb-4 lead">
            Our system is currently experiencing technical difficulties. <br>
            Our IT team has been notified. Please try again later.
        </p>
        <a href="<?= URLROOT ?? '/' ?>" class="btn btn-secondary btn-lg px-5">
            <i class="fas fa-sync-alt me-2"></i> Try Again
        </a>
    </div>
</div>

</body>
</html>