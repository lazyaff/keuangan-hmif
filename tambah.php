<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'function.php';
$data = query("SELECT * FROM dana where id_user = $_SESSION[id] order by nama");

if (isset($_POST["save"])) {
    if (tambah_transaksi($_POST) > 0) {
        echo "<script> 
                alert('Data berhasil ditambahkan!');
            </script>";
        header("Location: transaksi.php");
        exit;
    } else {
        echo mysqli_error($conn);
    }
}

?>

<html>
    <head>
        <title>Transaksi Baru | Rustiz</title>
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="assets/img/logo.png">

        <!-- CSS Files -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
        <link href="assets/css/main.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <!-- Header -->
        <header>
            <nav class="navbar navbar-expand-lg">
            <div class="container">
                    <a class="navbar-brand title" href="riwayat.php">Rustiz</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ini-nav" aria-controls="ini-nav" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="bi bi-list"></i>
                    </button>
        
                    <div class="collapse navbar-collapse" id="ini-nav">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item"><a href="riwayat.php" class="nav-link">Riwayat</a></li>
                            <li class="nav-item"><a href="saldo.php" class="nav-link">Saldo</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Perbarui Data </a>
                                <div class="dropdown-menu" aria-labelledby="dropdown04">
                                    <a class="dropdown-item" href="transaksi.php">Transaksi</a>
                                    <a class="dropdown-item" href="dana.php">Sumber Dana</a>
                                </div>
                            </li>
                            <li class="nav-item"><a href="profil.php" class="nav-link">Profil</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <!-- Main Content -->
        <div class="main" style="margin-top: 20px; margin-bottom: 20px; min-height: 400px;">
            <div class="container-fluid">
                <div class="row d-flex justify-content-center">
                    <div class="col-6">
                        <div class="box">
                            <div class="box-title text-center">
                                <h3>Transaksi Baru</h3>
                            </div>
                            <div class="box-body">
                                <form name="form" method="post" autocomplete="off">
                                    <div class="row">
                                        <div class="col-6 pr-1">
                                            <div class="form-group">
                                                <label>Tanggal</label>
                                                <input id="tgl" name="tgl" required type="date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-6 pl-1">
                                            <div class="form-group">
                                                <label>Waktu</label>
                                                <input id="wkt" name="wkt" required type="time" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 pr-1">
                                            <div class="form-group">
                                                <label>Detail Transaksi</label>
                                                <input id="dtl" name="dtl" required type="text" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 pr-1">
                                            <div class="form-group">
                                                <label>Nominal</label>
                                                <input id="nom" name="nom" required type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-6 pl-1">
                                            <div class="form-group">
                                                <label>Keterangan</label>
                                                <select id="ket" name="ket" required type="text" class="form-control">
                                                    <option selected disabled hidden value="">--- Pilih ---</option>
                                                    <option value="Pemasukan">Pemasukan</option>
                                                    <option value="Pengeluaran">Pengeluaran</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 pr-1">
                                            <div class="form-group">
                                                <label>Sumber Dana</label>
                                                <select id="id_dana" name="id_dana" required type="text" class="form-control">
                                                    <option selected disabled hidden value="">--- Pilih ---</option>
                                                    <?php foreach ($data as $row) : ?>
                                                        <option value=<?= $row["id_dana"] ?>><?= $row["nama"] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>                                                  
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info mt-4" id="save" name="save">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <footer>
            <div class="container text-center">
                <div class="social-links">
                    <a href="mailto:faizalamri15@gmail.com"><i class="bi bi-envelope"></i></a>
                    <a href="https://www.instagram.com/faizamr_/"><i class="bi bi-instagram"></i></a>
                    <a href="https://github.com/lazyaff"><i class="bi bi-github"></i></a>
                    <a href="https://www.linkedin.com/mwlite/in/faizal-amri-47a2541ba"><i class="bi bi-linkedin"></i></a>
                </div>
                <div class="copyright">
                    &copy; 
                    <script>document.write(new Date().getFullYear()) </script> 
                    All rights reserved | <a href="https://github.com/lazyaff">lazyaf</a>
                </div>
            </div>
        </footer>
        
        <!-- JS Files -->
        <script src="assets/js/main.js" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>