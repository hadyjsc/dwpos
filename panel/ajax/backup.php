<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
include_once '../../config/conn.php';
include_once '../../config/backup.php';

$backup = new BackupPos($config["host"],$config["database"],$config["username"],$config["password"]);
if ($_POST["action"] == "backup") {
	$date = date('Y_m_d_H_i_s');
	$backup->sql($date);
	$backup->csv($date);
	$backup->xlsx($date);

	$res = array("code"=>1,"dir"=>"fbackup/".$date);
	echo json_encode($res);

}else {
	echo "Anda Tidak Memiliki Akses Ke Halaman Ini.";
}

?>