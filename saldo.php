<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'function.php';
$wallet = query("SELECT * from dana where id_user = $_SESSION[id] order by nama");
$flt_bulan = query("SELECT tgl, SUBSTRING(tgl, 1, 8) AS value, YEAR(tgl) AS tahun, MONTH(tgl) as bulan FROM riwayat where id_user = $_SESSION[id] GROUP by bulan, tahun ORDER BY tgl");

?>

<html>
    <head>
        <title>Informasi Saldo | Rustiz</title>
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
                        <form action="" method="POST" role="form">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-2 pr-1">
                                        <select id="bulan" name="bulan" type="text" class="form-control">
                                            <option value="0">Pilih Bulan</option>
                                            <?php 
                                                foreach ($flt_bulan as $key ) {
                                                    $nb = bulan($key["bulan"]);
                                                    echo "<option value=".$key['value'].">".$nb." ".$key['tahun']."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <button id="filter" name="filter" type="submit" class="btn btn-primary mb-3">Proses</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="col-7">
                        <div class="box">
                            <div class="box-title text-center">
                                <h3>Informasi Saldo</h3>
                            </div>
                            <div class="box-body" style="overflow-x:auto;">
                                <div class="row">
                                    <div class="col">
                                        <?php  
                                            if (isset($_POST["filter"]) == false || $_POST["bulan"] == 0) {
                                                $bln = "Semua";
                                            } else {
                                                $key = substr($_POST["bulan"],-3,2);
                                                $bln = bulan($key) . " " . substr($_POST["bulan"],0,4);
                                            }
                                        ?>
                                        <b>Menampilkan hasil untuk :</b> <br>
                                        Bulan : <?= $bln ?> <br><br>
                                    </div>
                                </div>
                                <table id="saldo" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Sumber Dana</th>
                                            <th>Pemasukan</th>
                                            <th>Pengeluaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $i = 1;
                                            $masuk = 0;
                                            $keluar = 0; 
                                        ?>
                                        <?php foreach ($wallet as $row) : ?>
                                            <tr class="text-center">
                                                <td><?= $i ?></td>
                                                <td style="text-align: left;"><?= $row["nama"]; ?></td>
                                                <script>
                                                    function ribuan (x) {
                                                        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
                                                    }
                                                </script>
                                                <?php 
                                                    if (isset($_POST["filter"]) == false || $_POST["bulan"] == 0) {
                                                        $data = query("SELECT * from riwayat where id_dana = $row[id_dana] and id_user = $_SESSION[id]");
                                                    } else {
                                                        $key = $_POST["bulan"];
                                                        $data = query("SELECT * from riwayat where id_dana = $row[id_dana] and id_user = $_SESSION[id] and tgl like '$key%'");
                                                    }
                                                    
                                                    $dana_masuk = 0;
                                                    $dana_keluar = 0;

                                                    foreach ($data as $row) {
                                                        if ($row["ket"] == "Pemasukan") {
                                                            $nominal = $row["nom"];
                                                            $dana_masuk += $nominal;
                                                        } else {
                                                            $nominal = $row["nom"];
                                                            $dana_keluar += $nominal;
                                                        }
                                                    }
                                                    echo "<td style='text-align: right;'> Rp <script> document.write(ribuan(".$dana_masuk.")); </script>,00 </td>";
                                                    echo "<td style='text-align: right;'> Rp <script> document.write(ribuan(".$dana_keluar.")); </script>,00 </td>";
                                                    
                                                    $masuk += $dana_masuk;
                                                    $keluar += $dana_keluar;
                                                ?>
                                            </tr>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                        <tr class="text-center">
                                            <td colspan=2><b>Total</b></td>
                                            <td style='text-align: right;'><b> Rp <script> document.write(ribuan(<?=$masuk?>)); </script>,00 </b></td>
                                            <td style='text-align: right;'><b> Rp <script> document.write(ribuan(<?=$keluar?>)); </script>,00 </b></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <button onclick="exportTableToExcel('saldo', 'informasi-saldo')" class="btn btn-info mt-4"><i class="bi bi-box-arrow-up-right"></i> Ekspor</button>
                                </div>
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
        <script>
            function exportTableToExcel(tableID, filename = ''){
                var downloadLink;
                var dataType = 'application/vnd.ms-excel';
                var tableSelect = document.getElementById(tableID);
                var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
                
                // Specify file name
                filename = filename?filename+'.xls':'excel_data.xls';
                
                // Create download link element
                downloadLink = document.createElement("a");
                
                document.body.appendChild(downloadLink);
                
                if(navigator.msSaveOrOpenBlob){
                    var blob = new Blob(['\ufeff', tableHTML], {
                        type: dataType
                    });
                    navigator.msSaveOrOpenBlob( blob, filename);
                }else{
                    // Create a link to the file
                    downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
                
                    // Setting the file name
                    downloadLink.download = filename;
                    
                    //triggering the function
                    downloadLink.click();
                }
            }                  
        </script>
        <script src="assets/js/main.js" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>