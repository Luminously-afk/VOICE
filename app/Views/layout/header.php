<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= defined('SITENAME') ? SITENAME : 'V.O.I.C.E. - Olivarez College' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= URLROOT ?>/assets/css/style.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .text-success { color: #006400 !important; }
        .bg-success { background-color: #006400 !important; }
        .border-success { border-color: #006400 !important; }
        .btn-success {
            background-color: #006400;
            border-color: #006400;
        }
        .btn-success:hover {
            background-color: #004d00;
            border-color: #004d00;
        }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #006400; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #004d00; }

        .sticky-sidebar {
            position: sticky;
            top: 80px; 
            height: calc(100vh - 80px);
            overflow-y: auto;
            z-index: 100;
        }
        .navbar {
            z-index: 1030;
        }
    </style>
</head>
<body>