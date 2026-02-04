@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fas fa-eye mr-2"></i>
                <h5 class="mb-0">Detail Pengajuan Cuti Tambahan</h5>
            </div>

            <div class="card-body">

                {{-- NAMA --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-muted font-weight-bold">Nama Pegawai</div>
                    <div class="col-md-8">
                        {{ $additionalLeaveRequest->employee->employee_name }}
                    </div>
                </div>

                {{-- NIP --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-muted font-weight-bold">NIP</div>
                    <div class="col-md-8">
                        {{ $additionalLeaveRequest->employee->nip ?? '-' }}
                    </div>
                </div>

                {{-- JABATAN --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-muted font-weight-bold">Jabatan</div>
                    <div class="col-md-8">
                        {{ $additionalLeaveRequest->position }}
                    </div>
                </div>

                {{-- UNIT KERJA --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-muted font-weight-bold">Unit Kerja</div>
                    <div class="col-md-8">
                        {{ $additionalLeaveRequest->workUnit->work_unit }}
                    </div>
                </div>

                {{-- ALASAN --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-muted font-weight-bold">Alasan Cuti</div>
                    <div class="col-md-8">
                        {{ $additionalLeaveRequest->leave_reason }}
                    </div>
                </div>

                {{-- WAKTU CUTI (MULTI PERIODE) --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-muted font-weight-bold">Waktu Cuti</div>
                    <div class="col-md-8">
                        @foreach ($additionalLeaveRequest->periods as $index => $period)
                            <div class="mb-2">
                                <span class="badge badge-info">
                                    Periode {{ $index + 1 }}
                                </span>
                                {{ \Carbon\Carbon::parse($period->start_date)->translatedFormat('d F Y') }}
                                s/d
                                {{ \Carbon\Carbon::parse($period->end_date)->translatedFormat('d F Y') }}
                                <small class="text-muted">
                                    ({{ $period->total_days }} hari)
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- TOTAL HARI --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-muted font-weight-bold">Total Hari Cuti</div>
                    <div class="col-md-8">
                        {{ $additionalLeaveRequest->periods->sum('total_days') }} hari
                    </div>
                </div>

                {{-- NOMOR SURAT --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-muted font-weight-bold">Nomor Surat</div>
                    <div class="col-md-8">
                        {{ $additionalLeaveRequest->letter_number }}
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="row mb-4">
                    <div class="col-md-4 text-muted font-weight-bold">Status Pengajuan</div>
                    <div class="col-md-8">
                        @switch($additionalLeaveRequest->status)
                            @case('pending_unit_head')
                                <span class="badge badge-warning">Menunggu Pimpinan Unit</span>
                            @break

                            @case('approved_unit_head')
                                <span class="badge badge-info">Disetujui Pimpinan Unit</span>
                            @break

                            @case('approved')
                                <span class="badge badge-success">Disetujui Kepala Kantor</span>
                            @break

                            @case('rejected')
                            @case('rejected_unit_head')
                                <span class="badge badge-danger">Ditolak</span>
                            @break
                        @endswitch
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{ redirect()->back()->getTargetUrl() }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>

                    @if ($additionalLeaveRequest->status === 'pending_unit_head')
                        <a href="{{ route('additional-leave-requests.edit', $additionalLeaveRequest->id) }}"
                            class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
