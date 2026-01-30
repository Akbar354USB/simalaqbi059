<!DOCTYPE html>
<html>

<head>
    <style>
        /* ================= GLOBAL ================= */
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            /* DEFAULT 11 */
            line-height: 1.2;
            margin: 5px 10px 10px 10px;
            /* MARGIN ATAS DIPERKECIL */
        }

        /* ================= KOP SURAT ================= */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .kop-table td {
            border: none !important;
            padding: 0;
            margin: 0;
            vertical-align: top;
        }

        .kop-logo {
            width: 100px;
            margin-top: 5px;
            margin-left: 80px;
        }

        .kop-text {
            text-align: center;
            font-size: 13px;
            /* KOP TETAP LEBIH BESAR */
            font-weight: bold;
            line-height: 1.2;
            transform: translateX(-20px);
        }

        .subtext {
            font-size: 11px;
            /* SUB KOP BOLEH 11 */
            font-weight: normal;
            line-height: 1.2;
        }

        hr.garis-tebal {
            border: 2px solid #000;
            margin: 4px 0 10px 0;
            /* RAPAT KE ATAS */
        }

        /* ================= JUDUL ================= */
        h3 {
            font-size: 11px;
            /* SESUAI PERMINTAAN */
            font-weight: bold;
            text-align: center;
            margin: 4px 0 6px 0;
        }

        p {
            font-size: 11px;
            margin: 0 0 6px 0;
        }

        /* ================= TABEL DATA ================= */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            table-layout: fixed;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            /* CELL RAPAT */
            vertical-align: top;
            line-height: 1.15;
            word-wrap: break-word;
            word-break: break-word;
        }

        .data-table th {
            background: #eaeaea;
            text-align: center;
            font-weight: bold;
        }

        /* ================= KOLOM ================= */
        .col-no {
            width: 4%;
            text-align: center;
        }

        .col-tgl {
            width: 9%;
            text-align: center;
        }

        .col-nama {
            width: 15%;
        }

        .col-hp {
            width: 11%;
            text-align: center;
        }

        .col-instansi {
            width: 15%;
        }

        .col-keperluan {
            width: 21%;
        }

        .col-pegawai {
            width: 25%;
        }

        .pegawai-item {
            display: block;
            line-height: 1.1;
        }

        /* ================= TANDA TANGAN ================= */
        .ttd {
            font-size: 11px;
            line-height: 1.2;
        }
    </style>
</head>

<body>

    <!-- ====================== KOP SURAT ====================== -->
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
                    SUREL: kppnmajene@kemenkeu.go.id; LAMAN: www.djpbn.kemenkeu.go.id/kppn/majene
                </span>
            </td>
            <td style="width:10%"></td>
        </tr>
    </table>

    <hr class="garis-tebal">

    <!-- ================= JUDUL ================= -->
    <h3>Laporan Buku Tamu</h3>

    <p>
        <b>Data:</b> {{ $filterText }}
    </p>

    <!-- ====================== TABEL DATA ====================== -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-tgl">Tanggal</th>
                <th class="col-nama">Nama Tamu</th>
                <th class="col-hp">Nomor HP</th>
                <th class="col-instansi">Instansi</th>
                <th class="col-keperluan">Keperluan</th>
                <th class="col-pegawai">Pegawai Ditemui</th>
                <th class="col-hp">Waktu Datang</th>
                <th class="col-hp">Waktu Pulang</th>
                <th class="col-hp">Durasi Layanan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $g)
                <tr>
                    <td class="col-no">{{ $key + 1 }}</td>
                    <td class="col-tgl">
                        {{ \Carbon\Carbon::parse($g->created_at)->format('d/m/Y') }}
                    </td>
                    <td class="col-nama">{{ $g->guest_name }}</td>
                    <td class="col-hp">{{ $g->number_phone }}</td>
                    <td class="col-instansi">{{ $g->agency->agency_name }}</td>
                    <td class="col-keperluan">{{ $g->objective }}</td>
                    <td class="col-pegawai">
                        @foreach ($g->employees as $emp)
                            <span class="pegawai-item">{{ $emp->employee_name }}</span>
                        @endforeach
                    </td>
                    <td class="col-hp">{{ $g->arrival_time }}</td>
                    <td class="col-hp">{{ $g->departure_time }}</td>
                    <td class="col-hp">
                        @if ($g->arrival_time && $g->departure_time)
                            @php
                                $datang = \Carbon\Carbon::parse($g->arrival_time);
                                $pulang = \Carbon\Carbon::parse($g->departure_time);
                                $durasi = $datang->diff($pulang);
                            @endphp

                            {{ $durasi->h }} jam {{ $durasi->i }} menit
                        @else
                            -
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ================== TANDA TANGAN ================== -->
    <br>
    <table style="width:100%; border:none;">
        <tr>
            <td style="width:75%; border:none;"></td>
            <td class="ttd" style="width:25%; border:none;">
                Majene, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
                Mengetahui, <br>
                Kepala Subbagian Umum <br><br><br><br>

                <span style="color:#777;">
                    Ditandatangani secara elektronik
                </span><br>
                <b><u>Hendrik Gusti Toding Rante</u></b>
            </td>
        </tr>
    </table>

</body>

</html>
