@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between">
                <h5>Data Wajah Pegawai</h5>
                <a href="{{ route('employee-faces.create') }}" class="btn btn-primary btn-sm">
                    Tambah Wajah
                </a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pegawai</th>
                            <th>Jumlah Descriptor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($faces as $face)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $face->employee->name }}</td>
                                <td>{{ count($face->face_descriptor) }}</td>
                                <td>
                                    <a href="{{ route('employee-faces.show', $face) }}" class="btn btn-info btn-sm">Detail</a>
                                    <a href="{{ route('employee-faces.edit', $face) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('employee-faces.destroy', $face) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus data wajah?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
