{{-- resources/views/includes/admin/head.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/vendor/images/logo.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/style/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/style/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/style/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/style/responsive.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <title>Cooperative Shares Admin</title>
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

    </style>

    @stack('styles')
</head>
<body>
    <main class="main-layout-wrapper">
