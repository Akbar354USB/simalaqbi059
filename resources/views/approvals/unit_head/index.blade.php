@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle"></i>
                    Approval Pengajuan Cuti (Pimpinan Unit)
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="text-center table-light">
                            <tr>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Waktu Cuti</th>
                                <th>Total Hari</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($unitHeadRequests as $item)
                                <tr class="text-center">
                                    <td>{{ $item->employee->employee_name }}</td>
                                    <td>{{ $item->employee->nip ?? '-' }}</td>

                                    <td class="text-left">
                                        @foreach ($item->periods as $period)
                                            <span class="badge bg-success d-block mb-1">
                                                {{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }}
                                                –
                                                {{ \Carbon\Carbon::parse($period->end_date)->format('d M Y') }}
                                            </span>
                                        @endforeach
                                    </td>

                                    <td>
                                        {{ $item->periods->sum('total_days') }} hari
                                    </td>

                                    <td>
                                        {{-- BUTTON APPROVE --}}
                                        <button type="button" class="btn btn-success btn-sm btn-approve"
                                            data-id="{{ $item->id }}">
                                            <i class="fas fa-check"></i>
                                        </button>

                                        {{-- BUTTON REJECT --}}
                                        <button type="button" class="btn btn-danger btn-sm btn-reject"
                                            data-id="{{ $item->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>

                                        {{-- DETAIL --}}
                                        <a href="{{ route('additional-leave-requests.show', $item->id) }}"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- FORM APPROVE --}}
                                        <form id="approve-form-{{ $item->id }}"
                                            action="{{ route('unit-head.approvals.approve', $item->id) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>

                                        {{-- FORM REJECT --}}
                                        <form id="reject-form-{{ $item->id }}"
                                            action="{{ route('unit-head.approvals.reject', $item->id) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Tidak ada pengajuan menunggu approval
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($isGeneralAffairs)
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle"></i>
                        Penetapan Pengajuan Cuti (Kasubag Umum)
                    </h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="text-center table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Waktu Cuti</th>
                                    <th>Total Hari</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($generalAffairsRequests as $item)
                                    <tr class="text-center">
                                        <td>{{ $item->employee->employee_name }}</td>
                                        <td>{{ $item->employee->nip ?? '-' }}</td>

                                        <td class="text-left">
                                            @foreach ($item->periods as $period)
                                                <span class="badge bg-success d-block mb-1">
                                                    {{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }}
                                                    –
                                                    {{ \Carbon\Carbon::parse($period->end_date)->format('d M Y') }}
                                                </span>
                                            @endforeach
                                        </td>

                                        <td>
                                            {{ $item->periods->sum('total_days') }} hari
                                        </td>

                                        <td>

                                            <button type="button" class="btn btn-success btn-sm btn-approve"
                                                data-id="{{ $item->id }}">
                                                <i class="fas fa-check"></i>
                                            </button>

                                            <button type="button" class="btn btn-danger btn-sm btn-reject"
                                                data-id="{{ $item->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>

                                            <a href="{{ route('additional-leave-requests.show', $item->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- APPROVE --}}
                                            <form id="approve-form-{{ $item->id }}"
                                                action="{{ route('leave.general.approve', $item->id) }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>

                                            {{-- REJECT --}}
                                            <form id="reject-form-{{ $item->id }}"
                                                action="{{ route('leave.general.reject', $item->id) }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>

                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            Tidak ada pengajuan menunggu penetapan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('js')
    {{-- ALERT SUCCESS --}}
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

    {{-- ALERT ERROR --}}
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}",
                confirmButtonColor: '#e74a3b'
            });
        </script>
    @endif

    <script>
        // APPROVE
        document.querySelectorAll('.btn-approve').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;

                Swal.fire({
                    title: 'Setujui Pengajuan?',
                    text: 'Pengajuan cuti akan disetujui.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Setujui',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#1cc88a',
                    cancelButtonColor: '#858796'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('approve-form-' + id).submit();
                    }
                });
            });
        });

        // REJECT
        document.querySelectorAll('.btn-reject').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;

                Swal.fire({
                    title: 'Tolak Pengajuan?',
                    text: 'Pengajuan cuti akan ditolak.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#e74a3b',
                    cancelButtonColor: '#858796'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('reject-form-' + id).submit();
                    }
                });
            });
        });
    </script>
@endsection
