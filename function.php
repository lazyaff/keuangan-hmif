<?php

// koneksi ke database
$conn = mysqli_connect("localhost", "root", "qwerty", "rustiz");
$id_dn = 0;

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}


function tambah_dana($data) {
    global $conn;
    global $id_dn;

    // tes id
    $id_dn = rand(1000,9999);
    $id_test = $id_dn;
    $dt = mysqli_query($conn, "SELECT * FROM `dana` where id_dana = $id_test and id_user = $_SESSION[id]");
    function cek($db, $data) {
        global $conn;
        global $id_dn;
        if($db->num_rows > 0) {
            $id_dn = rand(1000,9999);
            $id_test = $id_dn;
            $dt = mysqli_query($conn, "SELECT * FROM `dana` where id_dana = $id_test and id_user = $_SESSION[id]");
            cek($dt, $id_test);     
        }
    }
    cek($dt, $id_test);

    // import data
    $id = $id_dn;
    $nama = $data["dana"];
    $user_id = $_SESSION["id"];
    mysqli_query($conn, "INSERT INTO dana VALUES('$id', '$nama', '$user_id')");
    
    return mysqli_affected_rows($conn);
}

function hapus_dana($data) {
    global $conn;
    mysqli_query($conn, "DELETE FROM dana WHERE id_dana = $data and id_user = $_SESSION[id]");
    return mysqli_affected_rows($conn);
}

function edit_dana($data) {
    global $conn;
    // import data
    $id_dana = $data["id"];
    $nama_dana = $data["nama_dana"];
    $user_id = $_SESSION["id"];

    // update data
    mysqli_query($conn, "UPDATE `dana` set nama='$nama_dana' where id_dana = $id_dana and id_user = $user_id");

    return mysqli_affected_rows($conn);
}

function tambah_transaksi($data) {
    global $conn;
    global $id_dn;

    // tes id
    $id_dn = rand(10000,99999);
    $id_test = $id_dn;
    $dt = mysqli_query($conn, "SELECT * FROM `riwayat` where id_hst = $id_test and id_user = $_SESSION[id]");
    function cek($db, $data) {
        global $conn;
        global $id_dn;
        if($db->num_rows > 0) {
            $id_dn = rand(10000,99999);
            $id_test = $id_dn;
            $dt = mysqli_query($conn, "SELECT * FROM `riwayat` where id_hst = $id_test and id_user = $_SESSION[id]");
            cek($dt, $id_test);     
        }
    }
    cek($dt, $id_test);

    // import data
    $id = $id_dn;
    $tgl = $data["tgl"];
    $wkt = $data["wkt"];
    $dtl = $data["dtl"];
    $nom = $data["nom"];
    $ket = $data["ket"];
    $id_dana = $data["id_dana"];
    $user_id = $_SESSION["id"];
    mysqli_query($conn, "INSERT INTO riwayat VALUES('$id', '$tgl', '$wkt', '$dtl', '$ket', '$nom', '$id_dana', '$user_id')");
    
    return mysqli_affected_rows($conn);
}

function hapus_riwayat($data) {
    global $conn;
    mysqli_query($conn, "DELETE FROM riwayat WHERE id_hst = $data and id_user = $_SESSION[id]");
    return mysqli_affected_rows($conn);
}

function edit_transaksi($data) {
    global $conn;
    // import data
    $id_hst = $data["id_hst"];
    $tgl = $data["tgl"];
    $wkt = $data["wkt"];
    $dtl = $data["dtl"];
    $nom = $data["nom"];
    $ket = $data["ket"];
    $id_dana = $data["id_dana"];

    // update data
    mysqli_query($conn, "UPDATE `riwayat` set tgl='$tgl', wkt='$wkt', dtl='$dtl', nom='$nom', ket='$ket', id_dana='$id_dana' where id_hst=$id_hst and id_user = $_SESSION[id]");

    return mysqli_affected_rows($conn);
}

function bulan ($key) {
    if ($key == 1) {
        $nb = "Januari";
    } elseif ($key == 2) {
        $nb = "Februari";
    } elseif ($key == 3) {
        $nb = "Maret";
    } elseif ($key == 4) {
        $nb = "April";
    } elseif ($key == 5) {
        $nb = "Mei";
    } elseif ($key == 6) {
        $nb = "Juni";
    } elseif ($key == 7) {
        $nb = "Juli";
    } elseif ($key == 8) {
        $nb = "Agustus";
    } elseif ($key == 9) {
        $nb = "September";
    } elseif ($key == 10) {
        $nb = "Oktober";
    } elseif ($key == 11) {
        $nb = "November";
    } else {
        $nb = "Desember";
    }

    return $nb;
}

function filter($bln, $dn) {
    if ($bln == 0 && $dn == 0) {
        $query = "SELECT *, SUBSTRING(wkt, 1, 5) AS waktu 
                FROM riwayat 
                join dana on riwayat.id_dana = dana.id_dana 
                where riwayat.id_user = $_SESSION[id]
                order by tgl, wkt
            ";
    } elseif ($bln == 0 && $dn != 0) {
        $query = "SELECT *, SUBSTRING(wkt, 1, 5) AS waktu 
                FROM riwayat 
                join dana on riwayat.id_dana = dana.id_dana 
                where riwayat.id_dana = $dn and riwayat.id_user = $_SESSION[id]
                order by tgl, wkt
            ";
    } elseif ($bln != 0 && $dn == 0) {
        $query = "SELECT *, SUBSTRING(wkt, 1, 5) AS waktu 
                FROM riwayat 
                join dana on riwayat.id_dana = dana.id_dana 
                where tgl like '$bln%' and riwayat.id_user = $_SESSION[id]
                order by tgl, wkt
            ";
    } else {
        $query = "SELECT *, SUBSTRING(wkt, 1, 5) AS waktu 
                FROM riwayat 
                join dana on riwayat.id_dana = dana.id_dana 
                where riwayat.id_dana = $dn and tgl like '$bln%' and riwayat.id_user = $_SESSION[id]
                order by tgl, wkt
            ";
    }
    return query($query);
}

function edit_pass($data) {
    global $conn;

    $old = $data["old"];
    $new = $data["new"];
    $conf = $data["conf"];

    $result = mysqli_query($conn, "SELECT * FROM pengguna WHERE id_user = $_SESSION[id]");
    $row = mysqli_fetch_assoc($result);

    // cek password lama
    if (password_verify($old, $row["sandi"])) {
        // cek kesesuaian password
        if ($new == $conf) {
            // enkripsi password
            $pass = password_hash($new, PASSWORD_DEFAULT);
            // ubah password
            $query = "UPDATE pengguna SET
                            sandi = '$pass'
                            where id_user = $_SESSION[id]
                        ";

            mysqli_query($conn, $query);
        } else {
            echo "
			<script>
				alert('Kata sandi gagal diubah karena kata sandi baru tidak cocok!');
                document.location.href = 'profil.php';
			</script>
		    ";
        }
    } else {
        echo "
        <script>
            alert('Kata sandi gagal diubah karena anda salah memasukkan kata sandi lama!');
            document.location.href = 'profil.php';
        </script>
        ";
    }

    return mysqli_affected_rows($conn);
}

function edit_profil($data) {
    global $conn;
    // import data
    $id_user = $data["id"];
    $username = $data["username"];
    $nama = $data["nama"];
    $comp = mysqli_query($conn, "SELECT * FROM `pengguna` where username = $username");

    // cek username
    if ($comp == true){
        // update data
        mysqli_query($conn, "UPDATE `pengguna` set username='$username', nama='$nama' where id_user = $id_user");
    } else {
        echo "
        <script>
            alert('Data gagal diperbarui karena username sudah terdaftar!');
            document.location.href = 'profil.php';
        </script>
        ";
    }

    return mysqli_affected_rows($conn);
}

?>