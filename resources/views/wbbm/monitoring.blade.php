@extends('master')

@section('css')
    <style>
        .rotate {
            transform: rotate(180deg);
            transition: 0.3s;
        }

        .rotate-icon {
            transition: 0.3s;
        }

        .progress-animated {
            transition: width 0.5s ease-in-out;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4">Monitor Indikator Pencapaian WBBM</h3>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="container-fluid">
        @foreach ($categories as $key => $kategori)
            <div class="card p-3 mb-2" data-kategori="{{ $kategori->id }}">

                <!-- HEADER KATEGORI -->
                <div class="d-flex justify-content-between align-items-center" data-toggle="collapse"
                    data-target="#menu-{{ $kategori->id }}" style="cursor: pointer;">

                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white d-flex justify-content-center align-items-center"
                            style="width: 28px; height: 28px; border-radius: 6px;">
                            {{ $key + 1 }}
                        </div>

                        <div class="ml-3 font-weight-bold">{{ $kategori->name }}</div>
                    </div>

                    <i class="fas fa-chevron-down rotate-icon"></i>
                </div>

                <!-- BODY KATEGORI -->
                <div id="menu-{{ $kategori->id }}" class="collapse mt-2">

                    <!-- PROGRESS BAR -->
                    <div class="ml-3">
                        <strong>
                            Progress:
                            <span id="progress-text-{{ $kategori->id }}">
                                {{ $kategori->progress() }}%
                            </span>
                        </strong>
                    </div>

                    <div class="progress ml-3 mr-5 mt-2 mb-3">
                        <div id="progress-bar-{{ $kategori->id }}" class="progress-bar progress-animated" role="progressbar"
                            style="width: {{ $kategori->progress() }}%">
                            {{ $kategori->progress() }}%
                        </div>
                    </div>

                    <!-- SUBKATEGORI -->
                    {{-- @foreach ($kategori->sub_categories as $sub)

                    @endforeach --}}
                    @foreach ($kategori->sub_categories as $sub)
                        <div class="card p-3 mb-1">
                            <div class="d-flex justify-content-between align-items-center" data-toggle="collapse"
                                data-target="#sub-{{ $sub->id }}" style="cursor: pointer;">

                                <div class="d-flex align-items-center ">
                                    <div class="ml-3 font-weight">{{ $loop->iteration }}. {{ $sub->name }} </div>
                                </div>

                                <div class="ml-auto">
                                </div>

                                <!-- Panah -->
                                <i class="fas fa-chevron-down rotate-icon" id="iconsub-{{ $sub->id }}"></i>
                            </div>

                            <div id="sub-{{ $sub->id }}" class="collapse mt-2">
                                {{-- item --}}
                                @foreach ($sub->items as $item)
                                    <div class="card p-3">
                                        <div class="d-flex justify-content-between align-items-center"
                                            data-toggle="collapse" data-target="#subitem-{{ $item->id }}"
                                            style="cursor: pointer;">
                                            @php
                                                $huruf = range('a', 'z'); // menghasilkan: a, b, c, ... z
                                            @endphp
                                            <div class="d-flex align-items-center ">
                                                <div class="ml-3 font-weight">{{ $huruf[$loop->index] }}.
                                                    {{ $item->name }}</div>
                                            </div>
                                            <div class="ml-auto">
                                            </div>

                                            <!-- Panah -->
                                            <i class="fas fa-chevron-down rotate-icon"
                                                id="iconsubitem-{{ $item->id }}"></i>
                                        </div>

                                        <!-- COLLAPSE ITEM -->
                                        <div id="subitem-{{ $item->id }}" class="collapse mt-2">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Dokumen</th>
                                                        <th>Status</th>
                                                        <th>Upload</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($item->item_documents as $dok)
                                                        <tr>
                                                            <td>{{ $dok->document_name }}</td>

                                                            {{-- Status Upload --}}
                                                            <td id="status-{{ $dok->id }}">
                                                                @if ($dok->upload)
                                                                    <a href="{{ asset('storage/' . $dok->upload->file_path) }}"
                                                                        target="_blank" class="btn btn-success btn-sm mb-2">
                                                                        📄 Lihat File
                                                                    </a>

                                                                    <form
                                                                        action="{{ route('document-delete', $dok->upload->id) }}"
                                                                        method="post" style="display: inline"
                                                                        class="form-check-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button class="btn btn-danger btn-sm delete-upload"
                                                                            data-upload-id="{{ $dok->upload->id }}"
                                                                            data-doc-id="{{ $dok->id }}">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <span class="text-danger">❌ Belum Upload</span>
                                                                @endif
                                                            </td>


                                                            {{-- Form Upload --}}
                                                            <td>
                                                                {{-- Tampil tombol lihat jika ada file --}}
                                                                {{-- <form class="ajax-upload" data-id="{{ $dok->id }}"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="file" name="file"
                                                                        class="form-control form-control-sm mb-2" required>
                                                                    <button type="submit"
                                                                        class="btn btn-primary btn-sm">Upload</button>

                                                                    <div class="upload-status mt-1"></div>
                                                                </form> --}}
                                                                <form class="ajax-upload" data-id="{{ $dok->id }}"
                                                                    enctype="multipart/form-data">
                                                                    @csrf

                                                                    <input type="hidden" name="item_documents_id"
                                                                        value="{{ $dok->id }}">

                                                                    <input type="file" name="file"
                                                                        class="form-control form-control-sm mb-2" required>
                                                                    <button type="submit"
                                                                        class="btn btn-primary btn-sm">Upload</button>

                                                                    <div class="upload-status mt-1"></div>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                                {{-- item --}}
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @endforeach
    </div>
@endsection


@section('js')
    <script>
        // ===============================
        // 1. HANDLE COLLAPSE KATEGORI
        // ===============================

        // Saat kategori dibuka
        $('[id^="menu-"]').on('show.bs.collapse', function() {
            // Tutup kategori lain
            $('[id^="menu-"]').not(this).collapse('hide');

            // Putar icon
            $(this).prev().find('.rotate-icon').addClass('rotate');
        });

        // Saat kategori ditutup
        $('[id^="menu-"]').on('hide.bs.collapse', function() {
            $(this).prev().find('.rotate-icon').removeClass('rotate');
        });


        // ===============================
        // 2. AJAX UPLOAD + UPDATE PROGRESS
        // ===============================

        $(document).on('submit', '.ajax-upload', function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = new FormData(this);
            let item_documents_id = form.data('id');
            let statusBox = form.find('.upload-status');

            statusBox.html('<span class="text-info">Mengupload...</span>');

            $.ajax({
                url: "{{ route('upload.store') }}",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(res) {
                    if (res.success) {
                        statusBox.html('<span class="text-success">✔ Berhasil upload</span>');

                        // Update status dokumen tanpa reload
                        let cell = $(`#status-${item_documents_id}`);
                        if (cell.length) {
                            cell.html(`<a href="${res.file_url}" target="_blank">📄 Lihat File</a>`);
                        }

                        // UPDATE PROGRES KATEGORI
                        let kategoriId = form.closest('[data-kategori]').data('kategori');

                        $.get(`/kategori/${kategoriId}/progress`, function(data) {
                            let progressBar = $(`#progress-bar-${kategoriId}`);
                            progressBar.css("width", data.progress + "%");
                            progressBar.text(data.progress + "%");
                            $(`#progress-text-${kategoriId}`).text(data.progress + "%");
                        });
                    } else {
                        statusBox.html('<span class="text-danger">Gagal upload</span>');
                    }
                },

                error: function() {
                    statusBox.html('<span class="text-danger">Terjadi kesalahan saat upload</span>');
                }
            });
        });
    </script>
@endsection
