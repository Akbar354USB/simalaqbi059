@php
    $layout = in_array(auth()->user()->role, ['admin', 'superadmin']) ? 'master' : 'master-no-sidebar';
@endphp

@extends($layout)



@section('content')
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Daftar Buku Tamu</h5>
            </div>

            <div class="card-body">

                <a href="{{ route('guest_book_create') }}" class="btn btn-primary mb-3">
                    + Tambah Tamu
                </a>

                <a href="{{ route('guest_book_print_pdf', request()->query()) }}" class="btn btn-success mb-3" target="_blank">
                    <i class="fas fa-file-pdf"></i> Print PDF
                </a>

                {{-- FILTER --}}
                <form action="{{ route('guest_book_index') }}" method="GET" class="mb-3">
                    <div class="form-row">
                        <div class="col-md-3">
                            <label>Filter Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Filter Bulan</label>
                            <input type="month" name="month" class="form-control" value="{{ request('month') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                    </div>

                    <div class="form-row mt-3">
                        <div class="col-md-12">
                            <button class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('guest_book_index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync-alt"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                {{-- TABEL --}}
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Tamu</th>
                            <th>Nomor HP</th>
                            <th>Satker</th>
                            <th>Keperluan</th>
                            <th>Waktu Datang</th>
                            <th>Waktu Pulang</th>
                            <th>Pegawai Yang Ditemui</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($guestBooks as $g)
                            <tr class="text-center">
                                <td>{{ $guestBooks->firstItem() + $loop->index }}</td>
                                <td>{{ \Carbon\Carbon::parse($g->created_at)->format('d/m/Y') }}</td>
                                <td>{{ $g->guest_name }}</td>
                                <td>{{ $g->number_phone }}</td>
                                <td>{{ $g->agency->agency_name }}</td>
                                <td>{{ $g->objective }}</td>
                                <td><span class="badge badge-success">
                                        {{ $g->arrival_time }}
                                    </span>
                                </td>

                                {{-- JAM PULANG --}}
                                <td>
                                    @if ($g->departure_time)
                                        <span class="badge badge-success">
                                            {{ $g->departure_time }}
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            On Progress
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @foreach ($g->employees as $emp)
                                        <span class="badge badge-info">
                                            {{ $emp->employee_name }}
                                        </span>
                                    @endforeach
                                </td>

                                {{-- AKSI --}}
                                <td>

                                    {{-- Tambah Jam Pulang --}}
                                    @if (!$g->departure_time)
                                        <button type="button" class="btn btn-success btn-sm btn-departure"
                                            data-id="{{ $g->id }}">
                                            <i class="fas fa-clock"></i>
                                        </button>
                                    @endif

                                    <a href="{{ route('guest_book_edit', $g->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('guest_book_destroy', $g->id) }}" method="POST"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $guestBooks->firstItem() }} –
                        {{ $guestBooks->lastItem() }} dari
                        {{ $guestBooks->total() }} data
                    </div>
                    <div>
                        {{ $guestBooks->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- SUCCESS --}}
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

    {{-- DELETE --}}
    <script>
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Yakin?',
                    text: 'Data tamu akan dihapus permanen!',
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
    </script>

    {{-- TAMBAH JAM PULANG --}}
    <script>
        document.querySelectorAll('.btn-departure').forEach(btn => {
            btn.addEventListener('click', function() {

                let guestId = this.dataset.id;

                Swal.fire({
                    title: 'Tambah Jam Pulang',
                    input: 'time',
                    inputLabel: 'Jam Pulang',
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Jam pulang wajib diisi!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {

                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/guest-book/${guestId}/departure`;

                        form.innerHTML = `
                    @csrf
                    <input type="hidden" name="departure_time" value="${result.value}">
                `;

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
