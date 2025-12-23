{{-- <!DOCTYPE html>
<html>

<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #eaeaea;
        }
    </style>
</head>

<body>
    <h3 style="text-align: center;">Laporan Buku Tamu</h3>

    <table>
        <thead>
            <tr>
                <th>Nama Tamu</th>
                <th>Nomor HP</th>
                <th>Instansi</th>
                <th>Tujuan</th>
                <th>Jam Datang</th>
                <th>Pegawai Ditemui</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $g)
                <tr>
                    <td>{{ $g->guest_name }}</td>
                    <td>{{ $g->number_phone }}</td>
                    <td>{{ $g->agency }}</td>
                    <td>{{ $g->objective }}</td>
                    <td>{{ $g->arrival_time }}</td>
                    <td>
                        @foreach ($g->employees as $emp)
                            {{ $emp->employee_name }} <br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

</body>

</html> --}}

<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        /* Tabel kop tanpa border */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-table td {
            border: none !important;
            /* border: 1px solid #000; */
            padding: 0;
            margin: 0;
            vertical-align: top;
        }

        .kop-logo {
            width: 100px;
            margin-top: 8px;
            margin-left: 80;
            padding-left: 1px;
            /* naikkan sedikit */
        }

        /* .kop-text {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            line-height: 1.2;
            padding-left: -20px;
        } */

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
            margin-top: 5px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #eaeaea;
        }
    </style>

</head>

<body>

    <!-- ====================== KOP SURAT ====================== -->
    <table class="kop-table">
        <tr>
            <!-- LOGO -->
            <td style="width:20%">
                <img src="{{ public_path('backend/kop.png') }}" class="kop-logo">
            </td>

            <!-- TEKS KOP SURAT -->
            <td class="kop-text" style="width:70%">
                KEMENTERIAN KEUANGAN REPUBLIK INDONESIA <br>
                DIREKTORAT JENDERAL PERBENDAHARAAN <br>
                KANTOR WILAYAH DIREKTORAT JENDERAL PERBENDAHARAAN <br>
                PROVINSI SULAWESI BARAT <br>
                KANTOR PELAYANAN PERBENDAHARAAN NEGARA TIPE A2 MAJENE <br>
                <span class="subtext">
                    Jl. Jenderal Sudirman, Majene 91412; TELEPON (0422) 21061;<br>
                    SUREL: kppnmajene@kemenkeu.go.id; LAMAN: www.djpbn.kemenkeu.go.id/kppn/majene
                </span>
            </td>
            <td style="width:10%"></td>
        </tr>
    </table>

    <hr class="garis-tebal">

    <!-- JUDUL LAPORAN -->
    <h3 style="text-align: center; margin-bottom: 10px;">Laporan Buku Tamu</h3>

    <p style="text-align: left; font-size: 12px; margin-bottom: 12px;">
        <b>Data:</b> {{ $filterText }}
    </p>
    <!-- ====================== TABEL DATA ====================== -->
    <table>
        <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th style="text-align: center">Tanggal</th>
                <th style="text-align: center">Nama Tamu</th>
                <th style="text-align: center">Nomor HP</th>
                <th style="text-align: center">Instansi</th>
                <th style="text-align: center">Tujuan</th>
                <th style="text-align: center">Jam Datang</th>
                <th style="text-align: center">Pegawai Ditemui</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $key => $g)
                <tr>
                    <td style="text-align: center">{{ $key + 1 }}</td>
                    <td style="text-align: center">{{ \Carbon\Carbon::parse($g->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $g->guest_name }}</td>
                    <td style="text-align: center">{{ $g->number_phone }}</td>
                    <td>{{ $g->agency }}</td>
                    <td>{{ $g->objective }}</td>
                    <td style="text-align: center">{{ $g->arrival_time }}</td>
                    <td>
                        @foreach ($g->employees as $emp)
                            {{ $emp->employee_name }} <br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ================== TANDA TANGAN + TANGGAL ================== -->

    <br><br>
    <table style="width: 100%; border: none; margin-top: 20px;">
        <tr>
            <td style="width: 75%; border: none;"></td>
            <td style="width: 25%; border: none; text-align: left;">

                {{-- Majene, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br> --}}
                Majene, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                Mengetahui, <br>
                Kepala Subbagian umum <br><br><br><br><br><br><br>

                {{-- Jika punya file tanda tangan png, aktifkan baris ini --}}
                {{-- <img src="{{ public_path('ttd_kepala.png') }}" width="120"> --}}
                <span style="color: #777; font-size: 12px;">
                    Ditandatangani secara elektronik
                </span>
                <br>
                <b><u>Hendrik Gusti Toding Rante</u></b> <br>
            </td>
        </tr>
    </table>

</body>

</html>
