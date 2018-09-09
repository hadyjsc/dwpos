<style type="text/css">
	#DataTables_Table_0_length {
		float: left;margin-right: 20px;
	}
</style>
<div class="block-header">
    <h2>DATA KELUHAN</h2>
</div>

 <div class="row clearfix">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="card">
          <div class="header text-center">
              <h2>
                  Data Keluhan Pelanggan
              </h2>
          </div>
          <div class="body">
            <a class="btn btn-success waves-effect" id="import">
              <i class="material-icons">file_upload</i>
              <span>Import Keluhan</span>
            </a>
            <!-- <a class="btn btn-success waves-effect" href="sistem.php?page=keluhan&action=proses">
              <i class="material-icons">refresh</i>
              <span>Proses Warehouse</span>
            </a> -->
             <a class="btn btn-success waves-effect" id="backupAll">
              <i class="material-icons">file_download</i>
              <span>Download Full CSV, Xlsx dan SQL</span>
            </a>
            <br/><br/>
              <div class="table-responsive">
                  <table class="table table-bordered table-striped table-hover dataTable js-exportable" width="100%">
                      <thead>
                          <tr>
                              <th width="20%">Kacab</th>
                              <th width="20%">No. Kiriman</th>
                              <th width="25%">Pengirim</th>
                              <th width="20%">Tanggal Kirim</th>
                              <th>Keluhan</th>
                          </tr>
                      </thead>
                      <tfoot>
                          <tr>
                              <th>Kacab</th>
                              <th>No. Kiriman</th>
                              <th>Pengirim</th>
                              <th>Tanggal Kirim</th>
                              <th>Keluhan</th>
                          </tr>
                      </tfoot>
                      <tbody>
                      	<?php
                      		$data = QB::query("SELECT data_test.id, dim_kpkpc.nama_KpKpc,dim_noresi.no_resi,data_test.pengirim,dim_waktu.tanggal_kirim,dim_pengaduan.nama_pengaduan FROM data_test, dim_waktu, dim_pengaduan, dim_noresi, dim_kpkpc WHERE dim_kpkpc.pk_KpKpc = data_test.kacab AND dim_noresi.pk_noresi = data_test.no_resi AND dim_waktu.pk_waktu = data_test.tgl_kirim AND dim_pengaduan.pk_pengaduan = data_test.keluhan ORDER BY data_test.id ASC");
                      		$resData = $data->get();
                      		$no = 1;
                      	?>
                      	<?php foreach ($resData as $key => $value): ?>
                      		<tr>
                      			<td><?= $value->nama_KpKpc ?></td>
                      			<td>
                      				<a style="cursor: pointer;" onclick="lihat(this)" data-id="<?= $value->id ?>"  data-toggle="modal" data-target="#defaultModal"><?= $value->no_resi ?></a></td>
                      			<td><a style="cursor: pointer;" onclick="lihat(this)" data-id="<?= $value->id ?>"  data-toggle="modal" data-target="#defaultModal"><?= $value->pengirim ?></a></td>
                      			<td><?= $value->tanggal_kirim ?></td>
                      			<td><?= $value->nama_pengaduan ?></td>
                      		</tr>
                      	<?php endforeach ?>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-orange">
                <h4 class="modal-title text-center" id="defaultModalLabel">Detail Data Keluhan</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success waves-effect" id="submit" style="display: none;">SIMPAN</button>
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	function lihat(e) {
		var id = $(e).attr("data-id");
	    $('.modal-body').load('ajax/detailKeluhan.php?id='+id,function(){
	        $('#modals').modal({show:true});
	    });
	}

  $("#import").click(function(){
    
    $("#defaultModalLabel").text("Import Data Keluhan");

    $(".modal-body").load("page/upload.php",function(){
      $('#defaultModal').modal({show:true,backdrop:"static"});  
      $("#submit").css("display","");
      $("#submit").click(function(){
        $("#submit").prop("disabled",true);
        var form = $('#formimport')[0];
        var data = new FormData(form);

        $.ajax({
          url: "ajax/upload.php",
          type: "POST",
          data:  data,
          contentType: false,
          cache: false,
          processData:false,
          beforeSend: function() {
            $('#loader').css("display", "");
            $("#infotext").text("Sedang Mengupload Data Excel....");
          },
          success: function (data) {
            if(data == "finish"){
              $('#loader').css("display", "none");
              swal("Tersimpan!", "Data siswa berhasil disimpan!", "success").then((value)=>{
                      location.reload();
                    });
             $("#submit").prop("disabled", false);
            }
            // console.log("SUCCESS : ", data);
            
          },
          error: function (e) {
            swal("Gagal", "Data tidak dapat tersimpan!", "error");
            console.log("ERROR : ", e);
            $("#submit").prop("disabled", false);
          }
        });
        
      });
    });
    
  });

  $("#backupAll").click(function(){
    $.ajax({
          type:'POST',
          url:'ajax/backup.php',
          data:{'action':'backup'},
          dataType: "JSON",
          success: function(response){
            // console.log("OK");
            if(response.code == "1"){
              swal("Berhasil!", "Database berhasil di backup ke folder <b>"+response.dir+"</b>", "success").then((value)=>{
                location.reload();
              });
            }
          }
        });
  });
</script>