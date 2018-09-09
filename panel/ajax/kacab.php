<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
include_once '../../config/conn.php';


if ($_POST["action"] == "add") {
	$result = array();
	$nama = $_POST["nama"];
	$deskripsi = $_POST["deskripsi"];
	$alamat = $_POST["alamat"];
	$lat = $_POST["lat"];
	$lng = $_POST["lng"];

	$data = array(
		'nama'=>$nama,
		'deskripsi'=>$deskripsi,
		'alamat'=>$alamat,
		'lat'=>$lat,
		'lng'=>$lng
	);

	$insert = QB::table('kacab')->insert($data);
	if ($insert) {
		$result["response"] = array("code"=>"1");
	}else {
		$result["response"] = array("code"=>"0");
	}
	echo json_encode($result);
}
elseif ($_POST["action"] == "edit") {
	$result = array();
	$nama = $_POST["nama"];
	$deskripsi = $_POST["deskripsi"];
	$alamat = $_POST["alamat"];
	$lat = $_POST["lat"];
	$lng = $_POST["lng"];

	$data = array(
		'nama'=>$nama,
		'deskripsi'=>$deskripsi,
		'alamat'=>$alamat,
		'lat'=>$lat,
		'lng'=>$lng
	);

	$update = QB::table('kacab')->update($data);
	if ($update) {
		$result["response"] = array("code"=>"1");
	}else {
		$result["response"] = array("code"=>"0");
	}
	echo json_encode($result);
}
elseif ($_POST["action"] == "delete") {
	$result = array();
	$id = $_POST["id"];

	$hapus = QB::table('kacab')->where('id', '=', $id)->delete();
	if ($hapus) {
		$result["response"] = array("code"=>"1");
	}else {
		$result["response"] = array("code"=>"0");
	}
	echo json_encode($result);
}
else {
	echo "Ada tidak dapat mengakses halaman ini";
}
?>