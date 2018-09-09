<?php
session_start();
include '../config/conn.php';
use Marquine\Etl\Job;
use Marquine\Etl\Extractors\Query;
use Marquine\Etl\Transformers\Trim;
use Marquine\Etl\Loaders\Table;

$job = new Job;
$loader = new Table;
if ($_SESSION['id'] == null) {
    header("location:index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome To | Dashboard Page</title>
    <link rel="shortcut icon" href="../assets/images/bj.png" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="../assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/plugins/node-waves/waves.css" rel="stylesheet" />
    <link href="../assets/plugins/animate-css/animate.css" rel="stylesheet" />
    <link href="../assets/plugins/morrisjs/morris.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/themes/theme-orange.min.css" rel="stylesheet">
    <link href="../assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="../assets/plugins/multi-select/css/multi-select.css" rel="stylesheet">
    <link href="../assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <link href="../assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../assets/js/sweetalert2/dist/sweetalert2.min.css">
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="../assets/plugins/highcharts/code/highcharts.js"></script>
    <script src="../assets/plugins/highcharts/code/modules/drilldown.js"></script>
    <style type="text/css">
        #maps {
            height: 400px;
            z-index: 0;
        }
    </style>
</head>

<body class="theme-orange">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <img src="../assets/images/bj.png" class="image-responsive navbar-brand" width="50px" height="60px">
                <a class="navbar-brand" href="?page=home"> POS</a>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="../assets/images/<?= $_SESSION['foto'] ?>" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" aria-haspopup="true" aria-expanded="false"><?= $_SESSION["username"] ?></div>
                    <div class="name" aria-haspopup="true" aria-expanded="false"><strong><?= $_SESSION["nama"] ?></strong></div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="<?= ($_GET['page'] == 'home')?'active':''; ?>">
                        <a href="sistem.php?page=home">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="<?= ($_GET['page'] == 'keluhan')?'active':''; ?>">
                        <a href="sistem.php?page=keluhan">
                            <i class="material-icons">text_fields</i>
                            <span>Data Keluhan</span>
                        </a>
                    </li>
                    <li class="<?= ($_GET['page'] == 'analisis')?'active':''; ?>">
                        <a href="sistem.php?page=analisis">
                            <i class="material-icons">find_replace</i>
                            <span>Analisis Keluhan</span>
                        </a>
                    </li>
                    <li>
                        <a onclick="keluar()">
                            <i class="material-icons">input</i>
                            <span>Keluar</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; <?= date("Y") ?> <a href="javascript:void(0);">Admin</a>
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php
                $page = $_GET["page"];
                if ($page != null) {
                    $action = (isset($_GET["action"]))? $_GET["action"]:"";
                    if ($action != null) {
                        include "page/".$page."-".$action.".php";
                    }else {
                        include "page/".$page.".php";
                    }
                }
                else {
                    include '../page/404.php';
                }
            ?>
        </div>
    </section>

    
    <script src="../assets/plugins/bootstrap/js/bootstrap.js"></script>
    <script src="../assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <script src="../assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <script src="../assets/plugins/node-waves/waves.js"></script>
    <script src="../assets/plugins/jquery-countto/jquery.countTo.js"></script>
    <script src="../assets/plugins/momentjs/moment.js"></script>

    <script src="../assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

    <script src="../assets/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="../assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="../assets/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="../assets/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="../assets/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="../assets/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="../assets/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="../assets/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="../assets/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
    <script src="../assets/plugins/multi-select/js/jquery.multi-select.js"></script>
    <script src="../assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <script src="../assets/js/admin.js"></script>
    
    <script type="text/javascript" src="../assets/js/sweetalert2/dist/sweetalert2.all.js"></script>
    <script src="../assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="../assets/js/pages/index.js"></script>
    <script type="text/javascript">
        $(function () {
            var minDate = $("#minDate").val();
            var maxDate = $("#maxDate").val();
            $('.datepicker').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                clearButton: true,
                weekStart: 1,
                time: false,
                minDate: minDate,
                maxDate: maxDate,
            });
            
        });

        function keluar() {
          swal({
            title: 'Apakah anda akan keluar?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Keluar',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.value) {
              $.ajax({
                type:'POST',
                url:'ajax/out.php',
                data:{'action':'keluar'},
                success: function(data){
                    if(data=="OK"){
                      swal("Berhasil!", "Silahkan Login Kembali", "success").then((value)=>{
                         window.location.href = 'index.php';
                      });
                    }else{
                      console.log(data);
                    }
                }
              });

            }
          })
      }
    </script>
</body>

</html>
