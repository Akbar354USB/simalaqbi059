@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-check"></i>
                    Approval Pengajuan Cuti (Kepala Kantor)
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Nama Pegawai</th>
                                <th>NIP</th>
                                <th>Unit Kerja</th>
                                <th>Periode Cuti</th>
                                <th>Total Hari</th>
                                <th>Status</th>
                                <th width="18%">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($requests as $item)
                                <tr class="text-center">
                                    <td>{{ $item->employee->employee_name }}</td>
                                    <td>{{ $item->employee->nip ?? '-' }}</td>
                                    <td>{{ $item->workUnit->work_unit }}</td>

                                    <td class="text-left">
                                        @foreach ($item->periods as $period)
                                            <span class="badge bg-info d-block mb-1">
                                                {{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }}
                                                s/d
                                                {{ \Carbon\Carbon::parse($period->end_date)->format('d M Y') }}
                                            </span>
                                        @endforeach
                                    </td>

                                    <td>
                                        <strong>{{ $item->periods->sum('total_days') }}</strong> hari
                                    </td>

                                    <td>
                                        @if ($item->status === 'approved_unit_head')
                                            <span class="badge bg-warning">
                                                Menunggu Kepala Kantor
                                            </span>
                                        @elseif ($item->status === 'pending_head_office')
                                            <span class="badge bg-warning">
                                                Menunggu Kepala Kantor
                                            </span>
                                        @elseif ($item->status === 'approved')
                                            <span class="badge bg-success">
                                                Disetujui
                                            </span>
                                        @elseif ($item->status === 'rejected')
                                            <span class="badge bg-danger">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if (in_array($item->status, ['approved_unit_head', 'pending_head_office']))
                                            <form action="{{ route('head-office.approvals.approve', $item->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm"
                                                    onclick="return confirm('Setujui pengajuan cuti ini?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('head-office.approvals.reject', $item->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Tolak pengajuan cuti ini?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('additional-leave-requests.show', $item->id) }}"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Tidak ada pengajuan cuti untuk disetujui
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
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33'
            });
        </script>
    @endif
@endsection
