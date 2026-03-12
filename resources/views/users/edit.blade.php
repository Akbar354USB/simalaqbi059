@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Edit Data User</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label>Pegawai</label>
                        <select name="employee_id" class="form-control">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ $employee->id == $user->employee_id ? 'selected' : '' }}>
                                    {{ $employee->employee_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-control">
                            <option value="pegawai" {{ $user->role === 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                            <option value="superadmin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="ppnpn" {{ $user->role === 'ppnpn' ? 'selected' : '' }}>PPNPN</option>
                            <option value="resepsionis" {{ $user->role === 'resepsionis' ? 'selected' : '' }}>Resepsionis
                            </option>
                            <option value="unit_head" {{ $user->role === 'unit_head' ? 'selected' : '' }}>Kepala Seksi /
                                Kasubag</option>
                            <option value="head_office" {{ $user->role === 'head_office' ? 'selected' : '' }}>Kepala Kantor
                            </option>

                            {{-- <option value="superadmin">admin</option>
                            <option value="pegawai">pegawai</option>
                            <option value="ppnpn">ppnpn</option>
                            <option value="resepsionis">resepsionis</option>
                            <option value="unit_head">Pimpinan Unit/Seksi</option>
                            <option value="head_office">Kepala Kantor</option> --}}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Password (kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <button class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
