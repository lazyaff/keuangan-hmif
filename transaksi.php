<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'function.php';
$data = query("SELECT *, SUBSTRING(wkt, 1, 5) AS waktu FROM riwayat join dana on riwayat.id_dana = dana.id_dana where riwayat.id_user = $_SESSION[id] order by tgl, wkt");
$flt_bulan = query("SELECT tgl, SUBSTRING(tgl, 1, 8) AS value, YEAR(tgl) AS tahun, MONTH(tgl) as bulan FROM riwayat where id_user = $_SESSION[id] GROUP by bulan, tahun ORDER BY tgl");
$wallet = query("SELECT * from dana where id_user = $_SESSION[id] order by nama");

if (isset($_POST["filter"])) {
    $data = filter($_POST["bulan"], $_POST["dana"]);
}

?>

<html>

<head>
    <title>Kelola Riwayat Transaksi | Rustiz</title>
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
                <div class="col-11">
                    <form action="" method="POST" role="form">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-2 pr-1">
                                    <select id="bulan" name="bulan" type="text" class="form-control">
                                        <option value="0">Pilih Bulan</option>
                                        <?php
                                        foreach ($flt_bulan as $key) {
                                            $nb = bulan($key["bulan"]);
                                            echo "<option value=" . $key['value'] . ">" . $nb . " " . $key['tahun'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-2 pr-1">
                                    <select id="dana" name="dana" type="text" class="form-control">
                                        <option value="0">Sumber Dana</option>
                                        <?php
                                        foreach ($wallet as $key) {
                                            echo "<option value=" . $key['id_dana'] . ">" . $key['nama'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-1">
                                    <button id="filter" name="filter" type="submit" class="btn btn-primary">Proses</button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-1">
                    <a class="btn btn-primary" href="tambah.php">Tambah</a>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-11">
                    <div class="box">
                        <div class="box-title text-center">
                            <h3>Riwayat Transaksi</h3>
                        </div>
                        <div class="box-body" style="overflow-x:auto;">
                            <div class="row">
                                <div class="col">
                                    <?php
                                    if (isset($_POST["filter"]) == false || $_POST["bulan"] == 0) {
                                        $bln = "Semua";
                                    } else {
                                        $key = substr($_POST["bulan"], -3, 2);
                                        $bln = bulan($key) . " " . substr($_POST["bulan"], 0, 4);
                                    }

                                    if (isset($_POST["filter"]) == false || $_POST["dana"] == 0) {
                                        $dn = "Semua";
                                    } else {
                                        $key = $_POST["dana"];
                                        $arr =  query("SELECT * from dana where id_dana = $key and id_user = $_SESSION[id]")[0];
                                        $dn = $arr["nama"];
                                    }
                                    ?>
                                    <b>Menampilkan hasil untuk : </b> <br>
                                    Bulan &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp: <?= $bln ?> <br>
                                    Sumber Dana : <?= $dn ?> <br><br>
                                </div>
                            </div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Detail</th>
                                        <th>Keterangan</th>
                                        <th>Nominal</th>
                                        <th>Sumber Dana</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $row) : ?>
                                        <tr class="text-center">
                                            <td><?= $row["id_hst"]; ?></td>
                                            <td><?= $row["tgl"]; ?></td>
                                            <td><?= $row["waktu"]; ?></td>
                                            <td style="text-align: left;"><?= $row["dtl"]; ?></td>
                                            <td><?= $row["ket"]; ?></td>
                                            <td>
                                                Rp <script>
                                                    function ribuan(x) {
                                                        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
                                                    }
                                                    document.write(ribuan(<?= $row["nom"] ?>));
                                                </script>,00
                                            </td>
                                            <td><?= $row["nama"]; ?></td>
                                            <td>
                                                <a href="edit.php?id=<?= $row["id_hst"]; ?>" class="btn btn-info btn-sm"><i class="bi bi-pencil-square"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#hapus-<?= $row["id_hst"]; ?>"><i class="bi bi-trash"></i></a>
                                            </td>
                                        </tr>
                                        <!-- Modal Hapus -->
                                        <div class="modal fade" id="hapus-<?= $row["id_hst"]; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header justify-content-center">
                                                        <div class="modal-icon">
                                                            <i class="bi bi-x"></i>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <p>Apakah anda yakin ingin menghapus data?</p>ID = <?= $row["id_hst"]; ?>
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <a href="hapus.php?id=<?= $row["id_hst"]; ?>" class="btn btn-info">Yakin</a>
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