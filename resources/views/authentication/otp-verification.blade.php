<!--
=========================================================
* Soft UI Dashboard - v1.0.3
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
    <link rel="icon" type="image/png"
        href="{{ url('https://www.ifpusa.com/wp-content/uploads/2021/11/KYB%20DRUPAL%20LOGO.png') }}">
    <title>
        Kayaba Indonesia
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Host+Grotesk:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Nucleo Icons -->
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="{{ asset('css/login.css') }}" rel="stylesheet" />
</head>

<style>
    .otp-input-container input {
        width: 50px;
        height: 50px;
        font-size: 24px;
        text-align: center;
        border: 2px solid #ccc;
        border-radius: 8px;
    }

    .otp-input-container input:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 8px var(--primary-color);
    }

    footer.footer {
        position: sticky;
        bottom: 0;
        background: white;
        z-index: 1000;
    }

    @media (max-height: 500px) {

        footer.footer {
            display: none;
        }
    }
</style>

<body class="d-flex flex-column min-vh-100">
    <main class="d-flex flex-grow-1 align-items-center justify-content-center">
        <section class="flex-grow-1">
            <div class="page-header min-vh-75">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if (session('error'))
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    </div>
                </div>
                @endif
                @if(session('message'))
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="alert alert-success">{{ session('message') }}</div>
                    </div>
                </div>
                @endif

                <div class="wrapper">
                    <div class="container-main shadow-lg p-5 text-center w-100 col-lg-6 col-md-8 col-sm-10 mx-auto">
                        <header class="mb-4">
                            <h3 class="text-dark">Verifikasi OTP</h3>
                            <p class="text-dark">Masukkan kode OTP yang telah dikirim ke nomor Anda</p>
                        </header>
                        <form action="{{ route('verify-otp') }}" method="POST" id="otp-form">
                            @csrf
                            <div class="otp-input-container d-flex justify-content-center gap-2 mb-4">
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]*" inputmode="numeric">
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]*" inputmode="numeric">
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]*" inputmode="numeric">
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]*" inputmode="numeric">
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]*" inputmode="numeric">
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]*" inputmode="numeric">
                            </div>
                            <input type="hidden" name="otp" id="otp_combined">
                            <button type="submit" class="btn btn-danger w-100 mt-3">Verifikasi</button>
                        </form>
                        <div class="d-flex align-items-center justify-content-center gap-2 mt-3 mb-3">
                            <form action="{{ route('resend-otp') }}" method="post" id="resend-form">
                                @csrf
                                <div class="row text-center">
                                    <div class="col-md-7">
                                        <p class="text-dark mb-0">Kirim ulang OTP</p>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="badge bg-success border-0">Resend OTP</button>

                                    </div>
                                </div>
                            </form>
                        </div>

                        <span class="badge bg-danger fs-6" id="countdown"
                            data-expiry="{{ session('otp_expiry') }}"></span>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <footer class="footer text-center mt-auto py-md-3">
        <div class="container">
            <div class="row">
                <div class="col-12 mx-auto text-center">
                    <p class="mb-0 text-secondary">
                        Copyright © <script>
                            document.write(new Date().getFullYear())
                        </script> PT Kayaba Indonesia
                    </p>
                </div>
            </div>
        </div>
    </footer>


    <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <!--   Core JS Files   -->
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{ asset('js/soft-ui-dashboard.min.js?v=1.0.3') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/otp-timer.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
        const inputs = document.querySelectorAll(".otp-input");
        const otpForm = document.getElementById("otp-form");
        const otpHiddenInput = document.getElementById("otp_combined");

        inputs.forEach((input, index) => {
            input.addEventListener("input", (e) => {
                if (e.target.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            input.addEventListener("keydown", (e) => {
                if (e.key === "Backspace" && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        otpForm.addEventListener("submit", function(e) {
            let otpValue = "";
            inputs.forEach(input => {
                otpValue += input.value;
            });

            otpHiddenInput.value = otpValue; // Simpan OTP di input hidden sebelum submit

            if (otpValue.length !== 6) {
                e.preventDefault(); // Jangan submit jika OTP belum lengkap
                alert("Masukkan 6 digit OTP!");
            }
        });
    });
    </script>
    <script>
        window.addEventListener('resize', function () {
            const footer = document.querySelector('footer.footer');
            if (window.innerHeight < 500) {
                footer.style.display = 'none';
            } else {
                footer.style.display = 'block';
            }
        });
    </script>


</body>

</html>