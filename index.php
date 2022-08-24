<?php
session_start();

require 'function.php';

if (isset($_SESSION["login"])) {
    header("Location: riwayat.php");
    exit;
}

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $pass = $_POST["pass"];
    // cek username
    $result = mysqli_query($conn, "SELECT * FROM pengguna WHERE username = '$username'");
    if (mysqli_num_rows($result) === 1) {
        // cek password
        $row = mysqli_fetch_assoc($result);
        if (password_verify($pass, $row["sandi"])) {
            // set session
            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["id_user"];
            // masuk ke halaman
            header("Location: riwayat.php");
            exit;
        } else {
            echo "
                <script>
                    alert('Kata sandi salah!');
                </script>
            ";
        }
    } else {
        echo "
			<script>
				alert('Username tidak terdaftar!');
			</script>
		";
    }
    $error = true;
}

?>

<html>
    <head>
        <title>Masuk | Rustiz</title>
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="assets/img/logo.png">

        <!-- CSS Files -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
        <link href="assets/css/main.css" rel="stylesheet" type="text/css">

        <style>
            body {
                background-image:url('assets/img/cover.jpg');
                background-position:center;
                background-size:cover;
            }
            ::placeholder { 
                color: rgba(255, 255, 255, 0.7)!important;
                font-size:18px!important;
            }
            .form-login {
                background-color: rgba(0,0,0,0.6);
                border-radius: 15px;
                border-color:white;
                border-width: 5px;
                color:white;
                box-shadow:0 1px 0 #cfcfcf;
                padding: 50px 30px;
                margin-top: 20px;
            }
            .form-content {
                margin: 25px 0px;
            }
            .form-control{
                background:transparent!important;
                color:white!important;
                font-size: 18px!important;
                border-radius: 10px;
            }
            .btn {
                border-radius: 10px;
                width: 125px;
            }
            h2 { 
                border:0 solid #fff; 
                border-bottom-width:1px;
                text-align: center;
                padding-bottom: 30px;
                margin-bottom: 50px;
            }
            .isi {
                margin: 60px 0px;
            }
        </style>
    </head>
    <body>
        <!-- Main Content -->
        <div class="container">
            <div class="row justify-content-center isi">
                <div class="col-md-offset-5 col-md-5 text-center">
                    <div class="form-login"></br>
                        <h2>Silahkan masuk untuk melanjutkan</h4>
                        <form method="post" autocomplete="off">
                            <input type="text" id="username" name="username" class="form-control form-content" placeholder="Username" required/>
                            <input type="password" id="pass" name="pass" class="form-control" placeholder="Password" required/>
                            <button type="submit" name="login" id="login" class="btn btn-info form-content">Masuk</button>
                        </form>
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