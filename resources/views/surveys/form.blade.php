@extends('master')

@section('content')
    <div class="container">

        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Judul Survei --}}
        <h3>{{ $survey->title }}</h3>
        <p>{{ $survey->description }}</p>

        {{-- Form --}}
        <form action="{{ route('surveys.submit.type', $type) }}" method="POST">
            @csrf

            {{-- Pegawai yang Dinilai --}}
            <div class="mb-3">
                <label><b>Pegawai yang Dinilai</b></label>
                <input type="text" class="form-control" value="{{ $currentTarget->employee->name }}" readonly>

                <input type="hidden" name="employee_id" value="{{ $currentTarget->employee->id }}">
            </div>

            <hr>

            {{-- Pertanyaan --}}
            @foreach ($survey->questions as $index => $question)
                <div class="mb-4 p-3 border rounded">
                    <label>
                        <b>{{ $index + 1 }}. {{ $question->question }}</b>
                    </label>

                    {{-- Skor 1–5 --}}
                    <div class="d-flex gap-3 mt-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="me-3">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $i }}"
                                    required>
                                {{ $i }}
                            </label>
                        @endfor
                    </div>

                    {{-- Keterangan Skor --}}
                    <small class="text-muted">
                        1 = Sangat Buruk, 5 = Sangat Baik
                    </small>

                    {{-- Komentar --}}
                    <textarea name="comments[{{ $question->id }}]" class="form-control mt-2" placeholder="Komentar (opsional)"></textarea>
                </div>
            @endforeach

            {{-- Tombol --}}
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">
                    Simpan & Next
                </button>
            </div>
        </form>
    </div>
@endsection
