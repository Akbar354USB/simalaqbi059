{{-- @php
    $pegawai = ['pegawai', 'unit_head', 'head_office'];
@endphp
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#">SIMALAQBI 059</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="#">059</a>
        </div>
        <ul class="sidebar-menu">
            @if (Auth::user()->role == 'superadmin')
                <li class="menu-header">Dashboard</li>
                <li><a class="nav-link" href="{{ route('home') }}"><i class="fas fa-fire"></i>
                        <span>Dashboard</span></a>
                </li>
                <li class="menu-header">Data</li>
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-database"></i>
                        <span>Data</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('employees.index') }}">Data Pegawai</a></li>
                        <li><a href="{{ route('work-units.index') }}">Data Unit Kerja</a></li>
                        <li><a href="{{ route('agencies.index') }}">Data Satker/Instasi</a></li>
                        <li><a href="{{ route('users.index') }}">Manajemen User</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-user-clock"></i>
                        <span>Absensi PPNPN</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('work-shifts.index') }}">Pembagian Shift Kerja</a></li>
                        <li><a href="{{ route('attendance.index') }}">Halaman Absensi</a></li>
                        <li><a href="{{ route('attendances.data') }}">Data Absensi PPNPN</a></li>
                    </ul>
                </li>
                <li><a class="nav-link" href="{{ route('guest_book_index') }}"><i class="fas fa-clipboard"></i>

                        <span>Buku Tamu</span></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-bell"></i>
                        <span>Reminder Hadirku059</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('work-schedules.index') }}">Jadwal Reminder</a></li>
                        <li><a href="{{ route('google-accounts.index') }}">Akun google terdaftar</a></li>
                        <li><a href="{{ route('reminder-logs.index') }}">Monitoring Reminder</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-calendar-check"></i>
                        <span>Cuti Tambahan</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('additional-leave-requests.create') }}">Form Pengajuan
                                Cuti</a></li>
                        <li><a href="{{ route('additional-leave-requests.history') }}">Riwayat Cuti</a></li>
                        <li><a href="{{ route('additional-leave-requests.index') }}">Data Pengajuan
                                Cuti</a></li>
                        <li><a href="{{ route('additional-leaves.index') }}">Kuota Cuti Pegawai</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-chart-line"></i>
                        <span>WBK-WBBM</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('wbbm-tes-progres') }}">Cek Progress</a></li>
                        <li><a href="{{ route('wbbm-data') }}">Indikator Capaian</a></li>
                    </ul>
                </li>
            @endif
            @if (in_array(auth()->user()->role, $pegawai))
                <li class="menu-header">Dashboard</li>
                <li><a class="nav-link" href="{{ route('home') }}"><i class="fas fa-fire"></i>
                        <span>Dashboard</span></a>
                </li>
                <hr>
                @if (Auth::user()->role == 'head_office')
                    <li><a class="nav-link" href="{{ route('head-office.approvals.index') }}"><i
                                class="fas fa-check-circle"></i>
                            <span>Approve Cuti</span></a>
                    </li>
                @endif
                @if (Auth::user()->role == 'unit_head')
                    <li><a class="nav-link" href="{{ route('unit-head.approvals.index') }}"><i
                                class="fas fa-check-circle"></i>
                            <span>Approve Cuti</span></a>
                    </li>
                @endif
                <li><a class="nav-link" href="{{ route('additional-leave-requests.history') }}"><i
                            class="fas fa-calendar-check"></i>
                        <span>Cuti Tambahan</span></a>
                </li>
                <li><a class="nav-link" href="{{ route('wbbm-data') }}"><i class="fas fa-chart-line"></i>
                        <span>WBK-WBBM</span></a>
                </li>
            @endif
        </ul>
        <hr>
    </aside>
</div>


@if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
@endif --}}

@php
    $pegawai = ['pegawai', 'unit_head', 'head_office'];
@endphp

<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="{{ asset('backend/logosimalaqbisidebar.png') }}" alt="logo"
                    style="max-width:150px; height:auto; display:block; margin:0 auto;">

            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                @if (Auth::user()->role == 'superadmin')
                    <li class="pc-item">
                        <a href="{{ route('home') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Data</label>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"><i
                                    class="ti ti-database"></i></span><span class="pc-mtext">Data</span><span
                                class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('employees.index') }}">Data
                                    Pegawai</a>
                            </li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('work-units.index') }}">Data Unit
                                    Kerja</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('agencies.index') }}">Data
                                    Satker/Instasi</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('users.index') }}">Manajemen User</a>
                            </li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-user-check"></i>
                            </span><span class="pc-mtext">PPNPN</span><span class="pc-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('work-shifts.index') }}">Pembagian
                                    Shift
                                    Kerja</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('attendance.index') }}">Halaman
                                    Absensi</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('attendances.data') }}">Data Absensi
                                    PPNPN</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('overtime.index') }}">Data Lembur
                                    PPNPN</a></li>
                        </ul>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('guest_book_index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-clipboard-list"></i>
                            </span>
                            <span class="pc-mtext">Buku Tamu</span>
                        </a>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-alarm"></i>
                            </span><span class="pc-mtext">Hadirku 059</span><span class="pc-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('work-schedules.index') }}">Jadwal
                                    Reminder</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('google-accounts.index') }}">Akun
                                    google
                                    terdaftar</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('reminder-logs.index') }}">Monitoring
                                    Reminder</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-calendar-off"></i>
                            </span><span class="pc-mtext">Cuti Tambahan</span><span class="pc-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('additional-leave-requests.create') }}">Form Pengajuan
                                    Cuti</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('additional-leave-requests.history') }}">Riwayat Cuti</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('additional-leave-requests.index') }}">Data Pengajuan
                                    Cuti</a></li>
                            <li class="pc-item"><a class="pc-link"
                                    href="{{ route('additional-leaves.index') }}">Kuota
                                    Cuti Pegawai</a></li>
                        </ul>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-chart-line"></i>
                            </span><span class="pc-mtext">WBK - WBBM</span><span class="pc-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('wbbm-tes-progres') }}">Cek
                                    Progress</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('wbbm-data') }}">Indikator
                                    Capaian</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (in_array(auth()->user()->role, $pegawai))
                    <li class="pc-item">
                        <a href="{{ route('home') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>
                    @if (Auth::user()->role == 'head_office')
                        <li><a class="nav-link" href="{{ route('head-office.approvals.index') }}"><i
                                    class="fas fa-check-circle"></i>
                                <span>Approve Cuti</span></a>
                        </li>
                    @endif
                    @if (Auth::user()->role == 'unit_head')
                        <li><a class="nav-link" href="{{ route('unit-head.approvals.index') }}"><i
                                    class="fas fa-check-circle"></i>
                                <span>Approve Cuti</span></a>
                        </li>
                    @endif
                    <li><a class="nav-link" href="{{ route('additional-leave-requests.history') }}"><i
                                class="fas fa-calendar-check"></i>
                            <span>Cuti Tambahan</span></a>
                    </li>
                    <li><a class="nav-link" href="{{ route('wbbm-data') }}"><i class="fas fa-chart-line"></i>
                            <span>WBK-WBBM</span></a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
