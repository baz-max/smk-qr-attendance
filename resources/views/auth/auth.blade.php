<!DOCTYPE html>
<html>
<head>
    <title>Authentication</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body{
            background:linear-gradient(135deg,#eef2ff,#f8fafc);
            height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            font-family:'Segoe UI',sans-serif;
        }

        .auth-wrapper{
            width:980px;
            height:600px;
            background:#fff;
            border-radius:28px;
            overflow:hidden;
            box-shadow:0 40px 80px rgba(0,0,0,.12);
            position:relative;
        }

        .auth-panel{
            position:absolute;
            width:50%;
            height:100%;
            padding:80px 70px;
            transition:.7s cubic-bezier(.77,0,.18,1);
        }

        .left-panel{
            background:linear-gradient(135deg,#1e3a8a,#2563eb);
            color:#fff;
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            text-align:center;
        }

        .left-panel h2{
            font-weight:700;
            letter-spacing:.5px;
        }

        .left-panel i{
            font-size:60px;
            margin-bottom:20px;
            opacity:.9;
        }

        .right-panel{
            right:0;
            background:#fff;
        }

        .auth-wrapper.active .left-panel{
            transform:translateX(100%);
        }

        .auth-wrapper.active .right-panel{
            transform:translateX(-100%);
        }

        .form-control{
            border-radius:12px;
            padding:12px 40px 12px 40px;
            height:48px;
        }

        .input-group-text{
            background:transparent;
            border:none;
            position:absolute;
            z-index:10;
            height:100%;
        }

        .form-group{
            position:relative;
        }

        .form-group i{
            position:absolute;
            left:12px;
            top:50%;
            transform:translateY(-50%);
            color:#6b7280;
        }

        .toggle-password{
            position:absolute;
            right:15px;
            top:50%;
            transform:translateY(-50%);
            cursor:pointer;
            color:#6b7280;
        }

        .btn-auth{
            border-radius:30px;
            padding:12px;
            background:linear-gradient(90deg,#2563eb,#1e40af);
            border:none;
            color:#fff;
            font-weight:600;
            transition:.3s;
        }

        .btn-auth:hover{
            transform:translateY(-2px);
            box-shadow:0 10px 25px rgba(37,99,235,.3);
        }

        .switch-link{
            cursor:pointer;
            font-weight:600;
            color:#2563eb;
            transition:.3s;
        }

        .switch-link:hover{
            text-decoration:underline;
        }

        .fade-slide{
            animation:fadeSlide .5s ease;
        }

        @keyframes fadeSlide{
            from{opacity:0; transform:translateY(10px);}
            to{opacity:1; transform:translateY(0);}
        }

        @media(max-width:768px){
            .auth-wrapper{
                width:95%;
                height:auto;
            }
            .auth-panel{
                position:relative;
                width:100%;
                transform:none !important;
            }
            .left-panel{
                display:none;
            }
        }
        .modern-input{
    border-radius:14px;
    padding:14px 50px 14px 45px;
    height:52px;
    border:1px solid #e5e7eb;
    background:#f9fafb;
    transition:.25s;
    font-weight:500;
}

.modern-input:focus{
    background:#fff;
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

.input-icon{
    position:absolute;
    left:15px;
    top:50%;
    transform:translateY(-50%);
    color:#9ca3af;
    font-size:18px;
}

.toggle-password{
    position:absolute;
    right:15px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    color:#9ca3af;
    font-size:18px;
}

    </style>
</head>
<body>

<div class="auth-wrapper {{ $errors->any() ? 'active' : '' }}" id="authBox">

    {{-- LEFT BRANDING --}}
    <div class="auth-panel left-panel">
    <i id="leftIcon" class="bi bi-qr-code-scan"></i>

    <h2 id="leftTitle">Sistem Absensi QR</h2>

    <p id="leftDesc" class="mt-2 opacity-75">
        Digitalisasi Absensi
    </p>

    <p class="opacity-75">
        SMK Muhammadiyah Pandeglang
    </p>

    <p class="mt-4">
        <span id="leftSwitchText" class="switch-link text-white"
              onclick="toggleAuth()">Masuk / Daftar</span>
    </p>
</div>

    {{-- RIGHT FORM --}}
    <div class="auth-panel right-panel">

        {{-- LOGIN --}}
        <div id="loginForm" class="fade-slide" style="{{ session('showRegister') ? 'display:none' : '' }}">
            <h4 class="mb-4 fw-bold">Login</h4>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login.process') }}" autocomplete="off">
                @csrf

                <div class="mb-3 position-relative">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email"
                        class="form-control modern-input"
                        placeholder="Email"
                        autocomplete="off" required>
                </div>

                <div class="mb-3 position-relative">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password"
                        id="loginPassword"
                        class="form-control modern-input"
                        placeholder="Password"
                        autocomplete="new-password" required>

                    <i class="bi bi-eye toggle-password"
                    onclick="togglePassword('loginPassword', this)"></i>
                </div>

                <button class="btn btn-auth w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
            </form>

            <p class="mt-3">
                Belum punya akun?
                <span class="switch-link" onclick="toggleAuth()">Register</span>
            </p>
        </div>

        {{-- REGISTER --}}
        <div id="registerForm" class="fade-slide"
             style="{{ session('showRegister') || $errors->any() ? '' : 'display:none' }}">

            <h4 class="mb-4 fw-bold">Register</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="mb-3 position-relative">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" name="name"
                        class="form-control modern-input"
                        placeholder="Nama Lengkap"
                        value="{{ old('name') }}"
                        autocomplete="off" required>
                </div>

                <div class="mb-3 position-relative">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email"
                        class="form-control modern-input"
                        placeholder="Email"
                        value="{{ old('email') }}"
                        autocomplete="off" required>
                </div>


                <div class="mb-3 position-relative">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password"
                        id="registerPassword"
                        class="form-control modern-input"
                        placeholder="Password (min 6 karakter)"
                        autocomplete="new-password" required>

                    <i class="bi bi-eye toggle-password"
                    onclick="togglePassword('registerPassword', this)"></i>
                </div>

                <div class="mb-3 position-relative">
                    <i class="bi bi-shield-lock input-icon"></i>
                    <input type="password" name="password_confirmation"
                        id="confirmPassword"
                        class="form-control modern-input"
                        placeholder="Konfirmasi Password"
                        autocomplete="new-password" required>

                    <i class="bi bi-eye toggle-password"
                    onclick="togglePassword('confirmPassword', this)"></i>
                </div>

                <button class="btn btn-auth w-100">
                    <i class="bi bi-person-plus me-2"></i>Register
                </button>
            </form>

            <p class="mt-3">
                Sudah punya akun?
                <span class="switch-link" onclick="toggleAuth()">Login</span>
            </p>
        </div>

    </div>
</div>

<script>
function toggleAuth(){
    const box = document.getElementById('authBox');
    const login = document.getElementById('loginForm');
    const register = document.getElementById('registerForm');

    const title = document.getElementById('leftTitle');
    const desc = document.getElementById('leftDesc');
    const icon = document.getElementById('leftIcon');
    const switchText = document.getElementById('leftSwitchText');

    box.classList.toggle('active');

    if(register.style.display === "none"){
        // 👉 PINDAH KE REGISTER
        register.style.display = "block";
        login.style.display = "none";

        title.innerText = "Buat Akun Baru";
        desc.innerText = "Kelola Absensi Lebih Mudah";
        switchText.innerText = "Sudah punya akun? Login";

        icon.classList.remove("bi-qr-code-scan");
        icon.classList.add("bi-person-plus");
    } else {
        // 👉 PINDAH KE LOGIN
        register.style.display = "none";
        login.style.display = "block";

        title.innerText = "Sistem Absensi QR";
        desc.innerText = "Digitalisasi Absensi";
        switchText.innerText = "Masuk / Daftar";

        icon.classList.remove("bi-person-plus");
        icon.classList.add("bi-qr-code-scan");
    }
}
function togglePassword(id, icon){
    const input = document.getElementById(id);

    if(input.type === "password"){
        // 👉 Tampilkan password
        input.type = "text";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    } else {
        // 👉 Sembunyikan password
        input.type = "password";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    }
}
window.addEventListener("DOMContentLoaded", function () {
    // Set semua password field ke hidden
    document.querySelectorAll("input[type='password']").forEach(input => {
        input.type = "password";
    });

    // Set semua icon ke mata tertutup
    document.querySelectorAll(".toggle-password").forEach(icon => {
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    });
});



</script>

</body>
</html>
