@extends('master')

@section('content')
    <div class="container">
        <h3>Daftar Survei</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Tipe</th>
                    <th>Periode</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($surveys as $survey)
                    <tr>
                        <td>{{ $survey->title }}</td>
                        <td>{{ strtoupper($survey->type) }}</td>
                        <td>
                            {{ $survey->start_date }} - {{ $survey->end_date }}
                        </td>
                        <td>
                            <a href="{{ route('surveys.form', $survey->id) }}" class="btn btn-sm btn-primary">
                                Isi Survei
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
