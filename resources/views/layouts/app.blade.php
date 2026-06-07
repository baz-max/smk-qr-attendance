<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Absensi SMK') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .navbar-custom {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }

        .brand-text {
            font-weight: 700;
            font-size: 20px;
            letter-spacing: 0.5px;
        }

        .brand-text span {
            color: #2563eb;
        }

        .nav-link {
            font-weight: 500;
        }

        .btn-logout {
            border-radius: 12px;
            padding: 6px 16px;
        }
        h1, h2, h3, h4, h5 {
    font-weight: 600;
    letter-spacing: -0.3px;
}

.user-panel{
    position:absolute;
    right:0;
    top:120%;
    width:280px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(14px);
    border-radius:16px;
    display:none;
    animation: fadeSlide .25s ease;
    z-index:999;
}

@keyframes fadeSlide{
    from{opacity:0; transform:translateY(-10px);}
    to{opacity:1; transform:translateY(0);}
}

.dropdown-item{
    border-radius:10px;
    font-weight:500;
}

.dropdown-item:hover{
    background:#f1f5f9;
}

.avatar-mini{
    width:35px;
    height:35px;
    border-radius:50%;
    background:linear-gradient(135deg,#2563eb,#06b6d4);
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
    font-size:14px;
}


.user-btn{
    display:flex;
    align-items:center;
    gap:10px;
    background:#f1f5f9;
    border:none;
    padding:6px 10px;
    border-radius:999px;
    cursor:pointer;
    transition:.2s;
}

.user-btn:hover{
    background:#e2e8f0;
}



.gear-icon{
    font-size:18px;
    color:#2563eb;
}

.avatar-navbar-initial{
    width:34px;
    height:34px;
    border-radius:50%;
    background:linear-gradient(135deg,#2563eb,#06b6d4);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    font-size:14px;
}

/* ================= DARK MODE ================= */
body.dark-mode{
    background:#0f172a;
    color:#e5e7eb;
}

body.dark-mode .navbar-custom{
    background:#020617;
    border-bottom:1px solid #1e293b;
}

body.dark-mode .brand-text{
    color:#e5e7eb;
}

body.dark-mode .card{
    background:#020617;
    color:#e5e7eb;
    border:1px solid #1e293b;
}

body.dark-mode .user-panel{
    background:rgba(2,6,23,0.95);
    color:#e5e7eb;
}

body.dark-mode .dropdown-item:hover{
    background:#1e293b;
}

body.dark-mode .user-btn{
    background:#020617;
}

body.dark-mode .user-btn:hover{
    background:#1e293b;
}

body.dark-mode .form-control{
    background:#020617;
    border:1px solid #1e293b;
    color:#e5e7eb;
}

body.dark-mode .form-control::placeholder{
    color:#94a3b8;
}

body.dark-mode .btn-outline-danger{
    border-color:#ef4444;
    color:#ef4444;
}

/* ===== UNIVERSAL TEXT ===== */
body.dark-mode .text-dark{
    color:#e5e7eb !important;
}

body.dark-mode .text-muted{
    color:#94a3b8 !important;
}

/* ===== UNIVERSAL BACKGROUND ===== */
body.dark-mode .bg-white,
body.dark-mode .bg-light{
    background:#020617 !important;
    color:#e5e7eb !important;
}

/* ===== BORDER ===== */
body.dark-mode .border,
body.dark-mode .border-top,
body.dark-mode .border-bottom,
body.dark-mode .border-start,
body.dark-mode .border-end{
    border-color:#1e293b !important;
}

/* ===== TABLE ===== */
body.dark-mode table{
    color:#e5e7eb;
}

body.dark-mode thead{
    background:#020617;
}

body.dark-mode tbody tr{
    border-color:#1e293b;
}

/* ===== MODAL ===== */
body.dark-mode .modal-content{
    background:#020617;
    color:#e5e7eb;
    border:1px solid #1e293b;
}

/* ===== DROPDOWN MENU (BOOTSTRAP DEFAULT) ===== */
body.dark-mode .dropdown-menu{
    background:#020617;
    color:#e5e7eb;
    border:1px solid #1e293b;
}

/* ===== LIST GROUP ===== */
body.dark-mode .list-group-item{
    background:#020617;
    color:#e5e7eb;
    border-color:#1e293b;
}

/* ===== BUTTON LIGHT ===== */
body.dark-mode .btn-light{
    background:#020617;
    color:#e5e7eb;
    border:1px solid #1e293b;
}

/* ===== PROGRESS BAR ===== */
body.dark-mode .progress{
    background:#020617;
}

body.dark-mode .progress-bar{
    background:#22c55e;
}

/* ===== FORM SELECT ===== */
body.dark-mode .form-select{
    background:#020617;
    border:1px solid #1e293b;
    color:#e5e7eb;
}

/* ===== SCROLLBAR ===== */
body.dark-mode ::-webkit-scrollbar{
    width:8px;
}
body.dark-mode ::-webkit-scrollbar-track{
    background:#020617;
}
body.dark-mode ::-webkit-scrollbar-thumb{
    background:#1e293b;
    border-radius:10px;
}



    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-custom px-4">
    <div class="container-fluid">

        {{-- LEFT --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <i class="bi bi-qr-code-scan text-primary fs-4"></i>
            <div class="brand-text">
                <span>ABSENSI</span> SMK MUHAMMADIYAH PANDEGLANG
            </div>
        </a>

        {{-- RIGHT --}}
        <div class="d-flex align-items-center gap-4 ms-auto">

           

            <div class="position-relative">

    <button class="user-btn" id="userMenuBtn">

    <div class="avatar-navbar-initial">
    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
</div>




    <i class="bi bi-gear gear-icon"></i>

</button>


    {{-- POPUP USER PANEL --}}
    <div class="user-panel shadow-lg" id="userPanel">

           {{-- HEADER PROFILE (AVATAR + NAMA) --}}
    <div class="p-3 border-bottom d-flex align-items-center gap-2">
        <div class="avatar-mini">
    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
</div>

        <div>
<div class="fw-semibold">{{ auth()->user()->name }}</div>
            <small class="text-success">● Online</small>
        </div>
    </div>

        <div class="p-2">

            <a href="/profil" class="dropdown-item">
                <i class="bi bi-person-circle me-2"></i> Profile
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>

        </div>

    </div>
</div>

           <form method="POST" action="{{ route('logout') }}">
                 @csrf
                <button class="btn btn-outline-danger btn-logout">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>

        </div>
    </div>
</nav>

{{-- CONTENT --}}
<div class="container-fluid py-4 px-4">
    @yield('content')
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const userBtn = document.getElementById('userMenuBtn');
const userPanel = document.getElementById('userPanel');

userBtn.addEventListener('click', function(e){
    e.stopPropagation();
    userPanel.style.display =
        userPanel.style.display === 'block' ? 'none' : 'block';
});

document.addEventListener('click', function(){
    userPanel.style.display = 'none';
});

const darkToggle = document.getElementById('darkModeToggle');

// load preferensi saat page dibuka
if(localStorage.getItem('darkMode') === 'on'){
    document.body.classList.add('dark-mode');
    darkToggle.checked = true;
}

// saat toggle diklik
darkToggle.addEventListener('change', function(){
    if(this.checked){
        document.body.classList.add('dark-mode');
        localStorage.setItem('darkMode','on');
    }else{
        document.body.classList.remove('dark-mode');
        localStorage.setItem('darkMode','off');
    }
});

</script>


</body>
</html>
