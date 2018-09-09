<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
include_once '../../config/conn.php';

$decode = json_decode($_POST["data"]);
$pkProvinsi = "";
$pkJenis = "";
$pkLayanan = "";
$pkWaktu = "";
$pkPengaduan = "";
$same = "";
$c = 1;

$queryIn = array();
$querySame = array();
$kolom = "";
$tabel = "";

foreach ($decode as $key => $value) {
	if (!empty($value->provinsi)) {
		$in = "";
		foreach ($value->provinsi as $v) {
			$in .= $v.",";
		}
		$c++;
		$pkProvinsi = "fact_keluhan.pk_propinsi IN (".substr_replace($in, "", -1).")";
		$kolom .= "dim_propinsi.nama_propinsi,";
		$tabel .= "dim_propinsi,";
		array_push($queryIn, $pkProvinsi);
		$same = "dim_propinsi.pk_propinsi = fact_keluhan.pk_propinsi";
		array_push($querySame, $same);
	}

	if (!empty($value->jenis)) {
		$in = "";
		foreach ($value->jenis as $v) {
			$in .= $v.",";
		}
		$c+1;
		$pkJenis = "fact_keluhan.pk_jeniskiriman IN(".substr_replace($in, "", -1).")";
		$kolom .= "dim_jeniskiriman.nama_jeniskiriman,";
		$tabel .= "dim_jeniskiriman,";
		array_push($queryIn, $pkJenis);
		$same = "dim_jeniskiriman.pk_jeniskiriman = fact_keluhan.pk_jeniskiriman";
		array_push($querySame, $same);
	}

	if (!empty($value->layanan)) {
		$in = "";
		foreach ($value->layanan as $v) {
			$in .= $v.",";
		}
		$c+1;
		$pkLayanan = "fact_keluhan.pk_layanan IN(".substr_replace($in, "", -1).")";
		$kolom .= "dim_layanan.nama_layanan,";
		$tabel .= "dim_layanan,";
		array_push($queryIn, $pkLayanan);
		$same = "dim_layanan.pk_layanan = fact_keluhan.pk_layanan";
		array_push($querySame, $same);
	}

	if (!empty($value->waktu)) {
		$getWaktu = QB::table("dim_waktu")->where("tanggal_kirim", "=",$value->waktu)->get();
		$pkWaktu = "fact_keluhan.pk_waktu = '".$getWaktu[0]->pk_waktu."'";
		$kolom .= "dim_waktu.tanggal_kirim,";
		$tabel .= "dim_waktu,";
		$c+1;
		array_push($queryIn, $pkWaktu);
		$same = "dim_waktu.pk_waktu = fact_keluhan.pk_waktu";
		array_push($querySame, $same);
	}

	if (!empty($value->pengaduan)) {
		$in = "";
		foreach ($value->pengaduan as $v) {
			$in .= $v.",";
		}
		$c+1;
		$pkPengaduan = "fact_keluhan.pk_pengaduan IN(".substr_replace($in, "", -1).")";
		$kolom .= "dim_pengaduan.nama_pengaduan,";
		$tabel .= "dim_pengaduan,";
		array_push($queryIn, $pkPengaduan);
		$same = "dim_pengaduan.pk_pengaduan = fact_keluhan.pk_pengaduan";
		array_push($querySame, $same);
	}
}

$queryWhere = "WHERE ";
foreach ($queryIn as $key => $value) {
	$queryWhere .= $value." AND ";
}
$queryWhere = substr($queryWhere, 0, -4);

$queryWhereSame = "";
foreach ($querySame as $key => $value) {
	$queryWhereSame .= $value." AND ";
}
$queryWhereSame = substr($queryWhereSame, 0, -4);

$getData = QB::query("SELECT * FROM `fact_keluhan` ".$queryWhere);
$resData = $getData->get();

$queryWhereSum = "SELECT ".$kolom." SUM(total_keluhan) as 'sum' FROM fact_keluhan, ". substr_replace($tabel, "", -1) ." ".$queryWhere." AND ".$queryWhereSame." GROUP BY  ".substr_replace($kolom, "", -1);	

// echo $queryWhereSum;
$getSum = QB::query($queryWhereSum);
$resSum = $getSum->get();

// $data = json_encode($resData);
$data = json_encode($resSum);
echo $data;

?>