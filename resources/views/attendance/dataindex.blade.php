@extends('master')

@section('content')
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">📋 Data Absensi Pegawai</h5>
            </div>

            <div class="card-body">

                {{-- FILTER --}}
                <form method="GET" action="{{ route('attendances.data') }}">
                    <div class="row align-items-end mb-3">

                        <div class="col-md-3">
                            <label>Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Bulan</label>
                            <input type="month" name="month" class="form-control" value="{{ request('month') }}">
                        </div>

                        <div class="col-md-6 d-flex justify-content-end gap-2">

                            {{-- FILTER --}}
                            <button type="submit" class="btn btn-primary">
                                🔍 Filter
                            </button>

                            {{-- RESET --}}
                            <a href="{{ route('attendances.data') }}" class="btn btn-secondary ml-2">
                                🔄 Reset
                            </a>

                            {{-- PDF --}}
                            <a href="{{ route('attendances.printPdf', request()->query()) }}" class="btn btn-success ml-2"
                                target="_blank">
                                🖨️ PDF
                            </a>

                        </div>
                    </div>
                </form>

                {{-- HAPUS SEMUA (FORM TERPISAH, JANGAN DIGABUNG FILTER) --}}
                <form action="{{ route('attendances.destroyAll') }}" method="POST" class="d-inline form-delete-all">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="btn btn-danger btn-delete-all float-right mb-2">
                        🗑 Hapus Semua
                    </button>
                </form>

                {{-- TABLE --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Pegawai</th>
                                <th>Tanggal</th>
                                <th>Shift</th>

                                <th>Datang</th>
                                <th>Status</th>
                                <th>Pulang</th>
                                <th>Status</th>

                                <th>Jarak (m)</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration + $attendances->firstItem() - 1 }}</td>

                                    <td>{{ $attendance->employee->employee_name ?? '-' }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($attendance->attendance_date)->translatedFormat('d M Y') }}
                                    </td>

                                    <td>{{ $attendance->workShift->shift_name ?? '-' }}</td>

                                    {{-- DATANG --}}
                                    <td>
                                        {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i:s') : '-' }}
                                    </td>

                                    <td>
                                        @if ($attendance->check_in_status)
                                            <span
                                                class="badge badge-{{ $attendance->check_in_status === 'ON_TIME' ? 'success' : 'danger' }}">
                                                {{ $attendance->check_in_status }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- PULANG --}}
                                    <td>
                                        {{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i:s') : '-' }}
                                    </td>

                                    <td>
                                        @if ($attendance->check_out_status)
                                            <span class="badge badge-warning">
                                                {{ $attendance->check_out_status }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- JARAK --}}
                                    <td>
                                        {{ $attendance->check_out_distance_meter ?? ($attendance->check_in_distance_meter ?? '-') }}
                                    </td>

                                    {{-- FOTO --}}
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">

                                            {{-- FOTO DATANG --}}
                                            @if ($attendance->check_in_photo_path)
                                                <a href="{{ asset('storage/' . $attendance->check_in_photo_path) }}"
                                                    target="_blank">
                                                    <img src="{{ asset('storage/' . $attendance->check_in_photo_path) }}"
                                                        class="img-thumbnail"
                                                        style="width:60px; height:60px; object-fit:cover;"
                                                        title="Foto Datang">
                                                </a>
                                            @endif

                                            {{-- FOTO PULANG --}}
                                            @if ($attendance->check_out_photo_path)
                                                <a href="{{ asset('storage/' . $attendance->check_in_photo_path) }}"
                                                    target="_blank">
                                                    <img src="{{ asset('storage/' . $attendance->check_in_photo_path) }}"
                                                        class="img-thumbnail"
                                                        style="width:60px; height:60px; object-fit:cover;"
                                                        title="Foto Pulang">
                                                </a>
                                            @endif

                                            {{-- JIKA TIDAK ADA FOTO --}}
                                            @if (!$attendance->check_in_photo_path && !$attendance->check_out_photo_path)
                                                -
                                            @endif

                                        </div>
                                    </td>

                                    {{-- AKSI --}}
                                    <td>
                                        <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button" class="btn btn-danger btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="12">Data absensi belum tersedia</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $attendances->firstItem() }} – {{ $attendances->lastItem() }}
                        dari {{ $attendances->total() }} data
                    </div>
                    <div>
                        {{ $attendances->appends(request()->query())->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('js')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: '{{ session('success_type') === 'all' ? 'warning' : 'success' }}',
                title: 'Berhasil',
                text: '{{ session('success') }}'
            });
        </script>
    @endif
    <script>
        // HAPUS PER BARIS
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Yakin?',
                    text: 'Data absensi dan foto akan dihapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // HAPUS SEMUA
        document.querySelector('.btn-delete-all')?.addEventListener('click', function() {
            const form = this.closest('form');

            Swal.fire({
                title: 'PERINGATAN!',
                html: '<b>SEMUA</b> data absensi dan <b>SEMUA FOTO</b> akan dihapus permanen.<br><br>Lanjutkan?',
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus SEMUA!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
