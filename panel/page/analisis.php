<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);

$getData = QB::table("fact_keluhan");
$resData = $getData->get();

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

$date_arr = array();
foreach ($resWaktu as $key => $value) {
	$date_arr[] = $value->tanggal_kirim;
}
usort($date_arr, function($a, $b) {
    $dateTimestamp1 = strtotime($a);
    $dateTimestamp2 = strtotime($b);

    return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
});

//Get Dimensi Pengaduan
$getPengaduan = QB::table("dim_pengaduan");
$resPengaduan = $getPengaduan->get();
?>
<div class="block-header">
	<h2>ANALISIS DATA</h2>
</div>
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header text-center">
				<h2>
				Analisis Data Keluhan
				</h2>
			</div>
			<div class="body">
				<div class="col-md-12 demo-color-box bg-light-blue">
					<div class="col-md-6">
						<p>
							<b>Berdasarkan Provinsi Tujuan</b>
						</p>
						<select class="form-control show-tick" data-live-search="true" multiple data-selected-text-format="count" id="provinsi">
							<?php foreach ($resProp as $key => $value): ?>
							<option value="<?= $value->pk_propinsi ?>"><?= $value->nama_propinsi ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="col-md-3">
						<p>
							<b>Berdasarkan Jenis Pengiriman</b>
						</p>
						<select class="form-control show-tick" data-live-search="true" multiple data-selected-text-format="count" id="jenis">
							<?php foreach ($resJenis as $key => $value): ?>
							<option value="<?= $value->pk_jeniskiriman ?>"><?= $value->nama_jeniskiriman ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="col-md-3">
						<p>
							<b>Berdasarkan Layanan</b>
						</p>
						<select class="form-control show-tick" data-live-search="true" multiple data-selected-text-format="count" id="layanan">
							<?php foreach ($resLayanan as $key => $value): ?>
							<option value="<?= $value->pk_layanan ?>"><?= $value->nama_layanan ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="col-md-4">
						<p>
							<b>Berdasarkan Waktu/ Tanggal</b>
						</p>
						<input type="input" class="form-control datepicker" id="waktu" placeholder="Pilih Tanggal">
						<input type="hidden" id="minDate" value="<?= $date_arr[0] ?>">
						<input type="hidden" id="maxDate" value="<?= $date_arr[count($date_arr) - 1] ?>">
					</div>
					<div class="col-md-4">
						<p>
							<b>Berdasarkan Pengaduan</b>
						</p>
						<select class="form-control show-tick" data-live-search="true" multiple data-selected-text-format="count" id="pengaduan">
							<?php foreach ($resPengaduan as $key => $value): ?>
							<option value="<?= $value->pk_pengaduan ?>"><?= $value->nama_pengaduan ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="col-md-4">
						<p>
							<b>&nbsp</b>
						</p>
						<button type="button" id="tampilkan" class="btn btn-block bg-deep-orange waves-effect">
                <i class="material-icons">show_chart</i>
                <span>Tampilkan Grafik</span>
            </button>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="grafik" style="display: none">
							<div class="table-responsive">
								<div id="container-grafik" style="width: 100%;height: 500px"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		$("#tampilkan").click(function(){
			$("#tampilkan").prop("disabled", true);
			var provinsi = ($("#provinsi").val() !== null ) ? toObject($("#provinsi").val()) : $("#provinsi").val();
			var jenis =  ($("#jenis").val() !== null) ? toObject($("#jenis").val()) : $("#jenis").val();
			var layanan = ($("#layanan").val() !== null) ? toObject($("#layanan").val()) : $("#layanan").val();
			var waktu = $("#waktu").val();
			var pengaduan = ($("#pengaduan").val() !== null ) ? toObject($("#pengaduan").val()) : $("#pengaduan").val();

			var dimensiArr = [];
			if (provinsi == null  && jenis == null && layanan == null && waktu == "" && pengaduan == null) {
				swal("Peringatan!", "Dimensi Tidak Boleh Kosong", "warning");
			}else {
				dimensiArr.push({'provinsi':provinsi,'jenis':jenis,'layanan':layanan,'waktu':waktu,'pengaduan':pengaduan});
				var json = JSON.stringify(dimensiArr);

				// console.log(dimensiArr);

				$.ajax({
          url: "ajax/analisis.php",
          type: "POST",
          dataType: 'json',
          cache: false,
          processData:false,
          data: 'data='+json ,
          // beforeSend: function() {
          //   $('#loader').css("display", "");
          //   $("#infotext").text("Sedang Mengupload Data Excel....");
          // },
          success: function (response) {
          	console.log(response);
          	$("#tampilkan").prop("disabled", false);
          	$("#grafik").css("display","");
          	if (Object.keys(response[0]).length == 2) {
          		chartDonut(response);
          	}
          	else if(Object.keys(response[0]).length == 3){
          		chartAnalisis(response);
          	}
          	else {
          		console.log("lebih dari 2 ;" + Object.keys(response[0]).length );
          	}
            
          },
          error: function (e) {
            swal("Gagal", "Data tidak dapat tersimpan!", "error");
            $("#tampilkan").prop("disabled", false);
          }
        });
			}

			

		});

		function toObject(arr) {
			  var rv = {};
			  for (var i = 0; i < arr.length; ++i)
			    rv[i] = arr[i];
			  return rv;
		}

		function chartDonut(e){
			var t = "";
			var namaDimensi = "";
			var data = [];
			if (e[0].nama_propinsi) {
				t = "Provinsi";
				for(var i = 0; i< e.length ;i++){
					data.push({"name": e[i].nama_propinsi,"y":Number(e[i].sum) });
				}
			}else if(e[0].nama_jeniskiriman){
				t = "Jenis Kiriman";
				for(var i = 0; i< e.length ;i++){
					data.push({"name": e[i].nama_jeniskiriman,"y":Number(e[i].sum) });
				}
			}else if(e[0].nama_layanan){
				t = "Layanan";
				for(var i = 0; i< e.length ;i++){
					data.push({"name": e[i].nama_layanan,"y":Number(e[i].sum) });
				}
			}else if(e[0].tanggal_kirim){
				t = "Tanggal Kirim";
				for(var i = 0; i< e.length ;i++){
					data.push({"name": e[i].tanggal_kirim,"y":Number(e[i].sum) });
				}
			}else{
				t = "Pengaduan";
				for(var i = 0; i< e.length ;i++){
					data.push({"name": e[i].nama_pengaduan,"y":Number(e[i].sum) });
				}
			}

			Highcharts.chart('container-grafik', {
		    chart: {
		        plotBackgroundColor: null,
		        plotBorderWidth: null,
		        plotShadow: false,
		        type: 'pie'
		    },
		    title: {
		        text: 'Jumlah Keluhan Berdasarkan '+t
		    },
		    tooltip: {
		        pointFormat: '{series.name}: <b>{point.y:.0f} Pengaduan</b>'
		    },
		    plotOptions: {
		        pie: {
		            allowPointSelect: true,
		            cursor: 'pointer',
		            dataLabels: {
		                enabled: true,
		                format: '<b>{point.name}</b>: {point.y:.0f}',
		                style: {
		                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
		                }
		            }
		        }
		    },
		    series: [{
		        name: 'Total Pengaduan',
		        colorByPoint: true,
		        data: data
		    }]
		});
		}

		function chartAnalisis(e){
			var data = [];
			var dril1 = [];
			var dril2 = [];
			var dril3 = [];
			if (e[0].nama_propinsi && e[0].nama_jeniskiriman || e[0].nama_propinsi && e[0].nama_layanan || e[0].nama_propinsi && e[0].tanggal_kirim || e[0].nama_propinsi && e[0].nama_pengaduan ) {
				var sum_prov = {},result;
				// for(var i = 0; i< e.length ;i++){
					// data.push({"name": e[i].provinsi,"y":Number(e[i].sum) });
				// }
				var findDuplicatesAndSum = function(e) {
				    var duplicateIndex = {};
				    var outputArr = [];
				    for (var i = 0; i < e.length; i++) {
				        var item = e[i];
				        var collisionIndex = duplicateIndex[item[0]];
				        if (collisionIndex > -1) {
				            outputArr[collisionIndex][2] += item[2];
				        } else {
				            outputArr.push(item);
				            duplicateIndex[item[0]] = outputArr.length - 1;
				        }
				    }
				    console.log(outputArr);
				    return outputArr;
				};

				findDuplicatesAndSum(e);
			}
			else if(e[0].nama_jeniskiriman && e[0].nama_layanan || e[0].nama_jeniskiriman && e[0].tanggal_kirim || e[0].nama_jeniskiriman && e[0].nama_pengaduan){
				console.log("Jenis with Layanan, Tanggal and Pengaduan");
			}
			else if(e[0].nama_layanan && e[0].tanggal_kirim || e[0].nama_layanan && e[0].nama_pengaduan){
				console.log("Layaann with Tanggal and Pengaduan");
			}
			else {
				console.log("Tanggal with Pengaduan");
			}

			Highcharts.chart('container-grafik', {
		    chart: {
		        type: 'column'
		    },
		    title: {
		        text: 'Grafik Analisa Keluhan Pelanggan Pos Pekanbaru'
		    },
		    subtitle: {
		        text: 'Data Awal Berdasarkan Jumlah Keluhan Perprovinsi'
		    },
		    xAxis: {
		        type: 'category'
		    },
		    yAxis: {
		        title: {
		            text: 'Total Keluhan'
		        }

		    },
		    legend: {
		        enabled: false
		    },
		    plotOptions: {
		        series: {
		            borderWidth: 0,
		            dataLabels: {
		                enabled: true,
		                format: '{point.y:.0f} Keluhan'
		            }
		        }
		    },

		    tooltip: {
		        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
		        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> Keluhan<br/>'
		    },

		    "series": [
		        {
		            "name": "Browsers",
		            "colorByPoint": true,
		            "data": [
		                {
		                    "name": "Chrome",
		                    "y": 62.74,
		                    "drilldown": "Chrome"
		                },
		                {
		                    "name": "Firefox",
		                    "y": 10.57,
		                    "drilldown": "Firefox"
		                },
		                {
		                    "name": "Internet Explorer",
		                    "y": 7.23,
		                    "drilldown": "Internet Explorer"
		                },
		                {
		                    "name": "Safari",
		                    "y": 5.58,
		                    "drilldown": "Safari"
		                },
		                {
		                    "name": "Edge",
		                    "y": 4.02,
		                    "drilldown": "Edge"
		                },
		                {
		                    "name": "Opera",
		                    "y": 1.92,
		                    "drilldown": "Opera"
		                },
		                {
		                    "name": "Other",
		                    "y": 7.62,
		                    "drilldown": null
		                }
		            ]
		        }
		    ],
		    "drilldown": {
		        "series": [
		            {
		                "name": "Chrome",
		                "id": "Chrome",
		                "data": [
		                    [
		                        "v65.0",
		                        0.1
		                    ],
		                    [
		                        "v64.0",
		                        1.3
		                    ],
		                    [
		                        "v63.0",
		                        53.02
		                    ],
		                    [
		                        "v62.0",
		                        1.4
		                    ],
		                    [
		                        "v61.0",
		                        0.88
		                    ],
		                    [
		                        "v60.0",
		                        0.56
		                    ],
		                    [
		                        "v59.0",
		                        0.45
		                    ],
		                    [
		                        "v58.0",
		                        0.49
		                    ],
		                    [
		                        "v57.0",
		                        0.32
		                    ],
		                    [
		                        "v56.0",
		                        0.29
		                    ],
		                    [
		                        "v55.0",
		                        0.79
		                    ],
		                    [
		                        "v54.0",
		                        0.18
		                    ],
		                    [
		                        "v51.0",
		                        0.13
		                    ],
		                    [
		                        "v49.0",
		                        2.16
		                    ],
		                    [
		                        "v48.0",
		                        0.13
		                    ],
		                    [
		                        "v47.0",
		                        0.11
		                    ],
		                    [
		                        "v43.0",
		                        0.17
		                    ],
		                    [
		                        "v29.0",
		                        0.26
		                    ]
		                ]
		            },
		            {
		                "name": "Firefox",
		                "id": "Firefox",
		                "data": [
		                    [
		                        "v58.0",
		                        1.02
		                    ],
		                    [
		                        "v57.0",
		                        7.36
		                    ],
		                    [
		                        "v56.0",
		                        0.35
		                    ],
		                    [
		                        "v55.0",
		                        0.11
		                    ],
		                    [
		                        "v54.0",
		                        0.1
		                    ],
		                    [
		                        "v52.0",
		                        0.95
		                    ],
		                    [
		                        "v51.0",
		                        0.15
		                    ],
		                    [
		                        "v50.0",
		                        0.1
		                    ],
		                    [
		                        "v48.0",
		                        0.31
		                    ],
		                    [
		                        "v47.0",
		                        0.12
		                    ]
		                ]
		            },
		            {
		                "name": "Internet Explorer",
		                "id": "Internet Explorer",
		                "data": [
		                    [
		                        "v11.0",
		                        6.2
		                    ],
		                    [
		                        "v10.0",
		                        0.29
		                    ],
		                    [
		                        "v9.0",
		                        0.27
		                    ],
		                    [
		                        "v8.0",
		                        0.47
		                    ]
		                ]
		            },
		            {
		                "name": "Safari",
		                "id": "Safari",
		                "data": [
		                    [
		                        "v11.0",
		                        3.39
		                    ],
		                    [
		                        "v10.1",
		                        0.96
		                    ],
		                    [
		                        "v10.0",
		                        0.36
		                    ],
		                    [
		                        "v9.1",
		                        0.54
		                    ],
		                    [
		                        "v9.0",
		                        0.13
		                    ],
		                    [
		                        "v5.1",
		                        0.2
		                    ]
		                ]
		            },
		            {
		                "name": "Edge",
		                "id": "Edge",
		                "data": [
		                    [
		                        "v16",
		                        2.6
		                    ],
		                    [
		                        "v15",
		                        0.92
		                    ],
		                    [
		                        "v14",
		                        0.4
		                    ],
		                    [
		                        "v13",
		                        0.1
		                    ]
		                ]
		            },
		            {
		                "name": "Opera",
		                "id": "Opera",
		                "data": [
		                    [
		                        "v50.0",
		                        0.96
		                    ],
		                    [
		                        "v49.0",
		                        0.82
		                    ],
		                    [
		                        "v12.1",
		                        0.14
		                    ]
		                ]
		            }
		        ]
		    }
		});
		}
	});
</script>