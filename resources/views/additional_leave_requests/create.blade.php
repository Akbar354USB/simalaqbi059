@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm mb-2">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Ajukan Cuti Tambahan</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('additional-leave-requests.store') }}" method="POST">
                    @csrf

                    {{-- INFO PEGAWAI --}}
                    <div class="alert alert-info">
                        <strong>Nama:</strong>
                        {{ auth()->user()->employee->employee_name ?? '-' }} <br>

                        <strong>NIP:</strong>
                        {{ auth()->user()->employee->nip ?? '-' }}
                    </div>

                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" name="position" class="form-control" value="{{ old('position') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Masa Kerja</label>
                        <input type="text" name="length_of_service" class="form-control"
                            value="{{ old('length_of_service') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Unit Kerja</label>
                        <select name="work_unit_id" class="form-control" required>
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach ($workUnits as $unit)
                                <option value="{{ $unit->id }}"
                                    {{ old('work_unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->work_unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Alasan Cuti</label>
                        <textarea name="leave_reason" class="form-control" required>{{ old('leave_reason') }}</textarea>
                    </div>

                    <label class="font-weight-bold mt-2">Waktu Cuti</label>

                    <div id="period-wrapper">
                        <div class="border rounded p-3 mb-3 period-item">
                            <strong>Periode 1</strong>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" name="periods[0][start_date]" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Tanggal Selesai</label>
                                    <input type="date" name="periods[0][end_date]" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add-period" class="btn btn-outline-primary btn-sm mb-3">
                        <i class="fas fa-plus"></i> Tambah Periode
                    </button>

                    <div class="form-group">
                        <label>No. Telp</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Alamat Selama Cuti</label>
                        <textarea name="leave_address" class="form-control" required>{{ old('leave_address') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        let periodIndex = 1;
        let maxPeriod = 2;

        document.getElementById('add-period').addEventListener('click', function() {

            if (periodIndex >= maxPeriod) {
                return;
            }

            const wrapper = document.getElementById('period-wrapper');

            const html = `
            <div class="border rounded p-3 mb-3 period-item">
                <strong>Periode ${periodIndex + 1}</strong>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="periods[${periodIndex}][start_date]" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="periods[${periodIndex}][end_date]" class="form-control">
                    </div>
                </div>
            </div>
        `;

            wrapper.insertAdjacentHTML('beforeend', html);
            periodIndex++;

            // 🔒 Disable tombol setelah 2 periode
            this.disabled = true;
            this.classList.add('disabled');
            this.innerText = 'Periode maksimal tercapai';
        });
    </script>
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Pengajuan Cuti Gagal',
                html: `
                <ul style="text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
                confirmButtonText: 'Mengerti'
            });
        </script>
    @endif
@endsection
