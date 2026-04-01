<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        /* ================= PENGATURAN CETAK ================= */
        @page {
            size: A4 portrait;
            /* pastikan orientasi portrait */
            margin: 5mm;
            /* sesuaikan margin */
        }

        @media print {

            /* Kop surat hanya muncul di halaman pertama */
            .kop-container {
                display: block;
            }

            .kop-container+hr.garis-tebal {
                display: block;
            }

            /* Sembunyikan kop surat di halaman selain pertama */
            .kop-container,
            .kop-container+hr.garis-tebal {
                page-break-after: avoid;
            }

            body {
                font-size: 11px;
            }
        }

        /* Agar kop surat tidak ikut di halaman berikutnya */
        @media print {
            .kop-container {
                page-break-after: always;
                /* kop surat berhenti di halaman pertama */
            }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        /* ================= KOP SURAT ================= */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-table td {
            border: none !important;
            padding: 0;
            vertical-align: top;
        }

        .kop-logo {
            width: 100px;
            margin-top: 8px;
            margin-left: 45;
            padding-left: 1px;
            /* naikkan sedikit */
        }

        .kop-text {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            line-height: 1.2;
            transform: translateX(-20px);
        }

        .subtext {
            font-size: 11px;
            font-weight: normal;
        }

        hr.garis-tebal {
            border: 2px solid #000;
            margin-top: 6px;
            margin-bottom: 15px;
        }

        /* ================= TABEL DATA ================= */
        table.data {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 2px 4px;
            /* atur jarak isi cell dengan teks */
        }

        table.data th {
            background: #eaeaea;
            text-align: center;
        }

        table.data td {
            text-align: center;
        }

        .text-left {
            text-align: left !important;
        }
    </style>
</head>

<body>

    <!-- KOP -->
    <div class="kop-container">
        <table class="kop-table">
            <tr>
                <td style="width:10%">
                    <img src="{{ public_path('backend/kop.png') }}" class="kop-logo">
                </td>
                <td class="kop-text" style="width:70%">
                    KEMENTERIAN KEUANGAN REPUBLIK INDONESIA <br>
                    DIREKTORAT JENDERAL PERBENDAHARAAN <br>
                    KANTOR WILAYAH DIREKTORAT JENDERAL PERBENDAHARAAN <br>
                    PROVINSI SULAWESI BARAT <br>
                    KANTOR PELAYANAN PERBENDAHARAAN NEGARA TIPE A2 MAJENE <br>
                    <span class="subtext">
                        Jl. Jenderal Sudirman, Majene 91412; TELEPON (0422) 21061;<br>
                        SUREL: kppnmajene@kemenkeu.go.id; LAMAN:
                        www.djpbn.kemenkeu.go.id/kppn/majene
                    </span>
                </td>
                <td style="width:20%"></td>
            </tr>
        </table>
        <hr class="garis-tebal">
    </div>
    <!-- JUDUL -->
    <h3 style="text-align:center;">LAPORAN LEMBUR PEGAWAI</h3>

    <!-- FILTER -->
    <p>
        <b>Tanggal :</b> {{ request('tanggal') ?? 'Semua' }}
        @if ($employeeName)
            | <b>Pegawai :</b> {{ $employeeName }}
        @endif
        @if (request('status'))
            | <b>Status :</b> {{ request('status') }}
        @endif
    </p>

    <!-- TABEL -->
    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Durasi</th>
                <th>Keperluan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>

                    <td class="text-left">
                        {{ $item->employee->employee_name ?? '-' }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($item->overtime_date)->format('d/m/Y') }}
                    </td>

                    <td>
                        {{ ucfirst($item->status) }}
                    </td>

                    <td>
                        {{ $item->duration ?? 0 }} menit
                    </td>

                    <td class="text-left">
                        {{ $item->purpose }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Data lembur tidak tersedia</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TTD -->
    <br><br>
    <table style="width:100%; border:none;">
        <tr>
            <td style="width:70%; border:none;"></td>
            <td style="width:30%; border:none; text-align:left;">
                Majene, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                Mengetahui, <br>
                Kepala Subbagian Umum <br><br><br><br><br><br><br><br>

                <span style="font-size:11px; color:#777;">
                    Ditandatangani secara elektronik
                </span>
                <br>
                <b>Hendrik Gusti Toding Rante</b>
            </td>
        </tr>
    </table>

</body>

</html>
