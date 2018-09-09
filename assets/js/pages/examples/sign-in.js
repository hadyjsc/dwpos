// $(function () {
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
                $("#btn-login").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; Verify ...');
            },
            success: function(response) {
                if (response == "Berhasil!") {
                    $("#btn-login").html('<i class="fa fa-check"></i> &nbsp; Silahkan Tunggu ...');
                    setTimeout(' window.location.href = "sistem.php?page=home"; ', 2000);
                } else {
                    $("#error").fadeIn(1000, function() {
                        $("#error").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; ' + response + ' !</div>');
                        $("#btn-login").html('<span class="glyphicon glyphicon-log-in"></span> &nbsp; Masuk');
                    });
                }
            }
        });
        return false;        
    });
// });