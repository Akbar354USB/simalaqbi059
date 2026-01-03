@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Tambah Satker</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('agencies.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Kode Satker</label>
                        <input type="text" name="agency_code" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Nama Satker</label>
                        <input type="text" name="agency_name" class="form-control" required>
                    </div>

                    <button class="btn btn-primary">Simpan</button>
                    <a href="{{ route('agencies.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
