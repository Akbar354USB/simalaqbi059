@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Daftar Buku Tamu</h5>
            </div>

            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <a href="{{ route('guest_book_create') }}" class="btn btn-primary mb-3">+ Tambah Tamu</a>

                <a href="{{ route('guest_book_print_pdf', request()->query()) }}" class="btn btn-success mb-3"
                    target="_blank">
                    <i class="fas fa-file-pdf"></i> Print PDF
                </a>



                <form action="{{ route('guest_book_index') }}" method="GET" class="mb-3">

                    <div class="form-row">

                        {{-- FILTER TANGGAL --}}
                        <div class="col-md-3">
                            <label>Filter Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>

                        {{-- FILTER BULAN --}}
                        <div class="col-md-3">
                            <label>Filter Bulan</label>
                            <input type="month" name="month" class="form-control" value="{{ request('month') }}">
                        </div>

                        {{-- RANGE TANGGAL MULAI --}}
                        <div class="col-md-3">
                            <label>Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>

                        {{-- RANGE TANGGAL SAMPAI --}}
                        <div class="col-md-3">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>

                    </div>

                    <div class="form-row mt-3">

                        <div class="col-md-12">
                            <button class="btn btn-primary"><small><i class="fas fa-filter"></i></small> Filter</button>
                            <a href="{{ route('guest_book_index') }}" class="btn btn-secondary"><small><i
                                        class="fas fa-sync-alt"></i></small> Reset</a>
                        </div>

                    </div>

                </form>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Tamu</th>
                            <th>Nomor HP</th>
                            <th>Instansi</th>
                            <th>Tujuan</th>
                            <th>Jam Datang</th>
                            <th>Pegawai Yang Ditemui</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($guestBooks as $key => $g)
                            <tr>
                                <td>{{ $guestBooks->firstItem() + $key }}</td>
                                <td>{{ \Carbon\Carbon::parse($g->created_at)->format('d/m/y') }}</td>
                                <td>{{ $g->guest_name }}</td>
                                <td>{{ $g->number_phone }}</td>
                                <td>{{ $g->agency }}</td>
                                <td>{{ $g->objective }}</td>
                                <td>{{ $g->arrival_time }}</td>
                                <td>
                                    @foreach ($g->employees as $emp)
                                        <span class="badge badge-info">{{ $emp->employee_name }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('guest_book_edit', $g->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('guest_book_destroy', $g->id) }}" method="POST"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
                <div class="mt-3 float-right">
                    {{ $guestBooks->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
