<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/user/images/logo.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/user/style/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/user/style/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/user/style/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/user/style/responsive.css') }}">
    <title>Cooperative Shares</title>
    <style>
        .sidebar-submenu li a {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-notify-circle {
            position: absolute;
            top: -4px;
            left: 14px;
            background: #295568;
            color: #fff;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 50%;
            line-height: 1;
        }

        /* ---------- GLOBAL TOAST FIX ---------- */
        /* Force toast ABOVE everything, at top-right, even over modals */
        .jq-toast-wrap {
            position: fixed !important;
            z-index: 9999999 !important;
            pointer-events: none; /* clicks pass through */
        }

        .jq-toast-wrap.top-right {
            top: 20px !important;
            right: 20px !important;
            bottom: auto !important;
            left: auto !important;
        }

        .jq-toast-single {
            pointer-events: auto; /* keep clicks on close button etc */
        }

        @media (max-width: 575.98px) {
            .jq-toast-wrap.top-right {
                top: 10px !important;
                right: 10px !important;
                left: 10px !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <main class="main-layout-wrapper">
