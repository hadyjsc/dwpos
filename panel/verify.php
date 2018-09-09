<?php 
session_start();
require_once '../config/conn.php';
if (isset($_POST['login']) == "login") {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	if ($username == null || $password == null) {
		echo "Form tidak boleh kosong!";
	}else {
		$getUname = QB::table('admin')->where('username', '=', $username);
		$data = $getUname->first();
		if ($getUname->count() == 1) {
			if (password_verify($password, $data->password)) {
				 $_SESSION['id'] = $data->id;
				 $_SESSION['username'] = $data->username;
				 $_SESSION['nama'] = $data->namauser;
				 $_SESSION['foto'] = $data->photo;
				 $_SESSION['level'] = $data->level;
				echo "Berhasil!";
			} else {
			   echo 'Username dan Password Tidak Cocok';
			}
		}else {
			echo "Username Tidak Ditemukan";
		}
	}
}else {
	echo "Tidak Dapat Login";
}
?>