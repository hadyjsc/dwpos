<?php

$queryData = QB::table("data_test")->select("kacab","prov_tujuan","keluhan");
$resData = $queryData->get();
$countData = count($resData);
$countKeluhan = 0;
$countNonKeluhan = 0;

$arr = array();
foreach ($resData as $key => $value) {
    // if ($value->keluhan != "Tidak Ada") {
    if ($value->keluhan != 1) {
        $arr[$value->kacab]["keluhan"][] = $value->keluhan;
        $countKeluhan++;
    }else {
        $arr[$value->kacab]["non"][] = $value->keluhan;
        $countNonKeluhan++;
    }
}

$newCount = array();
foreach ($arr as $key => $value) {
    $newCount[$key] = array("non"=>count($value["non"]),"keluhan"=>count($value["keluhan"]));
}

$queryKacab = QB::table("dim_kpkpc")->count();


?>

<div class="block-header">
    <h2>DASHBOARD</h2>
</div>

<div class="row clearfix">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-pink hover-expand-effect">
            <div class="icon">
                <i class="material-icons">playlist_add_check</i>
            </div>
            <div class="content">
                <div class="text">DATA PENGIRIMAN</div>
                <div class="number count-to" data-from="0" data-to="<?= $countData ?>" data-speed="1000" data-fresh-interval="20"></div>
            </div>
        </div>
    </div>
   
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-light-green hover-expand-effect">
            <div class="icon">
                <i class="material-icons">forum</i>
            </div>
            <div class="content">
                <div class="text">JUMLAH KELUHAN</div>
                <div class="number count-to" data-from="0" data-to="<?= $countKeluhan ?>" data-speed="1000" data-fresh-interval="20"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-orange hover-expand-effect">
            <div class="icon">
                <i class="material-icons">forum</i>
            </div>
            <div class="content">
                <div class="text">TIDAK ADA KELUHAN</div>
                <div class="number count-to" data-from="0" data-to="<?= $countNonKeluhan ?>" data-speed="1000" data-fresh-interval="20"></div>
            </div>
        </div>
    </div>
     <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-cyan hover-expand-effect">
            <div class="icon">
                <i class="material-icons">business</i>
            </div>
            <div class="content">
                <div class="text">JUMLAH KACAB</div>
                <div class="number count-to" data-from="0" data-to="<?= $queryKacab ?>" data-speed="1000" data-fresh-interval="20"></div>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="header bg-orange">
                <div class="row clearfix">
                    <div class="col-xs-12 col-sm-12">
                        <center><h2>GRAFIK DATA PENGIRIMAN</h2></center>
                    </div>
                </div>
            </div>
            <div class="body">
                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Pos Indonesia Cabang Pekanbaru'
    },
    subtitle: {
        text: 'Grafik Keluhan dan Non Keluhan'
    },
    xAxis: {
        categories: [
            <?php
                foreach ($newCount as $key => $value) {
                    echo "'".$key."',";
                }
            ?>
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Jumlah Data'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.0f} Data</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [
    {
        name: 'Total',
        data: [
        <?php
        foreach ($newCount as $key => $value) {
            echo $value["non"]+$value["keluhan"].",";
        }
        ?>
        ]

    },
    {
        name: 'Keluhan',
        data: [
        <?php
        foreach ($newCount as $key => $value) {
            echo $value["keluhan"].",";
        }
        ?>
        ]

    }, {
        name: 'Non Keluhan',
        data: [
        <?php
        foreach ($newCount as $key => $value) {
            echo $value["non"].",";
        }
        ?>
        ]

    }]
});
</script>