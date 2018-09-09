<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
include_once '../../config/conn.php';
?>

<?php if (isset($_GET["id"])): ?>
	<?php
	$id = $_GET["id"];

	$getData = QB::query("SELECT data_test.id, dim_kpkpc.nama_KpKpc,data_test.alamat_kacab,dim_noresi.no_resi,data_test.pengirim,data_test.penerima,dim_propinsi.nama_propinsi,data_test.kab_tujuan,data_test.kec_tujuan,data_test.kel_tujuan,dim_waktu.tanggal_kirim,dim_jeniskiriman.nama_jeniskiriman,dim_pengaduan.nama_pengaduan FROM `data_test`,dim_waktu,dim_propinsi,dim_pengaduan,dim_noresi,dim_layanan,dim_kpkpc,dim_jeniskiriman WHERE dim_kpkpc.pk_KpKpc = data_test.kacab AND dim_noresi.pk_noresi = data_test.no_resi AND dim_propinsi.pk_propinsi = data_test.prov_tujuan AND dim_waktu.pk_waktu = data_test.tgl_kirim AND dim_jeniskiriman.pk_jeniskiriman = data_test.jenis AND dim_pengaduan.pk_pengaduan = data_test.keluhan AND data_test.id = ".$id);
	$res = $getData->get();
	?>
	<table class="table table-bordered" width="100%">
		<tr>
			<th width="30%">Kantor Cabang</th>
			<td><?= $res[0]->nama_KpKpc ?></td>
		</tr>
		<tr>
			<th>Nomor Kiriman</th>
			<td><?= $res[0]->no_resi ?></td>
		</tr>
		<tr>
			<th>Nama Pengirim</th>
			<td><?= $res[0]->pengirim ?></td>
		</tr>
		<tr>
			<th>Penerima</th>
			<td><?= $res[0]->penerima ?></td>
		</tr>
		<tr>
			<th>Provinsi Tujuan</th>
			<td><?= $res[0]->nama_propinsi ?></td>
		</tr>
		<tr>
			<th>Kabupaten Tujuan</th>
			<td><?= $res[0]->kab_tujuan ?></td>
		</tr>
		<tr>
			<th>Kecamatan Tujuan</th>
			<td><?= $res[0]->kec_tujuan ?></td>
		</tr>
		<tr>
			<th>Kelurahan Tujuan</th>
			<td><?= $res[0]->kel_tujuan ?></td>
		</tr>
		<tr>
			<th>Tanggal Pengriman</th>
			<td><?= $res[0]->tanggal_kirim ?></td>
		</tr>
		<tr>
			<th>Jenis</th>
			<td><?= $res[0]->nama_jeniskiriman ?></td>
		</tr>
		<tr>
			<th>Keluhan</th>
			<td><?= $res[0]->nama_pengaduan ?></td>
		</tr>
	</table>
<?php else: ?>	
	Ada tidak dapat mengakses halaman ini
<?php endif ?>