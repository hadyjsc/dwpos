<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Halaman Login</title>
    <!-- Favicon-->
    <link rel="shortcut icon" href="assets/images/bj.png" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/plugins/node-waves/waves.css" rel="stylesheet" />
    <link href="assets/plugins/animate-css/animate.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/themes/theme-orange.css" rel="stylesheet">
</head>

<body class="login-page">
    <div class="login-box">
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST">
                  <center>
                      <img src="assets/images/bj.png" class="image-responsive" width="150px">
                    </center><br>
                    <div class="msg">Masuk Untuk Memulai Sesi</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                        </div>
                    </div>
                </form>
                    <div class="row">
                        <div class="col-xs-6">
                            <button class="btn btn-block bg-orange waves-effect" name="btn-login" id="btn-login" type="submit">
                                <i class="material-icons">input</i>
                                <span>Masuk</span>
                            </button>
                        </div>
                          <div class="col-xs-6">
                            <a href="" class="btn btn-block bg-blue waves-effect">
                                <i class="material-icons">home</i>
                                <span>Halaman Utama</span>
                            </a>
                        </div>
                    </div>
                    <div id="error"></div>
            </div>
        </div>
    </div>

    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.js"></script>
    <script src="assets/plugins/node-waves/waves.js"></script>
    <script src="assets/plugins/jquery-validation/jquery.validate.js"></script>
    <script src="assets/js/admin.js"></script>
    <script type="text/javascript">
    $(function () {
      $("#btn-login").click(function () {
        $('#sign_in').validate({
            highlight: function (input) {
                // console.log(input);
                $(input).parents('.form-line').addClass('error');
            },
            unhighlight: function (input) {
                $(input).parents('.form-line').removeClass('error');
            },
            errorPlacement: function (error, element) {
                $(element).parents('.input-group').append(error);
            }
        });

        var uname = $("#username").val();
        var upass = $("#password").val();
        $.ajax({
            type: 'POST',
            url: 'verify.php',
            data: {
                username: uname,
                password: upass,
                login:"login"
            },
            beforeSend: function() {
                $("#error").fadeOut();
                $("#btn-login").html('<i class="material-icons">refresh</i> &nbsp; Verify ...');
                $("#btn-login").attr("disabled","disabled");
            },
            success: function(response) {
                if (response == "Berhasil!") {
                    $("#btn-login").html('<i class="material-icons">check</i> &nbsp; Silahkan Tunggu ...');
                    setTimeout(' window.location.href = "sistem.php?page=home"; ', 2000);
                } else {
                    $("#error").fadeIn(1000, function() {
                        $("#btn-login").prop("disabled", false); 
                        $("#error").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; ' + response + ' !</div>');
                        $("#btn-login").html('<i class="material-icons">input</i><span>Masuk</span>');
                    });
                }
            }
        });
        return false;        
      });
    });
    </script>
</body>
</html>