@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="alert alert-success text-left" role="alert">
            <i class="fas fa-info-circle"></i>
            Selamat Datang <strong>{{ Auth::user()->name }}</strong> di SIMONA59. Sistem Monitoring dan Administrasi KPPN
            Majene.
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Pegawai Terdaftar</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalPegawai }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="far fa-newspaper"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Daftar Tamu</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalTamu }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Progress WBK-WBBM</h4>
                        </div>
                        <div class="card-body">
                            {{ $overallProgress }} %
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Satker Terdaftar</h4>
                        </div>
                        <div class="card-body">
                            {{ $satker }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
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
