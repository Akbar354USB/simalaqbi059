@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Manajemen User</h5>
            </div>

            <div class="card-body">
                <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Tambah User</a>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pegawai</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->employee->employee_name }}</td>
                                    <td>{{ $user->employee->email }}</td>
                                    <td>
                                        <span class="badge bg-info text-uppercase">{{ $user->role }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning"><i
                                                class="fas fa-edit"></i></a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus user ini?')"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Data belum tersedia</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $users->links() }}
            </div>
        </div>
    </div>


    {{-- <div class="container">
        <h4 class="mb-3">Manajemen User</h4>


    </div> --}}
@endsection
