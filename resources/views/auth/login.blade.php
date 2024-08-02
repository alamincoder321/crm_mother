<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ERP Mother Software Login Page </title>
    <link rel="icon" type="image/png" href="{{asset('backend')}}/img/favicon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{asset('auth')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('backend')}}/css/toastr.min.css">
</head>

<body>
    <div class="container">
        <div class="cover">
            <div class="front">
                <img src="{{asset('auth')}}/images/login.webp" alt="">
                <div class="text">
                    <span class="text-1">Every new friend is a <br> new adventure</span>
                    <span class="text-2">Let's get connected</span>
                </div>
            </div>
        </div>
        <div class="forms">
            <div class="form-content">
                <div class="login-form">
                    <div class="title">Login your account</div>
                    <form onsubmit="userLogin(event)">
                        <div class="input-boxes">
                            <div class="input-box" style="margin: 0;">
                                <i class="fas fa-envelope"></i>
                                <input type="text" name="username" placeholder="Username" autocomplete="off">
                            </div>
                            <p class="error-username" style="font-style:italic;font-size:12px;color:red;"></p>
                            <div class="input-box password" style="margin: 0;">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password" placeholder="Password" autocomplete="off">
                                <i class="fa fa-eye passwordeye" style="position: absolute;top: 34%;right: 10px;cursor:pointer;" onclick="passwordShow(event)"></i>
                            </div>
                            <p class="error-password" style="font-style:italic;font-size:12px;color:red;"></p>
                            <div class="text"><a href="#">Forgot password?</a></div>
                            <div class="button input-box">
                                <input type="submit" value="Login">
                            </div>
                            <!-- <div class="text sign-up-text">Don't have an account? <label for="flip">Sigup now</label></div> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('backend')}}/js/jquery.min.js"></script>
    <script src="{{asset('backend')}}/js/toastr.min.js"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function userLogin(event) {
            event.preventDefault();
            $("#login").prop("disabled", true);
            var formdata = new FormData(event.target)
            $.ajax({
                url: "/login",
                method: "POST",
                data: formdata,
                contentType: false,
                processData: false,
                beforeSend: () => {
                    $(".error-username").text('').removeClass("text-danger")
                    $(".error-password").text('').removeClass("text-danger")
                },
                success: res => {
                    location.href = "/panel/dashboard"
                },
                error: err => {
                    $("#login").prop("disabled", false);
                    toastr.error(err.responseJSON.message);
                    if (typeof err.responseJSON.errors == 'object') {
                        $.each(err.responseJSON.errors, (index, value) => {
                            $(".error-" + index).text(value).addClass("text-danger")
                        })
                        return
                    }
                    console.log(err.responseJSON.errors);
                }
            })
        }
        // show password
        function passwordShow(event) {
            let password = $(".password").find('input').prop('type');
            if (password == 'password') {
                $(".password").find('.passwordeye').removeProp('class').prop('class', 'fa fa-eye-slash passwordeye')
                $(".password").find('input').removeProp('type').prop('type', 'text');
            } else {
                $(".password").find('.passwordeye').removeProp('class').prop('class', 'fa fa-eye passwordeye')
                $(".password").find('input').removeProp('type').prop('type', 'password');
            }
        }
    </script>
</body>

</html>