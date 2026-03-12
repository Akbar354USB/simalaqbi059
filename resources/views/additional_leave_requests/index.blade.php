@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    Data Pengajuan Cuti Tambahan
                </h5>
            </div>

            <div class="card-body">
                <a href="{{ route('additional-leave-requests.create') }}" class="btn btn-primary mb-3">
                    + Pengajuan Cuti Tambahan
                </a>

                {{-- FILTER --}}
                <div class="card border mb-4 shadow-sm">
                    <div class="card-body py-3">

                        <form method="GET" action="{{ route('additional-leave-requests.index') }}">

                            <div class="row g-2 align-items-end">

                                {{-- SEARCH --}}
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">
                                        <i class="fas fa-search me-1"></i> Pencarian
                                    </label>

                                    <input type="text" name="keyword" class="form-control"
                                        value="{{ request('keyword') }}" placeholder="Nama / NIP / No Surat">
                                </div>

                                {{-- START DATE --}}
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">
                                        <i class="fas fa-calendar me-1"></i> Tanggal Mulai
                                    </label>

                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date') }}">
                                </div>

                                {{-- END DATE --}}
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">
                                        <i class="fas fa-calendar-check me-1"></i> Tanggal Selesai
                                    </label>

                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ request('end_date') }}">
                                </div>

                                {{-- BUTTON --}}
                                <div class="col-md-2">

                                    <div class="d-flex gap-2">

                                        <button class="btn btn-primary w-100">
                                            <i class="fas fa-search me-1"></i>
                                            Cari
                                        </button>

                                        <a href="{{ route('additional-leave-requests.index') }}"
                                            class="btn btn-light border w-100">
                                            <i class="ti ti-refresh me-1"></i>
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>
                </div>

                {{-- TABLE --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Waktu Cuti</th>
                                <th>No Surat</th>
                                <th>Status</th>
                                <th width="180">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $item)
                                <tr class="text-center">
                                    <td>{{ $item->employee->employee_name }}</td>
                                    <td>{{ $item->employee->nip ?? '-' }}</td>

                                    <td>
                                        @foreach ($item->periods as $period)
                                            <span class="badge bg-success d-block mb-1">
                                                {{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }}
                                                –
                                                {{ \Carbon\Carbon::parse($period->end_date)->format('d M Y') }}
                                                <small>({{ $period->total_days }} hari)</small>
                                            </span>
                                        @endforeach
                                    </td>

                                    <td>{{ $item->letter_number }}</td>

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
                                                    Ditetapkan Kasubag Umum
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
                                        {{-- Tombol cetak hanya jika sudah disetujui Subbag Umum --}}
                                        @if ($item->status === 'approved_general_affairs')
                                            <a href="{{ route('additional-leave-requests.print', $item->id) }}"
                                                target="_blank" class="btn btn-danger btn-sm">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif

                                        <a href="{{ route('additional-leave-requests.show', $item->id) }}"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- 🔥 TOMBOL HAPUS SELALU TAMPIL --}}
                                        <form action="{{ route('additional-leave-requests.destroy', $item->id) }}"
                                            method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            Belum ada pengajuan cuti
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
        {{-- ALERT SUKSES --}}
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

        {{-- SWEETALERT DELETE --}}
        <script>
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: 'Data yang dihapus tidak dapat dikembalikan',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e74a3b',
                        cancelButtonColor: '#858796',
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endsection
