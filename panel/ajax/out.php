<?php
if (isset($_POST['action']) == 'keluar') {
	session_start();
	session_destroy();
	echo "OK";	
}else {
	echo "Anda Tidak Memiliki Akses";
}

?>