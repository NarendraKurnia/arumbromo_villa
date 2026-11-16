<?php
include 'config/app.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INFORMASI UP3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <!-- icon -->
    <link rel="icon" href="images/Logo_PLN.png">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.0/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@master/devicon.min.css">
    <!-- css custom -->
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/responsive.css">
    <script src="script.js" defer></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light navbar-top">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item" style="margin-left: 30px;"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Pelanggan</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button">
                        Profil
                    </a>
                    <div class="dropdown-menu p-4">
                        <div class="row">
                            <p class="dropdown-header" style="text-align: center;">PROFIL</p>
                            <!-- Kolom 1 -->
                            <div class="col-md-3">
                                <a class="dropdown-item" href="#">UID Jatim</a>
                                <a class="dropdown-item" href="#">UP3 Surabaya Utara</a>
                                <a class="dropdown-item" href="#">UP3 Surabaya Selatan</a>
                                <a class="dropdown-item" href="up3sbb.php">UP3 Surabaya Barat</a>
                            </div>
                            <!-- Kolom 2 -->
                            <div class="col-md-3">
                                <a class="dropdown-item" href="#">UP3 Mojokerto</a>
                                <a class="dropdown-item" href="#">UP3 Gresik</a>
                                <a class="dropdown-item" href="#">UP3 Madura</a>
                                <a class="dropdown-item" href="#">UP3 Banyuwangi</a>
                            </div>
                            <!-- Kolom 3 -->
                            <div class="col-md-3">
                                <a class="dropdown-item" href="#">UP2D</a>
                                <a class="dropdown-item" href="#">UP3 Malang</a>
                                <a class="dropdown-item" href="#">UP3 Sidoarjo</a>
                                <a class="dropdown-item" href="#">UP3 Madiun</a>
                            </div>
                            <!-- Kolom 4 -->
                            <div class="col-md-3">
                                <a class="dropdown-item" href="#">UP3 Pasuruan</a>
                                <a class="dropdown-item" href="#">UP3 Bojonegoro</a>
                                <a class="dropdown-item" href="#">UP3 Kediri</a>
                                <a class="dropdown-item" href="#">UP3 Ponorogo</a>
                                <a class="dropdown-item" href="#">UP3 Situbondo</a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="#">Karir</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Kontak</a></li>
                <li class="nav-item">
                    <form class="navbar-left" role="search" method="get" action="http://web.pln.co.id//search">
                        <div class="container row">
                            <div class="form-group col-md-10 colsm-10 col-xs-10">
                                <input type="text" name="q" class="form-control" placeholder="Cari...">
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2" style="margin-left: -15px;">
                                <button type="submit" class="btn btn-default">
                                <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
        <div class="pln-logo ms-3">
            <img src="images/ICON-PLN.png" alt="PLN Logo">
        </div>
    </div>
</nav>

