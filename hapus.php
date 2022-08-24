<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

require 'function.php';

$id = $_GET["id"];

if ($id >= 1000 && $id < 10000) {
	if (hapus_dana($id) > 0) {
		echo "
			<script>
				alert('Data berhasil dihapus!');
				document.location.href = 'dana.php';
			</script>
		";
	} else {
		echo "
			<script>
				alert('Data gagal dihapus!');
				document.location.href = 'dana.php';
			</script>
		";
	}
}

if ($id >= 10000 && $id < 100000) {
	if (hapus_riwayat($id) > 0) {
		echo "
			<script>
				alert('Data berhasil dihapus!');
				document.location.href = 'transaksi.php';
			</script>
		";
	} else {
		echo "
			<script>
				alert('Data gagal dihapus!');
				document.location.href = 'transaksi.php';
			</script>
		";
	}
}


?>