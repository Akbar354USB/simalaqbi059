@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Edit Data Satker</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('agencies.update', $agency->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Kode Satker</label>
                        <input type="text" name="agency_code" class="form-control" value="{{ $agency->agency_code }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Nama Satker</label>
                        <input type="text" name="agency_name" class="form-control" value="{{ $agency->agency_name }}"
                            required>
                    </div>

                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('agencies.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
