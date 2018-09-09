<div class="block-header">
	<h2>DATA KANTOR CABANG</h2>
</div>
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="body">
				<div class="row clearfix">
					<div id="maps"></div>
				</div>
				<br>
				<form id="kacab-tambah" action="post">
				<input type="hidden" name="action" value="add">
				<div class="row clearfix">
					<div class="col-sm-4">
						<div class="form-group">
							<label>Nama Kacab</label>
							<div class="form-line">
								<input type="text" name="nama" class="form-control" placeholder="Nama Kantor Cabang">
							</div>
						</div>
					</div>
					<div class="col-sm-8">
						<div class="form-group">
							<label>Deskripsi Singkat (Opsional)</label>
							<div class="form-line">
								<input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi Singkat Kantor Cabang">
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label>Alamat</label>
							<div class="form-line">
								<input type="text" name="alamat" class="form-control" placeholder="Alamat Kantor Cabang">
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label>Latitude</label>
							<div class="form-line">
								<input type="text" name="lat" id="lat" class="form-control" placeholder="Latitude" readonly="">
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label>Longitude</label>
							<div class="form-line">
								<input type="text" name="lng" id="lng" class="form-control" placeholder="Longitude" readonly="">
							</div>
						</div>
					</div>
				</div>
				</form>
				<button class="btn btn-success waves-effect" id="simpan"><i class="material-icons">save</i><span>Simpan Data</span></button>
				<a href="sistem.php?page=kacab" class="btn btn-warning waves-effect"><i class="material-icons">arrow_left</i><span>Batal</span></a>
			</div>
		</div>
	</div>
</div>
<script type='text/javascript' src='../assets/leaflet/leaflet.js'></script>
<script src="../assets/js/petapanel.js"></script>
<script type="text/javascript">
	$(function () {
		$("#simpan").click(function(){
			var data = $("#kacab-tambah").serialize();
			swal({
			  title: 'Warning!',
			  text: "Apakah anda yakin akan menyimpan data kantor cabang area Pekanbaru?",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Simpan',
			  cancelButtonText: 'Batal'
			})
			.then((result) => {
			  if (result.value) {
				   $.ajax({
				    type:'POST',
				    url:'ajax/kacab.php',
				    data:data,
				    dataType: "JSON",
				    success: function(response){
				        if(response.response.code =="1"){
				            console.log("Tersimpan");
				        }else{
				         	swal("Gagal", "Data kacab tidak dapat tersimpan ke <i>database</i>!", "error");
				        }
				    }
				}).done(function(data) {
		            swal("Berhasil!", "Data kantor cabang berhasil tersimpan!", "success").then((value)=>{
		            	location.reload();
		            });
		        }).error(function(data) {
		            swal("Gagal", "Data kacab tidak dapat tersimpan ke <i>database</i>!", "error");
		          	});
			  } else {
			    swal("Informasi!", "Transaksi dibatalkan, data tidak tersimpan!", "warning");
			  }
			});
		});
	});
</script>