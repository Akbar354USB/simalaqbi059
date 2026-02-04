@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Unit Kerja</h5>
            </div>

            <div class="card-body">
                <a href="{{ route('work-units.create') }}" class="btn btn-primary mb-3">
                    + Unit Kerja
                </a>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th width="50">No</th>
                                <th>Unit Kerja</th>
                                <th>Nama Pimpinan</th>
                                <th>NIP Pimpinan</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($workUnits as $item)
                                <tr class="text-center">
                                    <td>{{ $workUnits->firstItem() + $loop->index }}</td>
                                    <td>{{ $item->work_unit }}</td>

                                    {{-- Nama Pimpinan --}}
                                    <td>
                                        {{ $item->employee->employee_name ?? '-' }}
                                    </td>

                                    {{-- NIP dari relasi employee --}}
                                    <td>
                                        {{ $item->employee->nip ?? '-' }}
                                    </td>

                                    <td>
                                        <form action="{{ route('work-units.destroy', $item->id) }}" method="POST"
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
                                        Data unit kerja belum tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $workUnits->firstItem() }} –
                        {{ $workUnits->lastItem() }} dari
                        {{ $workUnits->total() }} data
                    </div>

                    <div>
                        {{ $workUnits->links() }}
                    </div>
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

    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Yakin?',
                    text: 'Data unit kerja akan dihapus permanen!',
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
