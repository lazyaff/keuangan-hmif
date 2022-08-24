<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'function.php';
$data = query("SELECT * FROM `dana` where id_user = $_SESSION[id] order by nama");

if (isset($_POST["save"])) {
    if (tambah_dana($_POST) > 0) {
        echo "<script> 
                alert('Data berhasil ditambahkan!');
            </script>";
        header("Location: dana.php");
        exit;
    } else {
        echo mysqli_error($conn);
    }
}

if (isset($_POST["update"])) {
    if (edit_dana($_POST) > 0) {
        echo "<script> 
                alert('Data berhasil diperbarui!');
            </script>";
        header("Location: dana.php");
        exit;
    } else {
        echo "<script> 
                alert('Data tidak berhasil diperbarui!');
            </script>";
        echo mysqli_error($conn);
    }
}

?>

<html>
    <head>
        <title>Kelola Sumber Dana | Rustiz</title>
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
                <div class="row">
                    <div class="col">
                        <form class="form-inline float-right" method="post" autocomplete="off">
                            <input type="text" class="form-control mb-2 mr-sm-2" placeholder="Sumber Dana Baru" id="dana" name="dana" required>
                            <button type="submit" class="btn btn-primary mb-2" id="save" name="save">Simpan</button>
                        </form>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-title text-center">
                                <h3>Sumber Dana</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama Sumber Dana</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data as $row) : ?>
                                            <tr class="text-center">
                                                <td><?= $row["id_dana"]; ?></td>
                                                <td><?= $row["nama"]; ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#edit-<?= $row["id_dana"]; ?>"><i class="bi bi-pencil-square"></i></a>
                                                    <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#hapus-<?= $row["id_dana"]; ?>"><i class="bi bi-trash"></i></a>
                                                </td>
                                            </tr>
                                            <!-- Modal Edit -->
                                            <div class="modal fade" id="edit-<?= $row["id_dana"]; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form name="form" method="post" autocomplete="off">
                                                            <div class="modal-header justify-content-center">
                                                                <h2>Perbarui Data</h2>
                                                            </div>
                                                            <div class="modal-body"> 
                                                                <div class="form-group">
                                                                    <label>ID Sumber Dana</label>
                                                                    <input type="text" class="form-control mb-2 mr-sm-2" value="<?= $row["id_dana"]; ?>" id="id" name="id" readonly>
                                                                </div>   
                                                                <div class="form-group">
                                                                    <label>Sumber Dana</label>
                                                                    <input type="text" class="form-control mb-2 mr-sm-2" value="<?= $row["nama"]; ?>" id="nama_dana" name="nama_dana" required>
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
                                            <!-- Modal Hapus -->
                                            <div class="modal fade" id="hapus-<?= $row["id_dana"]; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header justify-content-center">
                                                            <div class="modal-icon">
                                                                <i class="bi bi-x"></i>
                                                            </div>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <p>Apakah anda yakin ingin menghapus data?</p>ID = <?= $row["id_dana"]; ?>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <a href="hapus.php?id=<?= $row["id_dana"]; ?>" class="btn btn-info">Yakin</a>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <footer>
            <div class="text-center">
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