<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>EASE || POS</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.css') }}">
    <!-- Logo for demo purposes -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/logo.png') }}">

    <style type="text/css">
        body {
            background: linear-gradient(to right, #ffffff, #ffffff);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .login-box{
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.2) !important;
        }
        .login-logo,
        .input-group,
        .icheck-primary,
        .col-4 {
            animation: showSlowlyElement 700ms !important;
        }
        .btn-primary{
            background-color: #fc204f !important;
            border: #fc204f !important;
            color: #ffff;
        }
        .footer-login {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: rgba(34, 17, 34, 0.63);
        }
        .footer-login a {
            color: #fc204f;
            text-decoration: none;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card">
            <div class="card-body">
                <div class="login-logo">
                    <a href="">
                        <img src="{{ asset('template/img/logo.png') }}" width="60%" style="width: 50%; margin-bottom: -30px;">
                    </a>
                </div>
                <p class="login-box-msg pt-2">Sign in to start your session</p>
               
                <form action="{{route('postLogin')}}" method="post">
                    @csrf

                    @if(session('error'))
                        <div class="alert alert-danger" style="font-size: 12pt;">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success" style="font-size: 10pt;">
                            <i class="fas fa-check"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" autocomplete="off" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <span style="color: #FF0000; font-size: 8pt;" class="form-text text-center">@error('username') {{ $message }} @enderror</span>

                    <div class="input-group">
                        <input type="password" class="form-control" name="password" id="myInput" placeholder="Password" autocomplete="off" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <span id="password" style="color: #FF0000; font-size: 8pt;" class="form-text text-center">@error('password') {{ $message }} @enderror</span>
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-sign-in-alt"></i> Sign In
                              </button>
                        </div>
                    </div>
                </form>   
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-login">
        <strong>Copyrighted &copy; 2025
            <a href="#">Manage by EASE</a>.
        </strong>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>

    <script>
        function myFunction() {
            var x = document.getElementById("myInput");
            x.type = x.type === "password" ? "text" : "password";
        }
    </script>
    
    <script>
        @if(auth()->check())
            window.location.href = "{{ route('dashboard') }}";
        @endif
    </script> 
</body>
</html>
