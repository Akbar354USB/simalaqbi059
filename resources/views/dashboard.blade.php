@extends('master')

@section('content')
    <div class="container-fluid">

        <div class="row mb-3">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Pegawai Terdaftar</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPegawai }}</div>
                                <div class="mt-2 mb-0 text-muted text-xs">
                                    <a href="{{ route('employees.index') }}"><span class="text-success mr-2"><i
                                                class="fas fa-link"></i> Lihat
                                            Data</span></a>
                                    {{-- <span>Since last month</span> --}}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-tie fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Daftar Tamu</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTamu }}</div>
                                <div class="mt-2 mb-0 text-muted text-xs">
                                    <span class="text-success mr-2"><i class="fas fa-link"></i> Lihat Data</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-address-book fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- New User Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Progress Capaian WBK-WBBM</div>
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $overallProgress }} %</div>
                                <div class="mt-2 mb-0 text-muted text-xs">
                                    <a href="{{ route('wbbm-tes-progres') }}"><span class="text-success mr-2"><i
                                                class="fas fa-link"></i> Lihat
                                            Data</span></a>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Satker Terdaftar</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $satker }}</div>
                                <div class="mt-2 mb-0 text-muted text-xs">
                                    <a href="{{ route('agencies.index') }}"><span class="text-success mr-2"><i
                                                class="fas fa-link"></i> Lihat
                                            Data</span></a>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-landmark fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="col-xl-8 col-lg-7">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Progress Pencapaian WBK-WBBM
                        </h6>
                    </div>

                    <div class="card-body">

                        @foreach ($categories as $key => $kategori)
                            <h4 class="small font-weight-bold">
                                {{ $kategori->name }} <span class="float-right">{{ $kategori->progress() }}%</span>
                            </h4>

                            <div class="progress mb-4">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $kategori->progress() }}%" aria-valuenow="{{ $kategori->progress() }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        @endforeach
                        <p class="text-muted small">
                            Target: 100% (WBK → WBBM)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Products Sold</h6>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
