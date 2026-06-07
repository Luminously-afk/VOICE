<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= defined('SITENAME') ? SITENAME : 'V.O.I.C.E. - Olivarez College' ?></title>
    <!-- Tailwind CSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'voice-dark': '#1a1d21',
                        'voice-card': '#2d333b',
                        'voice-input': '#22272e',
                        'voice-green': '#4ade80',
                        'voice-green-dark': '#22c55e',
                        'voice-border': '#30363d',
                        'voice-border-light': '#444c56',
                    },
                    boxShadow: {
                        'glow': '0 0 20px 2px rgba(74, 222, 128, 0.3)',
                        'glow-green': '0 0 8px 1px rgba(80, 212, 117, 0.4)',
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            background-color: #121417;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            color: #adbac7;
        }
        
        .input-underline {
            background: transparent;
            border: none;
            border-bottom: 1px solid #4b5563;
            transition: border-color 0.3s ease;
        }
        .input-underline:focus {
            outline: none;
            border-bottom-color: #4ade80;
            box-shadow: none;
        }
        
        .voice-input:focus {
            outline: none;
            border-color: #4ade80;
            box-shadow: 0 0 10px rgba(74, 222, 128, 0.5);
        }
        
        .custom-checkbox:checked {
            background-color: #4ade80;
            border-color: #4ade80;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1a1d21;
        }
        ::-webkit-scrollbar-thumb {
            background: #444c56;
            border-radius: 4px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">