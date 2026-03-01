@extends('master-no-sidebar')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    {{-- Optional Hover Effect --}}
    <style>
        .hover-card {
            transition: 0.3s;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
        }

        .table-sm th,
        .table-sm td {
            padding: 0.4rem;
            font-size: 0.75rem;
        }

        .badge-sm {
            font-size: 0.65rem;
            padding: 0.35em 0.5em;
        }

        @media (min-width: 768px) {

            .table-sm th,
            .table-sm td {
                font-size: 0.85rem;
            }
        }
    </style>
@endsection
@section('content')
    <div
        class="alert alert-success bg-success bg-opacity-10 border-0 shadow-sm py-2 px-3 mb-3 small d-flex align-items-start">

        <i class="fas fa-info-circle text-success me-2 mt-1"></i>

        <div>
            <span class="text-muted">
                Selamat Datang
            </span>
            <strong class="text-dark">
                {{ Auth::user()->name }}
            </strong>
            <span class="text-muted">
                di sistem absensi SIMALAQBI059. Silahkan Klik absensi untuk masuk ke fitur absensi. <strong
                    class="text-dark">FITUR LEMBUR
                    BELUM TERSEDIA.</strong>
            </span>
        </div>

    </div>
    <div class="container-fluid py-4">

        {{-- =======================
        BUTTON CARD SECTION
    ======================== --}}
        <div class="row mb-4 g-3">

            <!-- CARD ABSENSI -->
            <div class="col-6">
                <a href="{{ route('attendance.index') }}" class="text-decoration-none">
                    <div class="card shadow border-0 h-100 text-center hover-card bg-success bg-opacity-25">
                        <div class="card-body d-flex flex-column justify-content-center p-3">
                            <div class="mb-2">
                                <i class="ti ti-calendar-check fs-2 text-success"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Absensi</h6>
                            <small class="text-muted">
                                Presensi datang & pulang
                            </small>
                        </div>
                    </div>
                </a>
            </div>
            <!-- CARD LEMBUR -->
            <div class="col-6">
                <a href="{{ route('overtime.create') }}" class="text-decoration-none">
                    <div class="card shadow border-0 h-100 text-center hover-card bg-primary bg-opacity-25">
                        <div class="card-body d-flex flex-column justify-content-center p-3">
                            <div class="mb-2">
                                <i class="ti ti-calendar-time fs-2 text-primary"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Pengajuan Lembur</h6>
                            <small class="text-muted">
                                Klik untuk ajukan lembur
                            </small>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        {{-- =======================
    DASHBOARD KEHADIRAN
======================= --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-bold">Data Kehadiran</h6>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered align-middle text-center small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Masuk</th>
                                <th>Status</th>
                                <th>Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $data)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($data->attendance_date)->translatedFormat('d M Y') }}</td>
                                    <td>{{ $data->check_in_time ? \Carbon\Carbon::parse($data->check_in_time)->format('H:i:s') : '-' }}
                                    </td>
                                    <td>
                                        @php
                                            $status = $data->check_in_status;

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
                                    <td>{{ $data->check_out_time ? \Carbon\Carbon::parse($data->check_out_time)->format('H:i:s') : '-' }}
                                    </td>
                                    <td>
                                        @php
                                            $status = $data->check_out_status;

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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>


        {{-- =======================
    DASHBOARD LEMBUR
======================= --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-bold">Data Lembur</h6>
            </div>
            <div class="card-body p-2">

                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered align-middle text-center small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($overtimes as $overtime)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($overtime->overtime_date)->format('d M Y') }}</td>
                                    <td>{{ $overtime->start_time }}</td>
                                    <td>{{ $overtime->end_time }}</td>
                                    <td>
                                        @if ($overtime->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif ($overtime->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">Data Lembur belum tersedia</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection
