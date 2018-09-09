<?php
/**
 * 
 */
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class BackupPos {
		
	private $dbHost;
  private $dbName;
  private $dbUser;
  private $dbPass;
  private $dir;
  function __construct($a,$b,$c,$d) {
		$this->dbHost = $a;
		$this->dbName = $b;
		$this->dbUser = $c;
		$this->dbPass = $d;
		$this->dir = "../../fbackup";
	}

	public function sql($date,$tables = "*") {
			
			$host = $this->dbHost;
			$database = $this->dbName;
			$user = $this->dbUser;
			$pass = $this->dbPass;
			$newDir = $this->dir."/".$date;
			if (!is_dir($newDir)) {
					mkdir($newDir,0777);
					$file = $newDir."/pos.sql";
					exec("mysqldump --user={$user} --password={$pass} --host={$host} {$database} --result-file={$file} 2>&1", $output);		
			
			}
	}

	public function csv($date){
			$data = QB::table("data")->select("*");
			$get = $data->get();

			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', "Id Data");
			$sheet->setCellValue('B1', "Kantor Cabang");
			$sheet->setCellValue('C1', "Nomor Kiriman");
			$sheet->setCellValue('D1', "Pengirim");
			$sheet->setCellValue('E1', "Penerima");
			$sheet->setCellValue('F1', "Prov. Tujuan");
			$sheet->setCellValue('G1', "Kab. Tujuan");
			$sheet->setCellValue('H1', "Kec. Tujuan");
			$sheet->setCellValue('I1', "Kel. Tujuan");
			$sheet->setCellValue('J1', "Tanggal Kirim");
			$sheet->setCellValue('K1', "Jenis");
			$sheet->setCellValue('L1', "Keluhan");
			$count = 2;
			foreach ($get as $key => $value) {
				$sheet->setCellValue('A'.$count, $value->id);
				$sheet->setCellValue('B'.$count, $value->kacab);
				$sheet->setCellValue('C'.$count, $value->no_kiriman);
				$sheet->setCellValue('D'.$count, $value->pengirim);
				$sheet->setCellValue('E'.$count, $value->penerima);
				$sheet->setCellValue('F'.$count, $value->prov_tujuan);
				$sheet->setCellValue('G'.$count, $value->kab_tujuan);
				$sheet->setCellValue('H'.$count, $value->kec_tujuan);
				$sheet->setCellValue('I'.$count, $value->kel_tujuan);
				$sheet->setCellValue('J'.$count, $value->tgl_kirim);
				$sheet->setCellValue('K'.$count, $value->jenis);
				$sheet->setCellValue('L'.$count, $value->keluhan);
				$count++;
			}

			$writer = new Csv($spreadsheet);
			$writer->setDelimiter(',');
			$writer->setEnclosure('');
			$writer->setLineEnding("\r\n");
			$writer->setSheetIndex(0);
			$newDir = $this->dir."/".$date;
			if (!is_dir($newDir)) {
				mkdir($newDir,0777);
				$writer->save($newDir."/Data Keluhan Pos Indonesia.csv");
			}else {
				$writer->save($newDir."/Data Keluhan Pos Indonesia.csv");
			}
	}

	public function xlsx($date){
			$data = QB::table("data")->select("*");
			$get = $data->get();

			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', "Id Data");
			$sheet->setCellValue('B1', "Kantor Cabang");
			$sheet->setCellValue('C1', "Nomor Kiriman");
			$sheet->setCellValue('D1', "Pengirim");
			$sheet->setCellValue('E1', "Penerima");
			$sheet->setCellValue('F1', "Prov. Tujuan");
			$sheet->setCellValue('G1', "Kab. Tujuan");
			$sheet->setCellValue('H1', "Kec. Tujuan");
			$sheet->setCellValue('I1', "Kel. Tujuan");
			$sheet->setCellValue('J1', "Tanggal Kirim");
			$sheet->setCellValue('K1', "Jenis");
			$sheet->setCellValue('L1', "Keluhan");
			$count = 2;
			foreach ($get as $key => $value) {
				$sheet->setCellValue('A'.$count, $value->id);
				$sheet->setCellValue('B'.$count, $value->kacab);
				$sheet->setCellValue('C'.$count, $value->no_kiriman);
				$sheet->setCellValue('D'.$count, $value->pengirim);
				$sheet->setCellValue('E'.$count, $value->penerima);
				$sheet->setCellValue('F'.$count, $value->prov_tujuan);
				$sheet->setCellValue('G'.$count, $value->kab_tujuan);
				$sheet->setCellValue('H'.$count, $value->kec_tujuan);
				$sheet->setCellValue('I'.$count, $value->kel_tujuan);
				$sheet->setCellValue('J'.$count, $value->tgl_kirim);
				$sheet->setCellValue('K'.$count, $value->jenis);
				$sheet->setCellValue('L'.$count, $value->keluhan);
				$count++;
			}

			$writer = new Xlsx($spreadsheet);
			$newDir = $this->dir."/".$date;
			if (!is_dir($newDir)) {
				mkdir($newDir,0777);
				$writer->save($newDir."/Data Keluhan Pos Indonesia.xlsx");
			}else {
				$writer->save($newDir."/Data Keluhan Pos Indonesia.xlsx");
			}
	}

	
}

?>