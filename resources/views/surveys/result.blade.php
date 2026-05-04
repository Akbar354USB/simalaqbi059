@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Hasil Survei</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pegawai</th>
                    <th>Total Skor</th>
                    <th>Rata-rata</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $result)
                    @php
                        $total = $result->details->sum('score');
                        $count = $result->details->count();
                        $avg = $count ? round($total / $count, 2) : 0;
                    @endphp
                    <tr>
                        <td>{{ $result->employee->name }}</td>
                        <td>{{ $total }}</td>
                        <td>{{ $avg }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
