@extends('master')

@section('content')
    <div class="container">

        <a href="{{ route('overtime.create') }}" class="btn btn-primary mb-3">
            + Ajukan Lembur
        </a>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pegawai</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Durasi</th>
                    <th>Foto</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($overtimes as $key => $overtime)
                    <tr>
                        <td>{{ $loop->iteration + $overtimes->firstItem() - 1 }}</td>
                        <td>{{ $overtime->employee->employee_name }}</td>
                        <td>{{ $overtime->overtime_date->format('d-m-Y') }}</td>
                        <td>{{ $overtime->start_time }} - {{ $overtime->end_time }}</td>
                        <td>{{ $overtime->total_hours }} Jam</td>
                        <td>
                            <a href="{{ asset('storage' . $overtime->photo_url) }}?t={{ time() }}" target="_blank">
                                <img src="{{ asset('storage' . $overtime->photo_url) }}?t={{ time() }}"
                                    class="img-thumbnail" style="width:60px; height:60px; object-fit:cover;"
                                    title="foto lembur">
                            </a>
                        </td>
                        <td>
                            <span
                                class="badge bg-{{ $overtime->status == 'approved' ? 'success' : ($overtime->status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ strtoupper($overtime->status) }}
                            </span>
                        </td>
                        <td>
                            @if ($overtime->status == 'pending')
                                <a href="{{ route('overtime.edit', $overtime->id) }}" class="btn btn-sm btn-warning"><i
                                        class="fas fa-edit"></i></a>

                                <form action="{{ route('overtime.destroy', $overtime->id) }}" method="POST"
                                    style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>

                                @if ($isApprover)
                                    <form action="{{ route('overtime.approve', $overtime->id) }}" method="POST"
                                        style="display:inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Setujui</button>
                                    </form>

                                    <form action="{{ route('overtime.reject', $overtime->id) }}" method="POST"
                                        style="display:inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger">Tolak</button>
                                    </form>
                                @endif
                            @else
                                <form action="{{ route('overtime.destroy', $overtime->id) }}" method="POST"
                                    style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Menampilkan {{ $overtimes->firstItem() }} – {{ $overtimes->lastItem() }}
                dari {{ $overtimes->total() }} data
            </div>
            <div>
                {{ $overtimes->appends(request()->query())->links() }}
            </div>
        </div>

    </div>
@endsection
@section('js')
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        </script>
    @endif
@endsection
