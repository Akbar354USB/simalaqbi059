@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Data Pembagian Shift Kerja</h5>
            </div>

            <div class="card-body">
                <a href="{{ route('work-shifts.create') }}" class="btn btn-primary mb-3">
                    + Tambah Waktu Shift
                </a>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>Nama Shift</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Durasi</th>
                                <th>Jenis Shift</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($workShifts as $shift)
                                <tr class="text-center">
                                    <td>{{ $shift->shift_name }}</td>

                                    <td>{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</td>

                                    <td>
                                        @if ($shift->day_span == 0)
                                            1 Hari
                                        @else
                                            {{ $shift->day_span + 1 }} Hari
                                        @endif
                                    </td>

                                    <td>
                                        @if ($shift->is_cross_day)
                                            <span class="badge bg-warning text-dark">
                                                Lintas Hari
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                Normal
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('work-shifts.edit', $shift->id) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('work-shifts.destroy', $shift->id) }}" method="POST"
                                            class="d-inline">
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
                                        Data shift kerja belum tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $workShifts->firstItem() }} –
                        {{ $workShifts->lastItem() }} dari
                        {{ $workShifts->total() }} data
                    </div>

                    <div>
                        {{ $workShifts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    {{-- SweetAlert Success --}}
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

    {{-- SweetAlert Delete Confirmation --}}
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Yakin?',
                    text: 'Data Shift Kerja akan dihapus permanen!',
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
@endsection
