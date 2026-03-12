@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="alert alert-primary text-left mb-2" role="alert">
            <i class="fas fa-info-circle"></i>
            Silahkan Menghubungi Admin jika mendapat masalah dalam proses pengajuan cuti dan jika belum Mempunyai kuota
            Cuti.
        </div>
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history"></i> Riwayat Pengajuan Cuti
                </h5>
            </div>
            <div class="card-body">
                <a href="{{ route('additional-leave-requests.create') }}" class="btn btn-primary mb-3">
                    + Pengajuan Cuti Tambahan
                </a>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Waktu Cuti</th>
                                <th>Total Hari</th>
                                <th>No Surat</th>
                                <th>Status</th>
                                <th>Diajukan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($requests as $index => $item)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>

                                    {{-- WAKTU CUTI --}}
                                    <td class="text-left">
                                        @foreach ($item->periods as $period)
                                            <span class="badge bg-success d-block mb-1">
                                                {{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }}
                                                –
                                                {{ \Carbon\Carbon::parse($period->end_date)->format('d M Y') }}
                                                <small>({{ $period->total_days }} hari)</small>
                                            </span>
                                        @endforeach
                                    </td>

                                    <td>
                                        <strong>{{ $item->periods->sum('total_days') }}</strong> hari
                                    </td>

                                    <td>{{ $item->letter_number }}</td>

                                    {{-- STATUS --}}
                                    <td>
                                        @switch($item->status)
                                            @case('pending_unit_head')
                                                <span class="badge bg-warning text-dark">
                                                    Menunggu Atasan Langsung
                                                </span>
                                            @break

                                            @case('approved_unit_head')
                                                <span class="badge bg-info">
                                                    Disetujui Pimpinan Unit
                                                </span>
                                            @break

                                            @case('pending_head_office')
                                                <span class="badge bg-primary">
                                                    Menunggu Kepala Kantor
                                                </span>
                                            @break

                                            @case('approved_head_office')
                                                <span class="badge bg-secondary">
                                                    Disetujui Kepala Kantor
                                                </span>
                                            @break

                                            @case('pending_general_affairs')
                                                <span class="badge bg-dark">
                                                    Menunggu Penetapan Sub Bagian Umum
                                                </span>
                                            @break

                                            @case('approved_general_affairs')
                                                <span class="badge bg-success">
                                                    Disetujui Sub Bagian Umum
                                                </span>
                                            @break

                                            @case('approved')
                                                <span class="badge bg-success">
                                                    Disetujui
                                                </span>
                                            @break

                                            @case('rejected')
                                            @case('rejected_unit_head')

                                            @case('rejected_head_office')
                                            @case('rejected_general_affairs')
                                                <span class="badge bg-danger">
                                                    Ditolak
                                                </span>
                                            @break

                                            @default
                                                <span class="badge bg-light text-dark">
                                                    Status Tidak Diketahui
                                                </span>
                                        @endswitch
                                    </td>

                                    <td>
                                        {{ $item->created_at->format('d M Y') }}
                                    </td>

                                    {{-- AKSI --}}
                                    <td>
                                        <a href="{{ route('additional-leave-requests.show', $item->id) }}"
                                            class="btn btn-info btn-sm" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if (
                                            $item->status === 'approved_general_affairs' &&
                                                (auth()->user()->role === 'superadmin' || $item->employee_id === auth()->user()->employee->id))
                                            <a href="{{ route('additional-leave-requests.print', $item->id) }}"
                                                target="_blank" class="btn btn-danger btn-sm" title="Cetak">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            Belum ada riwayat pengajuan cuti
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('js')
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#1cc88a'
                });
            </script>
        @endif
    @endsection
