@extends('master')

@section('css')
    <style>
        /* Hilangkan padding default table cell */
        .table td,
        .table th {
            padding: 0 !important;
            vertical-align: middle !important;
        }

        /* Hilangkan margin default isi cell */
        .table td>* {
            /* margin: 0 !important; */
            margin: 4px;
            padding: 4px 6px;
        }

        /* Kolom kiri merapat ke kanan */
        .cell-right {
            text-align: right !important;
        }

        /* Kolom kanan merapat ke kiri */
        .cell-left {
            text-align: left !important;
        }
    </style>
@endsection


@section('content')
    <h1>inites progres</h1>

    <div class="card container-fluid">
        <table class="table table-borderless mt-1 mb-1">
            @foreach ($categories as $key => $kategori)
                <tr>
                    <td class="cell-right" style="width: 40%;">
                        <p>{{ $kategori->name }}</p>
                    </td>
                    <td class="cell-left" style="width: 60%;">
                        <div class="progress" style="height: 40px;">
                            <div id="progress-bar-{{ $kategori->id }}" class="progress-bar progress-animated"
                                role="progressbar" style="width: {{ $kategori->progress() }}%"
                                aria-valuenow="{{ $kategori->progress() }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $kategori->progress() }}%
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>


    </div>
@endsection



{{-- <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Progress Bars with Label</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <td>
                        <p>aadasad</p>
                    </td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25"
                                aria-valuemin="0" aria-valuemax="100">25%</div>
                        </div>
                    </td>
                </tr>

            </table>
            <p>Add labels to your progress bars by placing text within the
                <code>.progress-bar</code>.
            </p>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100">25%</div>
            </div>
        </div>
    </div> --}}
