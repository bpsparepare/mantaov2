<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/Logo-mantao.png') }}">
    <title>@yield('title', 'MANTAO')</title>

    {{-- Link ke file CSS baru di folder public --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- Font Awesome (jika Anda butuh ikon) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* General Body Styles */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f4f6f9;
            color: #343a40;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex-grow: 1;
        }

        /* Footer Styles */
        .app-footer {
            background-color: #343a40;
            color: #f8f9fa;
            text-align: center;
            padding: 20px;
            margin-top: auto;
        }
    </style>
    @stack('styles')
</head>

<body class="@yield('body-class')">

    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- Script untuk efek scroll pada header ---
            const header = document.querySelector('.app-header');
            if (header) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }
                });
            }

            // --- Script untuk tombol hamburger menu ---
            const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
            const mainNavUl = document.querySelector('.main-nav ul');

            // PERBAIKAN UTAMA: Pastikan KEDUA elemen (tombol DAN menu) ditemukan
            if (mobileNavToggle && mainNavUl) {
                mobileNavToggle.addEventListener('click', function() {
                    mainNavUl.classList.toggle('active');
                });
            }
        });
    </script>

</body>

</html>