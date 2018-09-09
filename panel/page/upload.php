<div class="col-md-12">
	Import Data dengan format file excel (xls atau xlsx). Lihat format dokumen dari link berikut : <a href="file/format.xlsx">Format Dokumen</a>
	<br>
	<br>
	<form action="#" id="formimport" enctype="multipart/form-data">
    	<div class="file-upload btn bg-teal waves-effect" style="color: #fff">
    		<span>Pilih File</span>
    	<input type="file" class="upload" id="FileAttachment"  name="getfile" accept=".xlsx,.xls">
    	</div>
    	<input type="text" id="fileuploadurl" readonly placeholder="File harus berformat xls atau xlsx">
  </form>
  <div id="loader" style="display: none">
  	<center>
  	<h4>Mohon Tunggu!</h4>
  	<img src="../assets/images/loading@2x.gif">
  	<br><br>
  	<h4><span id="infotext" class="label label-info">Default Text ....</span></h4>
  	</center>
  	<br>
  </div>
</div>
<script type="text/javascript">
	document.getElementById("FileAttachment").onchange = function () {
    document.getElementById("fileuploadurl").value = this.value.replace(/C:\\fakepath\\/i, '');
};
</script>