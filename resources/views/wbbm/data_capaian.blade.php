@extends('master')

@section('content')
    <h2>Indikator Pencapaian</h2>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <a href="{{ route('wbbm-create') }}"><button type="button" class="btn btn-primary mb-3">+ Data</button></a>
    <a href="{{ route('wbbm-monitor') }}"><button type="button" class="btn btn-primary mb-3">Upload Dokumen Dan Cek
            Progres</button></a>

    <div class="card card-body container-fluid mb-2">
        <table class="table table-bordered" style="font-size: 14px;">
            <thead class="text-center font-weight-bold text-white">
                <tr style="background:#3F4E6B;">
                    <th style="width: 50%;">Rencana Kerja</th>
                    <th style="width: 45%;">Bukti Dukung</th>
                    <th style="width: 5%;">Action</th>
                </tr>
            </thead>

            <tbody>

                <!-- ========================= -->
                <!-- I. KATEGORI 1 -->
                <!-- ========================= -->
                @foreach ($categories as $key => $kategori)
                    <tr style="background:#5DAA68; font-weight:bold;" class="text-center text-white">
                        <td colspan="2">{{ $kategori->name }}</td>
                        <td>
                            {{-- <a href="#" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </a> --}}
                            <form action="{{ route('category-delete', $kategori->id) }}" method="post"
                                style="display: inline" class="form-check-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @foreach ($kategori->sub_categories as $sub)
                        <!-- Sub Kategori 2 -->
                        <tr style="background:#F5EEC8; font-weight:bold;">
                            <td colspan="2">{{ $loop->iteration }}. {{ $sub->name }} </td>
                            <td>
                                <form action="{{ route('subcategory-delete', $sub->id) }}" method="post"
                                    style="display: inline" class="form-check-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @foreach ($sub->items as $item)
                            <tr>
                                @php
                                    $huruf = range('a', 'z'); // menghasilkan: a, b, c, ... z
                                @endphp
                                <td>{{ $huruf[$loop->index] }}. {{ $item->name }}</td>
                                <td>
                                    @foreach ($item->item_documents as $dok)
                                        - {{ $dok->document_name }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    <form action="{{ route('item-delete', $item->id) }}" method="post"
                                        style="display: inline" class="form-check-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
