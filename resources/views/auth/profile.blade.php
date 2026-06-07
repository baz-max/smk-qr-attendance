@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden profile-card">

        {{-- HEADER --}}
        <div class="profile-header text-white p-4 d-flex align-items-center gap-3">

            <div class="avatar-big">
                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
            </div>

            <div>
                <h4 class="mb-0 fw-bold">{{ Auth::user()->name }}</h4>
                <small class="opacity-75 text-uppercase">
                    {{ Auth::user()->role }}
                </small><br>

                <span class="badge bg-light text-success fw-semibold mt-2 px-3 py-2">
                    <i class="bi bi-circle-fill me-1" style="font-size:8px;"></i>
                    Online
                </span>
            </div>

        </div>

        {{-- BODY --}}
        <form method="POST" action="{{ route('profil.update') }}" id="profileForm">
        @csrf

<div class="row g-4 p-3">

    {{-- NAMA --}}
    <div class="col-md-6">
        <div class="info-box editable">
            <div class="label">Nama Lengkap</div>

            <div class="view-mode value">
                {{ Auth::user()->name }}
            </div>

            <input type="text" name="name"
                   value="{{ Auth::user()->name }}"
                   class="form-control edit-mode d-none">
        </div>
    </div>

    {{-- EMAIL --}}
    <div class="col-md-6">
        <div class="info-box editable">
            <div class="label">Email</div>

            <div class="view-mode value">
                {{ Auth::user()->email }}
            </div>

            <input type="email" name="email"
                   value="{{ Auth::user()->email }}"
                   class="form-control edit-mode d-none">
        </div>
    </div>

    {{-- ROLE --}}
    <div class="col-md-6">
        <div class="info-box">
            <div class="label">Role</div>

            <div class="view-mode value fw-semibold text-uppercase
                {{ Auth::user()->role == 'admin' ? 'text-success' : 'text-primary' }}">
                {{ Auth::user()->role }}
            </div>



            <div class="edit-mode d-none">
                <div class="toggle-group mt-1">
                    <input type="radio" name="role" value="admin" id="roleAdmin"
                        {{ Auth::user()->role == 'admin' ? 'checked' : '' }}>
                    <label for="roleAdmin">ADMIN</label>

                    <input type="radio" name="role" value="petugas" id="rolePetugas"
                        {{ Auth::user()->role == 'petugas' ? 'checked' : '' }}>
                    <label for="rolePetugas">PETUGAS</label>
                </div>
            </div>
        </div>
    </div>

    {{-- STATUS --}}
    <div class="col-md-6">
        <div class="info-box">
            <div class="label">Status</div>

            <div class="view-mode value fw-semibold text-uppercase
                {{ Auth::user()->status == 'aktif' ? 'text-success' : 'text-danger' }}">
                {{ Auth::user()->status }}
            </div>

            <div class="edit-mode d-none">
                <div class="toggle-group mt-1">
                    <input type="radio" name="status" value="aktif" id="statusAktif"
                        {{ (Auth::user()->status ?? 'aktif') == 'aktif' ? 'checked' : '' }}>
                    <label for="statusAktif">Aktif</label>

                    <input type="radio" name="status" value="nonaktif" id="statusNonaktif"
                        {{ Auth::user()->status == 'nonaktif' ? 'checked' : '' }}>
                    <label for="statusNonaktif">Non Aktif</label>
                </div>
            </div>
        </div>
    </div>

    {{-- TERAKHIR LOGIN --}}
    <div class="col-md-6">
        <div class="info-box h-100 d-flex align-items-center">
            <div>
                <div class="label">Terakhir Login</div>
                <div class="value">
                    {{ session('login_time') ?? now()->format('d M Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    {{-- BUTTON --}}
    <div class="col-md-6 d-flex align-items-center">
        <div class="w-100 d-flex justify-content-md-end gap-2">

            <button type="button"
                    class="btn btn-outline-primary rounded-3 px-4"
                    id="editProfileBtn">
                <i class="bi bi-person-lines-fill me-1"></i>
                Edit Profile
            </button>

            <button type="submit"
                    class="btn btn-success rounded-3 px-4 d-none"
                    id="saveProfileBtn">
                <i class="bi bi-check2-circle me-1"></i>
                Simpan
            </button>

            <button type="button"
                    class="btn btn-primary rounded-3 px-4"
                    data-bs-toggle="modal"
                    data-bs-target="#editProfileModal">
                <i class="bi bi-pencil-square me-1"></i>
                Ubah Password
            </button>

        </div>
    </div>

</div>
</form>

    </div>
</div>

{{-- MODAL PASSWORD --}}
<div class="modal fade" id="editProfileModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">

      <form method="POST" action="{{ route('profil.update') }}">
        @csrf

        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold">
            <i class="bi bi-person-gear me-2 text-primary"></i>
            Ubah Password
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body pt-2">

          <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password"
                   class="form-control rounded-3"
                   placeholder="Kosongkan jika tidak diubah">
          </div>

          <div class="mb-2">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation"
                   class="form-control rounded-3">
          </div>

        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light rounded-3"
                  data-bs-dismiss="modal">Batal</button>

          <button type="submit" class="btn btn-primary rounded-3 px-4">
            <i class="bi bi-check2-circle me-1"></i>
            Simpan
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<style>
.profile-card{ backdrop-filter: blur(10px); }

.profile-header{
    background: linear-gradient(135deg,#0d6efd,#3b82f6,#6366f1);
}

.avatar-big{
    width:75px;height:75px;border-radius:50%;
    background:white;color:#0d6efd;
    font-size:30px;font-weight:700;
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.info-box{
    background:#f8fafc;border-radius:14px;
    padding:16px 18px;transition:0.2s;
}

.info-box:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.label{ font-size:12px;color:#6c757d;margin-bottom:2px; }
.value{ font-weight:600;font-size:15px; }

.toggle-group{ display:flex;gap:8px; }
.toggle-group input{ display:none; }

.toggle-group label{
    padding:6px 14px;border-radius:999px;
    background:#e9ecef;font-size:13px;
    font-weight:600;cursor:pointer;
    transition:all .2s ease;
}

.toggle-group input:checked + label{
    background:linear-gradient(135deg,#0d6efd,#3b82f6);
    color:white;
    box-shadow:0 4px 10px rgba(13,110,253,0.25);
}
</style>

{{-- TOAST --}}
@if(session('success') || session('error'))
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div class="toast align-items-center text-white border-0 show
      {{ session('success') ? 'bg-success' : 'bg-danger' }}">
    <div class="d-flex">
      <div class="toast-body fw-semibold">
        <i class="bi {{ session('success') ? 'bi-check-circle' : 'bi-x-circle' }} me-2"></i>
        {{ session('success') ?? session('error') }}
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto"
              data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
@endif

<script>
const editBtn = document.getElementById('editProfileBtn');
const saveBtn = document.getElementById('saveProfileBtn');

editBtn.addEventListener('click', function () {

    document.querySelectorAll('.view-mode').forEach(el => el.classList.add('d-none'));
    document.querySelectorAll('.edit-mode').forEach(el => el.classList.remove('d-none'));

    editBtn.classList.add('d-none');
    saveBtn.classList.remove('d-none');
});

function updateRoleView() {
    const roleView = document.querySelector('[data-view="role"]');
    const selected = document.querySelector('input[name="role"]:checked');

    if (!roleView || !selected) return;

    roleView.textContent = selected.value;

    roleView.classList.remove('text-success','text-primary');

    if (selected.value === 'admin') {
        roleView.classList.add('text-success');
    } else {
        roleView.classList.add('text-primary');
    }
}

function updateStatusView() {
    const statusView = document.querySelector('[data-view="status"]');
    const selected = document.querySelector('input[name="status"]:checked');

    if (!statusView || !selected) return;

    statusView.textContent = selected.value;

    statusView.classList.remove('text-success','text-danger');

    if (selected.value === 'aktif') {
        statusView.classList.add('text-success');
    } else {
        statusView.classList.add('text-danger');
    }
}

document.querySelectorAll('input[name="role"]').forEach(radio => {
    radio.addEventListener('change', updateRoleView);
});

document.querySelectorAll('input[name="status"]').forEach(radio => {
    radio.addEventListener('change', updateStatusView);
});

</script>

@endsection
