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
                        <label>Nama Instansi / Satker</label>

                        <select name="agency_id" id="agency_id"
                            class="form-control select2 @error('agency_id') is-invalid @enderror" required>
                            <option value="">-- Cari Instansi / Satker --</option>

                            @foreach ($agency as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->agency_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('agency_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Tujuan</label>
                        <input type="text" name="objective" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Jam Datang</label>
                        <input type="time" name="arrival_time"
                            class="form-control @error('arrival_time') is-invalid @enderror">

                        @error('arrival_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label>Pegawai Yang Ingin Ditemui</label>

                        <select name="employee_ids[]"
                            class="form-control select2 @error('employee_ids') is-invalid @enderror" multiple>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">
                                    {{ $emp->employee_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('employee_ids')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <button type="submit" class="btn btn-primary">Simpan</button>

                </form>
                <div class="modal fade" id="addAgencyModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Instansi / Satker</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nama Instansi / Satker</label>
                                    <input type="text" id="new_agency_name" class="form-control">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-primary" id="saveAgency">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Ketik untuk mencari...',
                width: '100%',
                allowClear: true
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $('#agency_id').select2({
                placeholder: 'Ketik nama instansi...',
                width: '100%',
                language: {
                    noResults: function() {
                        return `
                    <div class="text-center">
                        <p class="mb-2">Instansi tidak ditemukan</p>
                        <button class="btn btn-sm btn-primary" id="addNewAgency">
                            + Tambah Instansi
                        </button>
                    </div>
                `;
                    }
                },
                escapeMarkup: function(markup) {
                    return markup;
                }
            });

            // Klik tambah instansi
            $(document).on('click', '#addNewAgency', function() {
                let keyword = $('.select2-search__field').val();
                $('#new_agency_name').val(keyword);
                $('#addAgencyModal').modal('show');
            });

            // Simpan instansi via AJAX
            $('#saveAgency').on('click', function() {
                let agencyName = $('#new_agency_name').val();

                if (agencyName === '') {
                    alert('Nama instansi wajib diisi');
                    return;
                }

                $.ajax({
                    url: "{{ route('agencies.store') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        agency_name: agencyName
                    },
                    success: function(response) {

                        // Tambahkan ke select2
                        let newOption = new Option(
                            response.agency_name,
                            response.id,
                            true,
                            true
                        );

                        $('#agency_id').append(newOption).trigger('change');

                        $('#addAgencyModal').modal('hide');
                        $('#new_agency_name').val('');
                    }
                });
            });

        });
    </script>
@endsection
