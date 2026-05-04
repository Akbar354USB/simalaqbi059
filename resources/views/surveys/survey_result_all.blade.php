@extends('master')

@section('content')
    <div class="container-fluid">

        <h5 class="mb-3 fw-semibold">Hasil Survey & Ranking</h5>

        <div class="row g-3">

            {{-- ================= PNS ================= --}}
            <div class="col-md-6">
                <div class="card shadow-sm border-0">

                    <div class="card-header bg-primary text-white py-2 small fw-semibold">
                        Survey PNS
                    </div>

                    <div class="card-body p-3">

                        <div class="small text-muted mb-2">
                            {{ $surveyPns->title ?? '-' }}
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle small">

                                <thead class="table-light text-center">
                                    <tr>
                                        <th>#</th>
                                        <th class="text-start">Nama</th>
                                        <th>Nilai</th>
                                        <th>Total Survei</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($resultsPns as $i => $row)
                                        <tr class="text-center">

                                            <td>
                                                <span class="fw-bold">#{{ $i + 1 }}</span>
                                            </td>

                                            <td class="text-start">
                                                {{ $row->employee_name }}
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <b>{{ number_format($row->percentage_score, 2) }}%</b>
                                                    <br>
                                                    <span class="text-muted">
                                                        ({{ $row->total_score }} / {{ $row->total_question * 5 }})
                                                    </span>
                                                </div>
                                            </td>

                                            <td>{{ $row->total }}</td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                Belum ada data
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ================= PPNPN ================= --}}
            <div class="col-md-6">
                <div class="card shadow-sm border-0">

                    <div class="card-header bg-success text-white py-2 small fw-semibold">
                        Survey PPNPN
                    </div>

                    <div class="card-body p-3">

                        <div class="small text-muted mb-2">
                            {{ $surveyPpnpn->title ?? '-' }}
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle small">

                                <thead class="table-light text-center">
                                    <tr>
                                        <th>#</th>
                                        <th class="text-start">Nama</th>
                                        <th>Nilai</th>
                                        <th>Total Survei</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($resultsPpnpn as $i => $row)
                                        <tr class="text-center">

                                            <td>
                                                <span class="fw-bold">#{{ $i + 1 }}</span>
                                            </td>

                                            <td class="text-start">
                                                {{ $row->employee_name }}
                                            </td>

                                            <td>
                                                <div class="small">
                                                    <b>{{ number_format($row->percentage_score, 2) }}%</b>
                                                    <br>
                                                    <span class="text-muted">
                                                        ({{ $row->total_score }} / {{ $row->total_question * 5 }})
                                                    </span>
                                                </div>
                                            </td>

                                            <td>{{ $row->total }}</td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                Belum ada data
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
