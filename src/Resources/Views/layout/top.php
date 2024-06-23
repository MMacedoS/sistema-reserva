<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?=APP_NAME?></title>
        <link rel="shortcut icon" href="<?=URL_PREFIX?>/Public/assets/images/logo-dark-rm-bg.png"/>
            <!-- *************
                ************ CSS Files *************
            ************* -->
        <!-- Icomoon Font Icons css -->
        <link rel="stylesheet" href="<?=URL_PREFIX?>/Public/assets/fonts/icomoon/style.css"/>

        <!-- Main CSS -->
        <link rel="stylesheet" href="<?=URL_PREFIX?>/Public/assets/css/main.min.css" />
        <link rel="stylesheet" href="<?=URL_PREFIX?>/Public/css/app.css" />
        <!-- *************
                ************ Vendor Css Files *************
            ************ -->

        <!-- Scrollbar CSS -->
        <link rel="stylesheet" href="<?=URL_PREFIX?>/Public/assets/vendor/overlay-scroll/OverlayScrollbars.min.css" />

        <!-- Include stylesheet -->
        <!-- <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" /> -->

    </head>
    <body>

        <?php 
            require_once('app-header.php');
            require_once('navbar.php');
        ?>

        <div class="app-body">
            <div class="container">
    