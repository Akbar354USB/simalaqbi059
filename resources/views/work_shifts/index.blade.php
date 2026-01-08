@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Data Pembagian Shitf Kerja</h5>
            </div>

            <div class="card-body">
                <a href="{{ route('work-shifts.create') }}" class="btn btn-primary mb-2">
                    + Tambah Waktu Shift
                </a>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>Nama Shift</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workShifts as $shift)
                                <tr class="text-center">
                                    <td>{{ $shift->shift_name }}</td>
                                    <td>{{ $shift->start_time }}</td>
                                    <td>{{ $shift->end_time }}</td>
                                    <td>
                                        <a href="{{ route('work-shifts.edit', $shift->id) }}"
                                            class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>

                                        <form action="{{ route('work-shifts.destroy', $shift->id) }}" method="POST"
                                            style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
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
                confirmButtonColor: '#1cc88a'
            });
        </script>
    @endif
@endsection
