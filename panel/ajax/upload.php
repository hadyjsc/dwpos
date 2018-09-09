<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
set_time_limit(0);
include_once '../../config/conn.php';

$file = $_FILES["getfile"]["name"];
$file_temp = $_FILES["getfile"]["tmp_name"];

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$message = "";
$step = "extract";

$reader = new Xlsx();
$date = new NumberFormat();
$reader->setReadDataOnly(TRUE);
$spreadsheet = $reader->load($file_temp);

$worksheet = $spreadsheet->getActiveSheet();
$highestRow = $worksheet->getHighestRow();

$dataArray = array();
$dimJenisKiriman = array();
$dimKpkpc = array();
$dimLayanan = array();
$dimNoResi = array();
$dimPengaduan = array();
$dimPropinsi = array();
$dimWaktu = array();

//Step Extrak Data Excel
if ($step == "extract") {
	$id = 1;
	for ($row = 2; $row <= $highestRow; $row++) {
		$tanggal = $date->toFormattedString($worksheet->getCell("K".$row)->getValue(),"YYYY-MM-DD");
		$dataArray[] = array(
			'id' 						=> $id++,
			'kacab' 				=> $worksheet->getCell("B".$row)->getValue(),
			'alamat_kacab'	=> $worksheet->getCell("C".$row)->getValue(),
			'no_resi'				=> $worksheet->getCell("D".$row)->getValue(),
			'pengirim' 			=> $worksheet->getCell("E".$row)->getValue(),
			'penerima' 			=> $worksheet->getCell("F".$row)->getValue(),
			'prov_tujuan' 	=> $worksheet->getCell("G".$row)->getValue(),
			'kab_tujuan'		=> $worksheet->getCell("H".$row)->getValue(),
			'kec_tujuan'		=> $worksheet->getCell("I".$row)->getValue(),
			'kel_tujuan'		=> $worksheet->getCell("J".$row)->getValue(),
			'tgl_kirim'			=> $tanggal,
			'jenis'					=> $worksheet->getCell("L".$row)->getValue(),
			'layanan'				=> $worksheet->getCell("M".$row)->getValue(),
			'keluhan'				=> $worksheet->getCell("N".$row)->getValue()
		);
		
		}

		foreach ($dataArray as $key => $value) {
			//Dimensi Jenis Kiriman
			$dimJenisKiriman[str_replace(" ","",$value["jenis"])] = str_replace(" ","",$value["jenis"]);
			//Dimensi Kantor Cabang
			$dimKpkpc[$value["kacab"]] = $value["kacab"];
			//Dimensi Layanan
			$dimLayanan[$value["layanan"]] = $value["layanan"];
			//Dimensi Nomor Resi
			$dimNoResi[$value["no_resi"]] = array('resi'=>$value["no_resi"],'nama'=>$value["penerima"]);
			//Dimensi Pengaduan
			$dimPengaduan[$value["keluhan"]] = $value["keluhan"];
			//Dimensi Provinsi
			$dimPropinsi[$value["prov_tujuan"]] = $value["prov_tujuan"];
			//Dimensi Waktu
			$dimWaktu[$value["tgl_kirim"]] = $value["tgl_kirim"];
		}

		$dimJenisKiriman = array_values($dimJenisKiriman);
		$dimKpkpc = array_values($dimKpkpc);
		$dimLayanan = array_values($dimLayanan);
		$dimNoResi = array_values($dimNoResi);
		$dimPengaduan = array_values($dimPengaduan);
		$dimPropinsi = array_values($dimPropinsi);
		$dimWaktu = array_values($dimWaktu);
		$step = "transform";
		$message = "extract";
}
if ($step == "transform") {
	//Clear All Data In Database
	QB::query('TRUNCATE TABLE data_test');
	QB::table('dim_jeniskiriman')->delete();
	QB::table('dim_kpkpc')->delete();
	QB::table('dim_layanan')->delete();
	QB::table('dim_noresi')->delete();
	QB::table('dim_pengaduan')->delete();
	QB::table('dim_propinsi')->delete();
	QB::table('dim_waktu')->delete();
	//Insert Data Dimensi Jenis Kiriman
	foreach ($dimJenisKiriman as $key => $value) {
		$key++;
		$data = array('pk_jeniskiriman'=> $key,'nama_jeniskiriman'=>$value);
		QB::table('dim_jeniskiriman')->insert($data);
	}
	//Insert Data Dimensi Kantor Cabang
	foreach ($dimKpkpc as $key => $value) {
		$key++;
		$data = array('pk_KpKpc'=> $key,'nama_KpKpc'=>$value);
		QB::table('dim_kpkpc')->insert($data);	
	}
	//Insert Data Dimensi Layanan
	foreach ($dimLayanan as $key => $value) {
		$key++;
		$data = array('pk_layanan'=> $key,'nama_layanan'=>$value);
		QB::table('dim_layanan')->insert($data);	
	}
	//Insert Data Dimensi Nomor Resi
	foreach ($dimNoResi as $key => $value) {
		$key++;
		$data = array('pk_noresi'=> $key,'no_resi'=>$value['resi'],'nama_pengirim'=>$value['nama']);
		QB::table('dim_noresi')->insert($data);	
	}
	//Insert Data Dimensi Pengaduan
	foreach ($dimPengaduan as $key => $value) {
		$key++;
		$data = array('pk_pengaduan'=> $key,'nama_pengaduan'=>$value);
		QB::table('dim_pengaduan')->insert($data);	
	}
	//Insert Data Dimensi Provinsi
	foreach ($dimPropinsi as $key => $value) {
		$key++;
		$data = array('pk_propinsi'=> $key,'nama_propinsi'=>$value);
		QB::table('dim_propinsi')->insert($data);	
	}
	//Insert Data Dimensi Waktu
	foreach ($dimWaktu as $key => $value) {
		$key++;
		$data = array('pk_waktu'=> $key,'tanggal_kirim'=>$value);
		QB::table('dim_waktu')->insert($data);	
	}

	$dataArrayClean = array();
	$id = 1;
	for ($i=0; $i < count($dataArray); $i++) { 
		
		$getKacab = QB::table("dim_kpkpc")
								->where("nama_KpKpc","=",$dataArray[$i]["kacab"])
								->get();
		$getResi = QB::table("dim_noresi")
								->where("no_resi","=",$dataArray[$i]["no_resi"])
								->get();
		$getProv = QB::table("dim_propinsi")
								->where("nama_propinsi","=",$dataArray[$i]["prov_tujuan"])
								->get();
		$getTggl = QB::table("dim_waktu")
								->where("tanggal_kirim","=",$dataArray[$i]["tgl_kirim"])
								->get();
		$getJenis = QB::table("dim_jeniskiriman")
								->where("nama_jeniskiriman","=",$dataArray[$i]["jenis"])
								->get();
		$getLayanan = QB::table("dim_layanan")
								->where("nama_layanan","=",$dataArray[$i]["layanan"])
								->get();
		$getKeluhan = QB::table("dim_pengaduan")
								->where("nama_pengaduan","=",$dataArray[$i]["keluhan"])
								->get();

		$dataArrayClean = array(
			'id' 						=> $id++,
			'kacab' 				=> $getKacab[0]->pk_KpKpc,
			'alamat_kacab'	=> $dataArray[$i]["alamat_kacab"],
			'no_resi'				=> $getResi[0]->pk_noresi,
			'pengirim' 			=> $dataArray[$i]["pengirim"],
			'penerima' 			=> $dataArray[$i]["penerima"],
			'prov_tujuan' 	=> $getProv[0]->pk_propinsi,
			'kab_tujuan'		=> $dataArray[$i]["kab_tujuan"],
			'kec_tujuan'		=> $dataArray[$i]["kec_tujuan"],
			'kel_tujuan'		=> $dataArray[$i]["kel_tujuan"],
			'tgl_kirim'			=> $getTggl[0]->pk_waktu,
			'jenis'					=> $getJenis[0]->pk_jeniskiriman,
			'layanan'				=> $getLayanan[0]->pk_layanan,
			'keluhan'				=> $getKeluhan[0]->pk_pengaduan
		);

		QB::table("data_test")->insert($dataArrayClean);
	}
	
	$step = "load";
}

if ($step == "load") {

	//Get Dimensi Provinsi
	$getProp = QB::table("dim_propinsi");
	$resProp = $getProp->get();

	//Get Dimensi Jenis Kiriman
	$getJenis = QB::table("dim_jeniskiriman");
	$resJenis = $getJenis->get();

	//Get Dimensi Layanan
	$getLayanan = QB::table("dim_layanan");
	$resLayanan = $getLayanan->get();

	//Get Dimensi Waktu
	$getWaktu = QB::table("dim_waktu");
	$resWaktu = $getWaktu->get();

	//Get Dimensi Pengaduan
	$getPengaduan = QB::table("dim_pengaduan");
	$resPengaduan = $getPengaduan->get();

	$no = 1;
	foreach ($resProp as $kProp => $vProp) {
		foreach ($resJenis as $kJenis => $vJenis) {
			foreach ($resLayanan as $kLayanan => $vLayanan) {
				foreach ($resWaktu as $kWaktu => $vWaktu) {
					foreach ($resPengaduan as $kPengaduan => $vPengaduan) {
						$total = QB::query("SELECT COUNT(id) as c FROM `data_test` WHERE prov_tujuan =  $vProp->pk_propinsi AND jenis = $vJenis->pk_jeniskiriman AND layanan = $vLayanan->pk_layanan AND tgl_kirim = $vWaktu->pk_waktu AND keluhan = $vPengaduan->pk_pengaduan")->get();

						$data = array(
							'id' => $no++,
							'pk_propinsi' => $vProp->pk_propinsi,
							'pk_jeniskiriman' => $vJenis->pk_jeniskiriman,
							'pk_layanan' => $vLayanan->pk_layanan,
							'pk_waktu' => $vWaktu->pk_waktu,
							'pk_pengaduan' => $vPengaduan->pk_pengaduan,
							'total_keluhan' => $total[0]->c
						);

						QB::table("fact_keluhan")->insert($data);
					}
				}
			}
		}
	}

	$message = "finish";
}
echo $message;

?>
