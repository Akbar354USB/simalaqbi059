@extends('master')

@section('content')
    <div class="container mt-4">
        <h4>Data Hari Libur & Cuti</h4>

        <!-- 🔥 Ubah ke halaman create -->
        <a href="{{ route('holidays.create') }}" class="btn btn-primary mb-3">
            Tambah Data
        </a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($holidays as $h)
                    <tr>
                        <td>{{ $h->name }}</td>
                        <td>
                            {{ $h->start_date }}
                            @if ($h->end_date)
                                s/d {{ $h->end_date }}
                            @endif
                        </td>
                        <td>
                            <!-- (Optional) nanti edit bisa juga dipisah halaman -->
                            <button class="btn btn-warning btn-sm" onclick='editData(@json($h))'
                                data-bs-toggle="modal" data-bs-target="#modalForm">
                                Edit
                            </button>

                            <form action="{{ route('holidays.delete', $h->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
