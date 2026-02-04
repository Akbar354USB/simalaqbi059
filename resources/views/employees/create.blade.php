@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Tambah Pegawai</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('employees.store') }}" method="POST">
                    @csrf

                    {{-- Nama Pegawai --}}
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Pegawai</label>
                        <input type="text" name="employee_name"
                            class="form-control @error('employee_name') is-invalid @enderror"
                            value="{{ old('employee_name') }}" placeholder="Masukkan nama pegawai" required>
                        @error('employee_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Masukkan email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="form-group mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Status --</option>
                            <option value="PNS" {{ old('status') == 'PNS' ? 'selected' : '' }}>PNS</option>
                            <option value="PPNPN" {{ old('status') == 'PPNPN' ? 'selected' : '' }}>PPNPN</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NIP (muncul hanya jika PNS) --}}
                    <div class="form-group mb-3 d-none" id="nip-wrapper">
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" id="nip"
                            class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}"
                            placeholder="Masukkan NIP">
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Aktif --}}
                    <div class="form-group mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Pegawai Aktif
                            </label>
                        </div>
                    </div>

                    {{-- Button --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script>
        const statusSelect = document.getElementById('status');
        const nipWrapper = document.getElementById('nip-wrapper');
        const nipInput = document.getElementById('nip');

        function toggleNip() {
            if (statusSelect.value === 'PNS') {
                nipWrapper.classList.remove('d-none');
                nipInput.setAttribute('required', true);
            } else {
                nipWrapper.classList.add('d-none');
                nipInput.removeAttribute('required');
                nipInput.value = '';
            }
        }

        statusSelect.addEventListener('change', toggleNip);

        // jalankan saat load (jaga-jaga kalau ada old value)
        toggleNip();
    </script>
@endsection
