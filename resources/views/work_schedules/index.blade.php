@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Data Waktu Reminder Pegawai</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info text-left" role="alert">
                    <i class="fas fa-info-circle"></i>
                    Tambahkan waktu kapan pegawai ingin di pasangkan reminder ke <strong>Goole Calendar</strong>. Reminder
                    akan Mengirimkan Notifikasi 10 menit sebelum Waktu yang di tentukan.
                </div>
                <a href="{{ route('work-schedules.create') }}" class="btn btn-primary mb-3">
                    Tambah Jadwal
                </a>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Pegawai</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Zona Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $schedule->employee->employee_name }}</td>
                                    <td>{{ $schedule->time_in }}</td>
                                    <td>{{ $schedule->time_out }}</td>
                                    <td>{{ $schedule->timezone }}</td>
                                    <td>
                                        <a href="{{ route('work-schedules.edit', $schedule->id) }}"
                                            class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>

                                        <form action="{{ route('work-schedules.destroy', $schedule->id) }}" method="POST"
                                            class="d-inline">
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
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $schedules->firstItem() }} –
                        {{ $schedules->lastItem() }} dari
                        {{ $schedules->total() }} data
                    </div>

                    <div>
                        {{ $schedules->links() }}
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
                    text: 'Data Waktu Reminder akan dihapus permanen!',
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
