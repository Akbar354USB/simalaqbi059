@extends('master')

@section('css')
    <style>
        /* Ukuran teks dropdown */
        .select2-container .select2-selection--multiple {
            font-size: 1rem;
            /* small */
            padding: 4px;
        }

        /* Ukuran teks placeholder */
        .select2-container .select2-selection__placeholder {
            font-size: 0.8rem !important;
        }

        /* Ukuran teks item pencarian */
        .select2-container .select2-search__field {
            font-size: 0.8rem !important;
        }

        /* Ukuran teks hasil pencarian */
        .select2-results__option {
            font-size: 0.8rem;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card mb-2">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Resepsionis - Daftar Terima Tamu</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('guest_book_store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Nama Tamu</label>
                        <input type="text" name="guest_name" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Nomor HP</label>
                        <input type="text" name="number_phone" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Nama Instansi/Satker</label>
                        <input type="text" name="agency" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Tujuan</label>
                        <input type="text" name="objective" class="form-control">
                    </div>

                    {{-- <div class="form-group">
                        <label>Jam Datang</label>
                        <input type="text" name="arrival_time" class="form-control">
                    </div> --}}

                    <div class="form-group">
                        <label>Jam Datang</label>
                        <input type="time" name="arrival_time"
                            class="form-control @error('arrival_time') is-invalid @enderror">

                        @error('arrival_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label>Pegawai Yang Ingin Ditemui</label><br>

                        <select name="employee_ids[]" class="form-control form-control-lg select2" multiple>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->employee_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Cari Pegawai",
                allowClear: true
            });
        });
    </script>
@endsection
