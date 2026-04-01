@php
    $layout = in_array(auth()->user()->role, ['admin', 'superadmin']) ? 'master' : 'master-no-sidebar';
@endphp
@extends($layout)
@section('content')
    <div class="container">
        <h3>Data Lembur V2</h3>
        <div class="card card-body">
            <div class="d-flex">
                <a href="{{ route('overtime_v2.create') }}" class="btn btn-primary mb-3">
                    + Tambah
                </a>
            </div>
            <form method="GET" action="{{ route('overtime_v2.index') }}" class="row mb-3">
                <div class="col-md-3">
                    <select name="nama" class="form-control">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('nama') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->employee_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Diajukan</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <!-- FILTER -->
                    <button class="btn btn-primary d-flex align-items-center justify-content-center flex-fill">
                        <i class="ti ti-filter fs-3"></i>
                    </button>

                    <!-- RESET -->
                    <a href="{{ route('overtime_v2.index') }}"
                        class="btn btn-secondary d-flex align-items-center justify-content-center flex-fill" title="Reset">
                        <i class="ti ti-refresh fs-3"></i>
                    </a>

                    <!-- PDF -->
                    <a href="{{ route('overtime_v2.export.pdf', request()->all()) }}"
                        class="btn btn-danger d-flex align-items-center justify-content-center flex-fill"
                        title="Export PDF">
                        <i class="ti ti-file-text fs-3"></i>
                    </a>

                    <!-- EXCEL -->
                    <a href="{{ route('overtime_v2.export.csv', request()->all()) }}"
                        class="btn btn-success d-flex align-items-center justify-content-center flex-fill"
                        title="Export Excel">
                        <i class="ti ti-table fs-3"></i>
                    </a>
                </div>
            </form>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Durasi</th>
                        <th class="text-center">Foto</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($overtimes as $key => $item)
                        <tr>
                            <td class="text-center">{{ $overtimes->firstItem() + $key }}</td>
                            <td class="text-center">{{ $item->employee->employee_name ?? '-' }}</td>
                            <td class="text-center">{{ $item->overtime_date }}</td>
                            <td class="text-center">{{ $item->status }}</td>
                            <td class="text-center">{{ $item->duration }} menit</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- CHECK IN --}}
                                    @if ($item->check_in_photo)
                                        <a href="{{ asset('storage/' . $item->check_in_photo) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $item->check_in_photo) }}"
                                                class="img-thumbnail" style="width:60px; height:60px; object-fit:cover;"
                                                title="Foto Check In">
                                        </a>
                                    @endif

                                    {{-- CHECK OUT --}}
                                    @if ($item->check_out_photo)
                                        <a href="{{ asset('storage/' . $item->check_out_photo) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $item->check_out_photo) }}"
                                                class="img-thumbnail" style="width:60px; height:60px; object-fit:cover;"
                                                title="Foto Check Out">
                                        </a>
                                    @endif

                                    {{-- JIKA TIDAK ADA --}}
                                    @if (!$item->check_in_photo && !$item->check_out_photo)
                                        -
                                    @endif

                                </div>
                            </td>
                            <td class="text-center">
                                @if ($item->status == 'pending')
                                    <a href="{{ route('overtime_v2.edit', $item->id) }}" class="btn btn-sm btn-warning"><i
                                            class="fas fa-edit"></i></a>

                                    <form action="{{ route('overtime_v2.destroy', $item->id) }}" method="POST"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    @if ($isApprover)
                                        <form action="{{ route('overtime.approve', $item->id) }}" method="POST"
                                            style="display:inline">
                                            @csrf
                                            {{-- <button class="btn btn-sm btn-success">Setujui</button> --}}
                                            <button type="button"
                                                class="btn btn-sm btn-success btn-approve">Setujui</button>
                                        </form>

                                        <form action="{{ route('overtime.reject', $item->id) }}" method="POST"
                                            style="display:inline">
                                            @csrf
                                            {{-- <button class="btn btn-sm btn-danger">Tolak</button> --}}
                                            <button type="button" class="btn btn-sm btn-danger btn-reject">Tolak</button>
                                        </form>
                                    @endif
                                @else
                                    <form action="{{ route('overtime_v2.destroy', $item->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- PAGINATION --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    @if ($overtimes->count())
                        Menampilkan {{ $overtimes->firstItem() }} – {{ $overtimes->lastItem() }}
                        dari {{ $overtimes->total() }} data
                    @else
                        Tidak ada data
                    @endif
                </div>

                <div>
                    {{ $overtimes->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}'
            });
        </script>
    @endif

    @if (session('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: '{{ session('warning') }}'
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: '{{ session('info') }}'
            });
        </script>
    @endif

    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                let form = this.closest('form');

                Swal.fire({
                    title: 'Yakin?',
                    text: "Data akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
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
    <script>
        document.querySelectorAll('.btn-approve').forEach(button => {
            button.addEventListener('click', function() {
                let form = this.closest('form');

                Swal.fire({
                    title: 'Setujui lembur?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, setujui'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        document.querySelectorAll('.btn-reject').forEach(button => {
            button.addEventListener('click', function() {
                let form = this.closest('form');

                Swal.fire({
                    title: 'Tolak lembur?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, tolak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
