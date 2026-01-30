@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Tambah User</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Pegawai</label>
                        <select name="employee_id" class="form-control" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->employee_name }} ({{ $employee->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin">admin</option>
                            <option value="pegawai">pegawai</option>
                            <option value="ppnpn">ppnpn</option>
                            <option value="resepsionis">resepsionis</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="text" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Konfirmasi Password</label>
                        <input type="text" name="password_confirmation" class="form-control" required>
                    </div>

                    <button class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    {{-- <div class="container">
        <h4>Tambah User</h4>


    </div> --}}
@endsection
