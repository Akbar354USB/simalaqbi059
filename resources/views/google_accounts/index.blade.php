@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Akun Google Terdaftar</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info text-left" role="alert">
                    <i class="fas fa-info-circle"></i>
                    Data diambil dari User yang berhasil terhubung dengan <strong>Goole Calendar</strong>.
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Pegawai</th>
                                <th>Email Google</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($googleAccounts as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->employee->employee_name }}</td>
                                    <td class="text-center">{{ $item->google_email }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('google-accounts.destroy', $item->id) }}" method="POST"
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
                                    <td colspan="5" class="text-center text-muted">
                                        Data belum tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $googleAccounts->firstItem() }} –
                        {{ $googleAccounts->lastItem() }} dari
                        {{ $googleAccounts->total() }} data
                    </div>

                    <div>
                        {{ $googleAccounts->links() }}
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
                    text: 'Data Akun Google akan dihapus permanen!',
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
