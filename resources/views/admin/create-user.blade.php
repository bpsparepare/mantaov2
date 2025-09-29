<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/Logo-mantao.png') }}">
    <title>Admin - Buat Akun</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap');
        @import url('https://fonts.googleapis.com/css?family=Raleway:400,700');

        html,
        body {
            width: 100%;
            overflow: hidden;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background: linear-gradient(90deg, #e0eafc, #cfdef3);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            padding: 40px 20px;
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

        .card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(13, 44, 63, 0.2);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            position: relative;
        }

        .form-container {
            padding: 40px;
        }

        h1 {
            font-family: 'Poppins', sans-serif;
            text-align: left;
            color: #0d2c3f;
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 1.8rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #4a5568;
            font-size: 0.9rem;
        }

        input,
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            box-sizing: border-box;
            transition: all 0.3s;
            font-family: 'Raleway', sans-serif;
            font-weight: 600;
            color: #333;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #1a4f6d;
            box-shadow: 0 0 0 3px rgba(26, 79, 109, 0.15);
        }

        select[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background-color: #1a4f6d;
            color: white;
            border: none;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background-color: #0d2c3f;
            transform: translateY(-2px);
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            text-align: left;
            font-weight: 600;
        }

        .alert ul {
            padding-left: 20px;
            margin: 0;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Hapus media query lama karena card sudah dibatasi max-width */
    </style>
</head>

<body>
    <div class="card">
        <div class="form-container">
            <h1>Buat Akun Baru</h1>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.user.create') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="role">Pilih Role Akun:</label>
                    <select id="role" name="role" required>
                        <option value="user" selected>User Instansi</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nama_instansi">Nama Instansi:</label>
                    <select id="nama_instansi" name="nama_instansi" required>
                        <option value="" disabled selected>-- Pilih salah satu --</option>
                        @foreach($all_instansi as $instansi)
                        {{-- Sembunyikan opsi Admin jika role bukan admin, dan sebaliknya --}}
                        <option value="{{ $instansi }}">{{ $instansi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="contoh@email.com">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password:</label>
                    <div class="password-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>
                <button type="submit" class="submit-btn">
                    <span>Buat Akun</span>
                    <i class="fas fa-plus-circle"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const instansiSelect = document.getElementById('nama_instansi');
            const adminInstansiName = 'BPS Kota Parepare (Admin)';

            // Fungsi untuk mengatur keadaan form berdasarkan role
            function handleRoleChange() {
                if (roleSelect.value === 'admin') {
                    instansiSelect.value = adminInstansiName;
                    instansiSelect.setAttribute('readonly', 'readonly');
                    // Menyembunyikan opsi lain kecuali admin
                    for (let option of instansiSelect.options) {
                        if (option.value !== adminInstansiName && option.value !== "") {
                            option.style.display = 'none';
                        }
                    }
                } else {
                    instansiSelect.removeAttribute('readonly');
                    if (instansiSelect.value === adminInstansiName) {
                        instansiSelect.value = ""; // Reset pilihan jika sebelumnya admin
                    }
                    // Menampilkan kembali semua opsi kecuali admin
                    for (let option of instansiSelect.options) {
                        if (option.value === adminInstansiName) {
                            option.style.display = 'none';
                        } else {
                            option.style.display = 'block';
                        }
                    }
                }
            }

            // Jalankan fungsi saat role berubah
            roleSelect.addEventListener('change', handleRoleChange);

            // Jalankan fungsi saat halaman pertama kali dimuat
            handleRoleChange();


            // Fungsi untuk toggle show/hide password
            const togglePasswordIcons = document.querySelectorAll('.toggle-password');
            togglePasswordIcons.forEach(icon => {
                icon.addEventListener('click', function() {
                    const passwordInput = this.previousElementSibling;
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    // Ganti ikon mata
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            });
        });
    </script>
</body>

</html>