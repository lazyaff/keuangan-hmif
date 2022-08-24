<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'function.php';
$data = query("SELECT * FROM `pengguna` where id_user = $_SESSION[id]")[0];

if (isset($_POST["update-pass"])) {
    if (edit_pass($_POST) > 0) {
        echo "<script> 
                alert('Kata sandi berhasil diperbarui!');
                document.location.href = 'profil.php';
            </script>";
    } else {
        echo "<script> 
                alert('Kata sandi tidak berhasil diperbarui!');
            </script>";
    }
}

if (isset($_POST["update"])) {
    if (edit_profil($_POST) > 0) {
        echo "<script> 
                alert('Data berhasil diperbarui!');
                document.location.href = 'profil.php';
            </script>";
        exit;
    } else {
        echo "<script> 
                alert('Data tidak berhasil diperbarui!');
            </script>";
    }
}

if (isset($_POST["logout"])) {
    session_start();
    $_SESSION = [];
    session_unset();
    session_destroy();

    header("Location: index.php");
    exit;
}

?>

<html>

<head>
    <title>Profil Pengguna | Rustiz</title>
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
                <div class="col-8">
                    <div class="box">
                        <div class="box-body">
                            <div class="row d-flex">
                                <div class="col-lg-3 text-center mt-4">
                                    <img src="assets/img/profil.jpg" style="height:250px; vertical-align: middle;">
                                </div>
                                <div class="col mt-4">
                                    <h3 class="text-center">Profil Pengguna</h3>
                                    <table class="table mt-4">
                                        <tbody>
                                            <tr>
                                                <td>User ID</td>
                                                <td class="text-center">:</td>
                                                <td><?= $data["id_user"] ?></td>
                                            </tr>
                                            <tr>
                                                <td>Username</td>
                                                <td class="text-center">:</td>
                                                <td><?= $data["username"] ?></td>
                                            </tr>
                                            <tr>
                                                <td>Nama Pengguna</td>
                                                <td class="text-center">:</td>
                                                <td><?= $data["nama"] ?></td>
                                            </tr>
                                            <tr>
                                                <td>Kata Sandi</td>
                                                <td class="text-center">:</td>
                                                <td><a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#edit-pass">Ubah</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <a href="#" class="btn btn-info mb-3" data-toggle="modal" data-target="#edit"><i class="bi bi-pencil-square"></i> Perbarui</a>
                                <form method="post"><button name="logout" id="logout" type="submit" class="btn btn-danger"><i class="bi bi-box-arrow-left"></i> Keluar</button></form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Edit Password -->
            <div class="modal fade" id="edit-pass">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form name="form" method="post" autocomplete="off">
                            <div class="modal-header justify-content-center">
                                <h2>Ubah Kata Sandi</h2>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Kata Sandi Lama</label>
                                    <input type="password" class="form-control mb-2 mr-sm-2" id="old" name="old" required>
                                </div>
                                <div class="form-group">
                                    <label>Kata Sandi Baru</label>
                                    <input type="password" class="form-control mb-2 mr-sm-2" id="new" name="new" required>
                                </div>
                                <div class="form-group">
                                    <label>Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" class="form-control mb-2 mr-sm-2" id="conf" name="conf" required>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="submit" class="btn btn-info" id="update-pass" name="update-pass">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal Edit -->
            <div class="modal fade" id="edit">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form name="form" method="post" autocomplete="off">
                            <div class="modal-header justify-content-center">
                                <h2>Perbarui Data</h2>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>User ID</label>
                                    <input type="text" class="form-control mb-2 mr-sm-2" value="<?= $data["id_user"] ?>" id="id" name="id" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control mb-2 mr-sm-2" value="<?= $data["username"] ?>" id="username" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label>Nama Pengguna</label>
                                    <input type="text" class="form-control mb-2 mr-sm-2" value="<?= $data["nama"] ?>" id="nama" name="nama" required>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="submit" class="btn btn-info" id="update" name="update">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </form>
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
                <script>
                    document.write(new Date().getFullYear())
                </script>
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