@php
    $layout = in_array(auth()->user()->role, ['admin', 'superadmin']) ? 'master' : 'master-no-sidebar';
@endphp

@extends($layout)

@section('css')
    <style>
        .select2-container .select2-selection--multiple {
            font-size: 1rem;
            padding: 4px;
        }

        .select2-container .select2-selection__placeholder,
        .select2-container .select2-search__field,
        .select2-results__option {
            font-size: 0.8rem !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <a href="{{ route('guest_book_index') }}" class="btn btn-primary mb-1">Lihat Data Buku Tamu</a>
        <div class="card mb-2">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    Resepsionis - Daftar Terima Tamu
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('guest_book_store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Nama Tamu</label>
                        <input type="text" name="guest_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Nomor HP</label>
                        <input type="text" name="number_phone" class="form-control" required>
                    </div>

                    {{-- INSTANSI --}}
                    <div class="form-group">
                        <label>Nama Instansi / Satker</label>

                        <select name="agency_id" id="agency_id"
                            class="form-control select2 @error('agency_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Instansi / Satker --</option>
                            @foreach ($agency as $item)
                                <option value="{{ $item->id }}">{{ $item->agency_name }}</option>
                            @endforeach
                        </select>

                        @error('agency_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addAgencySwal">
                                + Tambah Instansi / Satker
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Keperluan</label>
                        <input type="text" name="objective" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Jam Datang</label>
                        <input type="time" name="arrival_time" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Pegawai Yang Ingin Ditemui</label>
                        <select name="employee_ids[]" class="form-control select2" multiple required>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->employee_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {

            // INIT SELECT2
            $('.select2').select2({
                width: '100%',
                allowClear: true,
                dropdownParent: $('body')
            });

            // TAMBAH INSTANSI VIA SWEETALERT
            $(document).on('click', '#addAgencySwal', function() {

                Swal.fire({
                    title: 'Tambah Instansi / Satker',
                    input: 'text',
                    inputLabel: 'Nama Instansi',
                    inputPlaceholder: 'Masukkan nama instansi...',
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Nama instansi wajib diisi';
                        }
                    },
                    preConfirm: (agencyName) => {
                        return $.ajax({
                            url: "{{ route('agencies.store') }}",
                            type: "POST",
                            dataType: "json",
                            data: {
                                agency_name: agencyName,
                                _token: "{{ csrf_token() }}"
                            }
                        }).catch(xhr => {
                            Swal.showValidationMessage(
                                `Gagal menyimpan instansi`
                            );
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {

                        let response = result.value;

                        let option = new Option(
                            response.agency_name,
                            response.id,
                            true,
                            true
                        );

                        $('#agency_id').append(option).trigger('change');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Instansi berhasil ditambahkan',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });

        });
    </script>
@endsection
