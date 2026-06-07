@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between mb-3">
        <h4 class="fw-bold">Manajemen User</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-plus-circle me-1"></i> Tambah User
        </button>
    </div>

    {{-- ERROR VALIDASI --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Statistik --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h6>Total User</h6>
                    <h3>{{ $users->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h6>Sedang Login</h6>
                    <h3>{{ count($activeUserIds) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel User --}}
    <div class="card shadow border-0">
        <div class="card-body">

            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Login</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>

                        <td>
                            <span class="badge 
                                {{ $user->role == 'admin' ? 'bg-primary' : 'bg-warning text-dark' }}
                                text-uppercase">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        <td>
                            <span class="badge {{ $user->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>

                        <td>
                            @if(in_array($user->id, $activeUserIds))
                                <span class="badge bg-success">Online</span>
                            @else
                                <span class="badge bg-secondary">Offline</span>
                            @endif
                        </td>

                        <td>
                            <button class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editUser{{ $user->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <form action="{{ route('user.destroy', $user->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus user?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- MODAL EDIT --}}
                    <div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('user.update', $user->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <input type="text" name="name" class="form-control mb-2"
                                               value="{{ $user->name }}" required>

                                        <input type="email" name="email" class="form-control mb-2"
                                               value="{{ $user->email }}" required>

                                        <select name="role" class="form-control mb-2" required>
                                            <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
                                            <option value="petugas" {{ $user->role=='petugas'?'selected':'' }}>Petugas</option>
                                        </select>

                                        <select name="status" class="form-control mb-2" required>
                                            <option value="aktif" {{ $user->status=='aktif'?'selected':'' }}>Aktif</option>
                                            <option value="nonaktif" {{ $user->status=='nonaktif'?'selected':'' }}>Nonaktif</option>
                                        </select>

                                        <input type="password" name="password"
                                               class="form-control"
                                               placeholder="Password baru (opsional)">

                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @endforeach

                </tbody>
            </table>

        </div>
    </div>
</div>

{{-- MODAL TAMBAH USER --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('user.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="text" name="name" class="form-control mb-2" placeholder="Nama" required>

                    <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>

                    <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

                    <select name="role" class="form-control mb-2" required>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                    </select>

                    {{-- TAMBAH STATUS --}}
                    <select name="status" class="form-control" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
