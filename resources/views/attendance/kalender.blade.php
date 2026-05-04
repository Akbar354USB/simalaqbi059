<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid black;
            text-align: center;
            padding: 3px;
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

    <h3 style="text-align:center;">
        Rekap Absensi Bulan {{ $month }} Tahun {{ $year }}
    </h3>

    @php
        // 🔥 mapping warna global
        $statusColors = [
            'H' => '#d4edda',
            'TL' => '#f8d7da',
            'PSW' => '#fff3cd',
            'CT' => '#cce5ff',
            'CSH' => '#d1ecf1',
            'DL' => '#e2e3e5',
            'CS' => '#f5c6cb',
            'TL/PSW' => '#ea868f', // ambil warna PSW
        ];
    @endphp

    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>

                @for ($i = 1; $i <= $daysInMonth; $i++)
                    {{-- <th style="{{ isset($weekends[$i]) ? 'background-color:#ff0000;color:white;' : '' }}"> --}}
                    <th
                        style="
    {{ isset($weekends[$i]) || isset($holidayDates[$i]) ? 'background-color:#ff0000;color:white;' : '' }}">
                        {{ $i }}
                    </th>
                @endfor

                <th>TL</th>
                <th>PSW</th>
                <th>CT</th>
                <th>CSH</th>
                <th>DL</th>
                <th>CS</th>
                <th>Hadir</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['name'] }}</td>

                    {{-- 🔥 LOOP KALENDER --}}
                    @foreach ($row['calendar'] as $dayIndex => $day)
                        @php
                            // prioritas warna: weekend > status
                            // if (isset($weekends[$dayIndex])) {
                            if (isset($weekends[$dayIndex]) || isset($holidayDates[$dayIndex])) {
                                $color = '#ff0000';
                                $textColor = 'white';
                            } else {
                                $color = $statusColors[$day] ?? '';
                                $textColor = 'black';
                            }
                        @endphp

                        <td
                            style="background-color: {{ $color }}; color: {{ $textColor }}; text-align:center;">
                            {{ $day }}
                        </td>
                    @endforeach

                    {{-- 🔥 KOLOM AKUMULASI (BERWARNA SESUAI STATUS) --}}
                    <td style="background-color: {{ $statusColors['TL'] }}; text-align:center;">
                        {{ $row['rekap']['TL'] }}
                    </td>

                    <td style="background-color: {{ $statusColors['PSW'] }}; text-align:center;">
                        {{ $row['rekap']['PSW'] }}
                    </td>

                    <td style="background-color: {{ $statusColors['CT'] }}; text-align:center;">
                        {{ $row['rekap']['CT'] }}
                    </td>

                    <td style="background-color: {{ $statusColors['CSH'] }}; text-align:center;">
                        {{ $row['rekap']['CSH'] }}
                    </td>

                    <td style="background-color: {{ $statusColors['DL'] }}; text-align:center;">
                        {{ $row['rekap']['DL'] }}
                    </td>

                    <td style="background-color: {{ $statusColors['CS'] }}; text-align:center;">
                        {{ $row['rekap']['CS'] }}
                    </td>

                    {{-- 🔥 HADIR --}}
                    <td style="background-color: #d4edda; font-weight:bold; text-align:center;">
                        {{ $row['hadir'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    {{-- @if (!empty($holidays))
        @php
            $holidaysSorted = $holidays->sortBy('start_date')->values();
            $chunks = $holidaysSorted->chunk(ceil($holidaysSorted->count() / 2));
        @endphp

        <div style="font-size:9px; margin-bottom:5px; line-height:1.2;">
            <b>Hari Libur / Cuti:</b>

            <div style="margin-top:3px; overflow:hidden;">

                <!-- KOLOM KIRI -->
                <div style="width:49.5%; float:left;">
                    @foreach ($chunks[0] as $h)
                        <div style="margin-bottom:1px;">
                            <b>
                                {{ \Carbon\Carbon::parse($h->start_date)->format('d') }}
                                @if ($h->end_date && $h->end_date != $h->start_date)
                                    -{{ \Carbon\Carbon::parse($h->end_date)->format('d') }}
                                @endif
                            </b>
                            - {{ $h->name }}
                        </div>
                    @endforeach
                </div>

                <!-- KOLOM KANAN -->
                <div style="width:49.5%; float:left;">
                    @if (isset($chunks[1]))
                        @foreach ($chunks[1] as $h)
                            <div style="margin-bottom:1px;">
                                <b>
                                    {{ \Carbon\Carbon::parse($h->start_date)->format('d') }}
                                    @if ($h->end_date && $h->end_date != $h->start_date)
                                        -{{ \Carbon\Carbon::parse($h->end_date)->format('d') }}
                                    @endif
                                </b>
                                - {{ $h->name }}
                            </div>
                        @endforeach
                    @endif
                </div>

                <div style="clear:both;"></div>
            </div>
        </div>
    @endif --}}

    @if (!empty($holidays))
        @php
            $holidaysSorted = $holidays->sortBy('start_date')->values();
            $chunks = $holidaysSorted->chunk(ceil($holidaysSorted->count() / 2));
        @endphp

        <div style="font-size:8px; margin-bottom:3px; line-height:1.1; width:30%;">
            <b>Hari Libur / Cuti:</b>

            <table style="width:100%; margin-top:2px; border-collapse:collapse; border:none;">
                <tr>
                    <!-- KOLOM KIRI -->
                    <td style="width:50%; vertical-align:top; padding:0 2px 0 0; border:none;">
                        @foreach ($chunks[0] as $h)
                            <div style="margin-bottom:1px;">
                                <b>
                                    {{ \Carbon\Carbon::parse($h->start_date)->format('d') }}
                                    @if ($h->end_date && $h->end_date != $h->start_date)
                                        -{{ \Carbon\Carbon::parse($h->end_date)->format('d') }}
                                    @endif
                                </b>
                                {{ $h->name }}
                            </div>
                        @endforeach
                    </td>

                    <!-- KOLOM KANAN -->
                    <td style="width:50%; vertical-align:top; padding:0 0 0 2px; border:none;">
                        @if (isset($chunks[1]))
                            @foreach ($chunks[1] as $h)
                                <div style="margin-bottom:1px;">
                                    <b>
                                        {{ \Carbon\Carbon::parse($h->start_date)->format('d') }}
                                        @if ($h->end_date && $h->end_date != $h->start_date)
                                            -{{ \Carbon\Carbon::parse($h->end_date)->format('d') }}
                                        @endif
                                    </b>
                                    {{ $h->name }}
                                </div>
                            @endforeach
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    @endif

    @php
        $statusColors = [
            'H' => '#d4edda',
            'TL' => '#f8d7da',
            'PSW' => '#fff3cd',
            'CT' => '#cce5ff',
            'CSH' => '#d1ecf1',
            'DL' => '#e2e3e5',
            'CS' => '#f5c6cb',
        ];

        $statusLabels = [
            'H' => 'Hadir',
            'TL' => 'Terlambat',
            'PSW' => 'Pulang sebelum waktunya',
            'CT' => 'Cuti Tahunan',
            'CSH' => 'Cuti setengah hari',
            'DL' => 'Dinas Luar',
            'CS' => 'Cuti Sakit',
        ];
    @endphp

    <table width="100%" style="margin-top:10px; border:none;">
        <tr>
            {{-- 🔥 KOLOM KIRI (KETERANGAN) --}}
            <td style="width:75%; vertical-align:top; border:none;">

                <table border="1" cellspacing="0" cellpadding="5" style="width:250px;">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($statusLabels as $kode => $label)
                            <tr>
                                <td
                                    style="background-color: {{ $statusColors[$kode] }}; text-align:center; font-weight:bold;">
                                    {{ $kode }}
                                </td>
                                <td style="background-color: {{ $statusColors[$kode] }};">
                                    {{ $label }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </td>

            {{-- 🔥 KOLOM KANAN (TTD) --}}
            <td style="width:25%; text-align:left; vertical-align:top; border:none;">

                <div style="font-size:12px; margin-bottom:60px;">
                    Majene, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    Mengetahui,<br>
                    Kepala Subbagian Umum
                </div>

                {{-- JARAK TTD --}}
                <div style="height:60px;"></div>



                <div style="font-size:12px; color:#777;">
                    Ditandatangani secara elektronik
                </div>
                <div style="font-size:12px; font-weight:bold;">
                    <b><u>Hendrik Gusti Toding Rante</u></b>
                </div>

            </td>
        </tr>
    </table>

</body>

</html>
