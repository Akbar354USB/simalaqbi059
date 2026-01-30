<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#">SIMALAQBI 059</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="#">059</a>
        </div>
        <ul class="sidebar-menu">
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
        </ul>
        <hr>
    </aside>
</div>
