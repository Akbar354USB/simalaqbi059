@extends('master')

@section('content')
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">📋 Data Absensi Pegawai</h5>
            </div>

            <div class="card-body">


                {{-- <form method="GET" action="{{ route('attendances.data') }}">
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


                            <button type="submit" class="btn btn-primary">
                                🔍 Filter
                            </button>


                            <a href="{{ route('attendances.data') }}" class="btn btn-secondary ml-2">
                                🔄 Reset
                            </a>

                            <a href="{{ route('attendances.printPdf', request()->query()) }}" class="btn btn-success ml-2"
                                target="_blank">
                                🖨️ PDF
                            </a>

                        </div>
                    </div>
                </form> --}}

                {{-- FILTER --}}
                <form method="GET" action="{{ route('attendances.data') }}">
                    <div class="row align-items-end mb-3">

                        <div class="col-md-2">
                            <label>Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>

                        <div class="col-md-2">
                            <label>Bulan</label>
                            <input type="month" name="month" class="form-control" value="{{ request('month') }}">
                        </div>

                        {{-- FILTER PEGAWAI --}}
                        <div class="col-md-3">
                            <label>Nama Pegawai</label>
                            <select name="employee_id" class="form-control">
                                <option value="">-- Semua Pegawai --</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->employee_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- FILTER SHIFT --}}
                        <div class="col-md-3">
                            <label>Shift</label>
                            <select name="work_shift_id" class="form-control">
                                <option value="">-- Semua Shift --</option>
                                @foreach ($workShifts as $shift)
                                    <option value="{{ $shift->id }}"
                                        {{ request('work_shift_id') == $shift->id ? 'selected' : '' }}>
                                        {{ $shift->shift_name }}
                                        ({{ $shift->start_time }} - {{ $shift->end_time }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex gap-2 ms-n3">

                            {{-- FILTER --}}
                            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center"
                                style="width:45px; height:45px;" title="Filter">
                                <i class="ti ti-filter fs-3"></i>
                            </button>

                            {{-- RESET --}}
                            <a href="{{ route('attendances.data') }}"
                                class="btn btn-secondary d-flex align-items-center justify-content-center"
                                style="width:45px; height:45px;" title="Reset">
                                <i class="ti ti-refresh fs-3"></i>
                            </a>

                            {{-- PDF --}}
                            <a href="{{ route('attendances.printPdf', request()->query()) }}"
                                class="btn btn-success d-flex align-items-center justify-content-center"
                                style="width:45px; height:45px;" target="_blank" title="Cetak PDF">
                                <i class="ti ti-file-text fs-3"></i>
                            </a>

                            {{-- PDF KALENDER --}}
                            <a href="{{ request('month') ? route('attendances.cetak_kalendar', request()->query()) : '#' }}"
                                class="btn btn-warning d-flex align-items-center justify-content-center {{ !request('month') ? 'disabled' : '' }}"
                                style="width:45px; height:45px;" target="_blank" title="Cetak PDF Kalender">
                                <i class="ti ti-calendar fs-3"></i>
                            </a>

                            <a href="{{ route('attendances.export.csv') }}"
                                class="btn btn-success d-flex align-items-center justify-content-center"
                                style="width:45px; height:45px;" target="_blank" title="Cetak CSV">
                                <i class="ti ti-table fs-3"></i>
                            </a>

                            {{-- <a href="{{ route('attendances.export.csv') }}" class="btn btn-success">
                                Export CSV
                            </a> --}}

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

                {{-- input kehadiran manual --}}
                <button type="button" class="btn btn-primary mb-2" id="btn-manual">
                    + Input Kehadiran Manual
                </button>

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
                                        @php
                                            $status = $attendance->check_in_status;

                                            $badgeClass = match ($status) {
                                                'ON_TIME' => 'bg-success',
                                                'TERLAMBAT' => 'bg-danger',
                                                'LEBIH AWAL' => 'bg-warning text-dark',
                                                'LEMBUR' => 'bg-primary',
                                                default => 'bg-secondary',
                                            };
                                        @endphp

                                        @if ($status)
                                            <span class="badge {{ $badgeClass }}">
                                                {{ $status }}
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
                                        @php
                                            $status = $attendance->check_out_status;

                                            $badgeClass = match ($status) {
                                                'ON_TIME' => 'bg-success',
                                                'TERLAMBAT' => 'bg-danger',
                                                'LEBIH AWAL' => 'bg-warning text-dark',
                                                'LEMBUR' => 'bg-primary',
                                                default => 'bg-secondary',
                                            };
                                        @endphp

                                        @if ($status)
                                            <span class="badge {{ $badgeClass }}">
                                                {{ $status }}
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
                                                <a href="{{ asset('storage/' . $attendance->check_in_photo_path) }}?t={{ time() }}"
                                                    target="_blank">
                                                    <img src="{{ asset('storage/' . $attendance->check_in_photo_path) }}?t={{ time() }}"
                                                        class="img-thumbnail"
                                                        style="width:60px; height:60px; object-fit:cover;"
                                                        title="Foto Datang">
                                                </a>
                                            @endif

                                            {{-- FOTO PULANG --}}
                                            @if ($attendance->check_out_photo_path)
                                                <a href="{{ asset('storage/' . $attendance->check_out_photo_path) }}?t={{ time() }}"
                                                    target="_blank">
                                                    <img src="{{ asset('storage/' . $attendance->check_out_photo_path) }}?t={{ time() }}"
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
                                        {{-- EDIT --}}
                                        <button type="button" class="btn btn-warning btn-edit"
                                            data-id="{{ $attendance->id }}"
                                            data-checkin="{{ $attendance->check_in_time }}"
                                            data-checkout="{{ $attendance->check_out_time }}"
                                            data-statusin="{{ $attendance->check_in_status }}"
                                            data-statusout="{{ $attendance->check_out_status }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

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
    <script>
        document.getElementById('btn-manual').addEventListener('click', function() {

            Swal.fire({
                title: 'Input Kehadiran Manual',
                width: '600px', // ✅ diperbesar biar lega
                html: `
                <div style="display:flex; flex-direction:column; gap:10px;">

                    <select id="employee_id" class="swal2-input">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->employee_name }}</option>
                        @endforeach
                    </select>

                    <input type="date" id="attendance_date" class="swal2-input">

                    <select id="work_shift_id" class="swal2-input">
                        <option value="">-- Pilih Shift --</option>
                        @foreach ($workShifts as $shift)
                            <option value="{{ $shift->id }}">
                                {{ $shift->shift_name }} 
                                ({{ $shift->start_time }} - {{ $shift->end_time }})
                            </option>
                        @endforeach
                    </select>

                    <select id="status" class="swal2-input">
                        <option value="">-- Pilih Status --</option>
                        <option value="CUTI TAHUNAN">CT (Cuti Tahunan)</option>
                        <option value="CUTI SETENGAH HARI">CSH (Cuti Setengah Hari)</option>
                        <option value="DINAS LUAR">DL (Dinas Luar)</option>
                        <option value="CUTI SAKIT">CS (Cuti Sakit)</option>
                    </select>

                </div>
            `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Simpan',

                preConfirm: () => {
                    const employee_id = document.getElementById('employee_id').value;
                    const attendance_date = document.getElementById('attendance_date').value;
                    const work_shift_id = document.getElementById('work_shift_id').value;
                    const status = document.getElementById('status').value;

                    if (!employee_id || !attendance_date || !work_shift_id || !status) {
                        Swal.showValidationMessage('Semua field wajib diisi!');
                        return false;
                    }

                    return {
                        employee_id,
                        attendance_date,
                        work_shift_id,
                        status,
                    }
                }
            }).then((result) => {

                if (result.isConfirmed) {

                    fetch("{{ route('attendances.manual.store') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify(result.value)
                        })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire('Berhasil', data.message, 'success')
                                .then(() => location.reload());
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Gagal menyimpan data', 'error');
                        });
                }
            });
        });
    </script>

    <script>
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {

                const id = this.dataset.id;
                const checkin = this.dataset.checkin ?? '';
                const checkout = this.dataset.checkout ?? '';
                const statusin = this.dataset.statusin ?? '';
                const statusout = this.dataset.statusout ?? '';

                Swal.fire({
                    title: 'Edit Absensi',
                    width: '420px', // 🔽 diperkecil
                    html: `
                <div style="display:flex; flex-direction:column; gap:10px; text-align:left; font-size:13px;">

                    <div>
                        <div style="margin-bottom:3px; font-weight:600;">Jam Datang</div>
                        <input type="time" id="check_in_time"
                            style="width:100%; padding:6px 8px; font-size:13px; border:1px solid #ccc; border-radius:6px;"
                            value="${checkin ? checkin.substring(11,19) : ''}">
                    </div>

                    <div>
                        <div style="margin-bottom:3px; font-weight:600;">Status Datang</div>
                        <select id="check_in_status"
                            style="width:100%; padding:6px 8px; font-size:13px; border:1px solid #ccc; border-radius:6px;">
                            <option value="">-- Pilih Status --</option>
                            <option value="ON_TIME" ${statusin === 'ON_TIME' ? 'selected' : ''}>ON TIME</option>
                            <option value="TERLAMBAT" ${statusin === 'TERLAMBAT' ? 'selected' : ''}>TERLAMBAT</option>
                            <option value="CUTI SETENGAH HARI" ${statusin === 'CUTI SETENGAH HARI' ? 'selected' : ''}>
                                CUTI SETENGAH HARI (CSH)
                            </option>
                        </select>
                    </div>

                    <div>
                        <div style="margin-bottom:3px; font-weight:600;">Jam Pulang</div>
                        <input type="time" id="check_out_time"
                            style="width:100%; padding:6px 8px; font-size:13px; border:1px solid #ccc; border-radius:6px;"
                            value="${checkout ? checkout.substring(11,19) : ''}">
                    </div>

                    <div>
                        <div style="margin-bottom:3px; font-weight:600;">Status Pulang</div>
                        <select id="check_out_status"
                            style="width:100%; padding:6px 8px; font-size:13px; border:1px solid #ccc; border-radius:6px;">
                            <option value="">-- Pilih Status --</option>
                            <option value="ON_TIME" ${statusout === 'ON_TIME' ? 'selected' : ''}>ON TIME</option>
                            <option value="LEBIH AWAL" ${statusout === 'LEBIH AWAL' ? 'selected' : ''}>LEBIH AWAL</option>
                        </select>
                    </div>

                </div>
            `,
                    showCancelButton: true,
                    confirmButtonText: 'Update',
                    confirmButtonColor: '#3085d6',
                    cancelButtonText: 'Batal',

                    preConfirm: () => {
                        return {
                            check_in_time: document.getElementById('check_in_time').value,
                            check_in_status: document.getElementById('check_in_status').value,
                            check_out_time: document.getElementById('check_out_time').value,
                            check_out_status: document.getElementById('check_out_status').value
                        }
                    }

                }).then((result) => {

                    if (result.isConfirmed) {

                        fetch(`/attendances/${id}/update-inline`, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify(result.value)
                            })
                            .then(res => res.json())
                            .then(data => {
                                Swal.fire('Berhasil', data.message, 'success')
                                    .then(() => location.reload());
                            })
                            .catch(err => {
                                Swal.fire('Error', 'Gagal update data', 'error');
                            });
                    }
                });
            });
        });
    </script>
@endsection
