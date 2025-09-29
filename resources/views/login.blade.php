<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - MANTAO</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo-mantao.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Raleway:400,700');
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap');

        html,
        body {
            width: 100%;
            overflow: hidden;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Raleway', sans-serif;
        }

        body {
            background: linear-gradient(90deg, #e0eafc, #cfdef3);
            position: relative;
        }

        body::before,
        body::after {
            content: '';
            position: absolute;
            z-index: -1;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.4);
        }

        body::before {
            width: 350px;
            height: 350px;
            top: -120px;
            left: -100px;
        }

        body::after {
            width: 450px;
            height: 450px;
            bottom: -150px;
            right: -150px;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .screen {
            background: linear-gradient(90deg, #0d2c3f, #1a4f6d);
            position: relative;
            min-height: 500px;
            width: 360px;
            box-shadow: 0px 0px 24px rgba(13, 44, 63, 0.4);
            border-radius: 15px;
            overflow: hidden;
        }

        .screen__content {
            z-index: 1;
            position: relative;
            height: 100%;
        }

        .screen__background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
            clip-path: inset(0 0 0 0);
        }

        .screen__background__shape {
            transform: rotate(45deg);
            position: absolute;
        }

        .screen__background__shape1 {
            height: 520px;
            width: 520px;
            background: #FFF;
            top: -50px;
            right: 120px;
            border-radius: 0 72px 0 0;
        }

        .screen__background__shape2 {
            height: 220px;
            width: 220px;
            background: #1a4f6d;
            top: -172px;
            right: 0;
            border-radius: 32px;
        }

        .screen__background__shape3 {
            height: 540px;
            width: 190px;
            background: linear-gradient(270deg, #0d2c3f, #1a3a4f);
            top: -24px;
            right: 0;
            border-radius: 32px;
        }

        .screen__background__shape4 {
            height: 400px;
            width: 200px;
            background: #1a3a4f;
            top: 420px;
            right: 50px;
            border-radius: 60px;
        }

        .login {
            width: 100%;
            padding: 30px;
            padding-top: 80px;
        }

        .login h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #0d2c3f;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-align: center;
            margin-bottom: 40px;
        }

        .login__field {
            padding: 20px 0px;
            position: relative;
        }

        .login__icon {
            position: absolute;
            top: 30px;
            color: #1a4f6d;
        }

        .login__input {
            border: none;
            border-bottom: 2px solid #D1D1D4;
            background: none;
            padding: 10px;
            padding-left: 24px;
            font-weight: 700;
            width: 100%;
            transition: .2s;
            color: #333;
        }

        .login__input::placeholder {
            color: #AAA;
            font-weight: 400;
        }

        .login__input:active,
        .login__input:focus,
        .login__input:hover {
            outline: none;
            border-bottom-color: #1a4f6d;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .login__input {
            padding-right: 35px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 5px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 18px;
        }

        .login__submit {
            background: #fff;
            font-size: 14px;
            margin-top: 30px;
            padding: 16px 20px;
            border-radius: 26px;
            border: 1px solid #D4D3E8;
            text-transform: uppercase;
            font-weight: 700;
            display: flex;
            align-items: center;
            width: 100%;
            color: #0d2c3f;
            box-shadow: 0px 2px 2px #0d2c3f;
            cursor: pointer;
            transition: .2s;
        }

        .login__submit:active,
        .login__submit:focus,
        .login__submit:hover {
            border-color: #0d2c3f;
            outline: none;
        }

        .button__icon {
            font-size: 24px;
            margin-left: auto;
            color: #1a4f6d;
        }

        .notification {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            color: white;
            background-color: rgba(220, 53, 69, 0.7);
            font-size: 14px;
        }

        /* --- CSS RESPONSIVE --- */
        @media (max-width: 400px) {
            .screen {
                width: 100%;
                min-height: 0;
            }

            .screen__background__shape {
                display: none;
            }

            .screen__background__shape1 {
                display: block;
                right: 40px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <form class="login" action="{{ route('login') }}" method="POST">
                    @csrf
                    <h2>MANTAO</h2>
                    @if ($errors->any())
                    <div class="notification">
                        @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="email" name="email" class="login__input" placeholder="Email" required>
                    </div>
                    <div class="login__field password-wrapper">
                        <i class="login__icon fas fa-lock"></i>
                        <input type="password" id="password-field" name="password" class="login__input" placeholder="Password" required>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                    <button class="button login__submit">
                        <span class="button__text">Log In</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>
                </form>
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePasswordIcon = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('password-field');
            if (togglePasswordIcon && passwordInput) {
                togglePasswordIcon.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>

</html>