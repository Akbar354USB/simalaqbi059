<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
            margin-left: 80;
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
            padding: 6px;
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

    <!-- ================= KOP SURAT ================= -->
    <table class="kop-table">
        <tr>
            <td style="width:20%">
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

            <td style="width:10%"></td>
        </tr>
    </table>

    <hr class="garis-tebal">

    <!-- ================= JUDUL ================= -->
    <h3 style="text-align:center; margin-bottom: 5px;">
        LAPORAN ABSENSI PEGAWAI
    </h3>

    <!-- ================= FILTER INFO ================= -->
    <p style="font-size:12px; margin-bottom: 12px;">
        <b>Data :</b>
        @if ($request->date)
            Tanggal {{ \Carbon\Carbon::parse($request->date)->translatedFormat('d F Y') }}
        @elseif ($request->month)
            Bulan {{ \Carbon\Carbon::parse($request->month)->translatedFormat('F Y') }}
        @else
            Semua Data
        @endif
    </p>

    <!-- ================= TABEL ABSENSI ================= -->
    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Shift</th>
                <th>Jam Absen</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($attendances as $key => $attendance)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">
                        {{ $attendance->employee->employee_name ?? '-' }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d/m/Y') }}
                    </td>
                    <td>{{ $attendance->type }}</td>
                    <td>{{ $attendance->workShift->shift_name ?? '-' }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($attendance->attendance_time)->format('H:i:s') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Data absensi tidak tersedia</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ================= TANDA TANGAN ================= -->
    <br><br>
    <table style="width:100%; border:none;">
        <tr>
            <td style="width:70%; border:none;"></td>
            <td style="width:30%; border:none; text-align:left;">
                Majene, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                Mengetahui, <br>
                Kepala Subbagian Umum <br><br><br><br>

                <span style="font-size:11px; color:#777;">
                    Ditandatangani secara elektronik
                </span>
                <br>
                <b><u>Hendrik Gusti Toding Rante</u></b>
            </td>
        </tr>
    </table>

</body>

</html>
