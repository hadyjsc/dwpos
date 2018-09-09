<div class="block-header">
	<h2>DATA KANTOR CABANG</h2>
</div>
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header text-center">
				<h2>
				Data Kantor Cabang Pekanbaru
				</h2>
			</div>
			<div class="body">
				<a class="btn btn-success waves-effect" href="sistem.php?page=kacab&action=tambah">
					<i class="material-icons">add</i>
					<span>Tambah Kacab</span>
				</a>
				<br/><br/>
				<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover dataTable js-basic-example" width="100%">
							<thead>
								<td width="5%">#</td>
								<td>Nama</td>
								<td>Alamat</td>
								<td>Koordinat</td>
								<td>Deskripsi</td>
								<td>Aksi</td>
							</thead>
							<tbody>
								<?php
								$query = QB::table("kacab")->select("*");
								$result = $query->get();
								$no = 1;
								?>

								<?php foreach ($result as $key => $value): ?>
									<tr>
										<td><?= $no++ ?></td>
										<td><?= $value->nama ?></td>
										<td><?= $value->alamat ?></td>
										<td>Lat : <?= $value->lat ?>, Lng : <?= $value->lng ?> </td>
										<td><?= $value->deskripsi ?></td>
										<td>
						            <a href="sistem.php?page=kacab&action=ubah&id=<?= $value->id ?>" class="btn btn-warning waves-effect"><div><i class="material-icons">edit</i><span>Ubah</span></div></a>
						            
						            <button id="hapus" onclick="hapus(this)" data-id="<?= $value->id ?>" class="btn btn-danger waves-effect"><div><i class="material-icons">delete</i><span>Hapus</span></div></button>
										</td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="addCabang" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg"" role="document">
        <div class="modal-content">
            <div class="modal-header bg-orange">
                <h4 class="modal-title text-center" id="defaultModalLabel">Tambah Data Cabang</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	$("#tambahKacab").click(function(){
	    $('.modal-body').load('ajax/tambahKacab.php',function(){
	        $('#addCabang').modal({show:true});
	    });
	});

	function hapus(e) {
		var id = $(e).attr("data-id");
		swal({
		  title: 'Warning!',
		  text: "Apakah anda yakin data Kantor Cabang akan dihapus?",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Hapus',
		  cancelButtonText: 'Batal'
		})
		.then((result) => {
		  if (result.value) {
			   $.ajax({
			    type:'POST',
			    url:'ajax/kacab.php',
			    data:{'id':id,'action':'delete'},
			    success: function(response){
			        if(response.code == "1"){
			            console.log("Terhapus");
			        }else{
			         	swal("Gagal", "Data tidak dapat dihapus dari database!", "error");
			        }
			    }
			}).done(function(data) {
	            swal("Berhasil!", "Data kacab berhasil dihapus!", "success").then((value)=>{
	            	location.reload();
	            });
	        }).error(function(data) {
	            swal("Gagal", "Data tidak dapat dihapus dari database!", "error");
	          	});
		  } else {
		    swal("Informasi!", "Data Kacab tidak dihapus", "warning");
		  }
		});
	}
</script>