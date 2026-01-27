@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Data Satker</h5>
            </div>

            <div class="card-body">
                <a href="{{ route('agencies.create') }}" class="btn btn-primary mb-2">
                    + Tambah Data Satker
                </a>
                <form action="{{ route('agencies.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari kode atau nama satker..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <a href="{{ route('agencies.index') }}" class="btn btn-secondary">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Satker</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agencies as $agency)
                            <tr>
                                <td>{{ $agency->agency_name }}</td>
                                <td>
                                    <a href="{{ route('agencies.edit', $agency->id) }}" class="btn btn-warning btn-sm"><i
                                            class="fas fa-edit"></i></a>

                                    <form action="{{ route('agencies.destroy', $agency->id) }}" method="POST"
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
                        Menampilkan {{ $agencies->firstItem() }} –
                        {{ $agencies->lastItem() }} dari
                        {{ $agencies->total() }} data
                    </div>

                    <div>
                        {{ $agencies->links() }}
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
                    text: 'Data Satker akan dihapus permanen!',
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
