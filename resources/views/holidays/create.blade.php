@extends('master')

@section('content')
    <div class="container mt-4">
        <h4>Tambah Hari Libur / Cuti</h4>

        <form method="POST" action="{{ route('holidays.store') }}">
            @csrf

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Tipe Tanggal</label>
                <select id="type" name="type" class="form-control" onchange="toggleEndDate()">
                    <option value="single">Satu Hari</option>
                    <option value="range">Range Tanggal</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>

            <div class="mb-3" id="endDateField" style="display:none;">
                <label>Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" class="form-control">
            </div>

            <div class="mt-3">
                <button class="btn btn-success">Simpan</button>
                <a href="{{ route('holidays.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        function toggleEndDate() {
            let type = document.getElementById('type').value;
            let endField = document.getElementById('endDateField');
            let endInput = document.getElementById('end_date');

            if (type === 'range') {
                endField.style.display = 'block';
            } else {
                endField.style.display = 'none';
                if (endInput) endInput.value = '';
            }
        }
    </script>
@endsection
