<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- import style from external public/css/styles --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container-fluid">
        <div class="row items-stretch">
            <!-- Sidebar -->
            <aside class="position-relative p-0" id="sidebar" style="width: 280px;">
                <div class="d-flex flex-column flex-shrink-0 px-4 py-3 bg-light h-100 position-fixed top-0 left-0"
                    style="width: 280px;">
                    <a href="/"
                        class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none border-bottom">
                        <h4 class="text-md fw-bold">APOTEK MEDICALIC</h4>
                    </a>

                    <ul class="nav nav-pills flex-column mb-auto gap-1">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <button
                                class="nav-link {{ request()->routeIs('master-data.*') ? 'active' : '' }} d-flex justify-content-between align-items-center w-100"
                                onclick="toggleSubmenu(event)">
                                <div>
                                    <i class="fas fa-database me-2"></i> Master Data
                                </div>

                                <i class="fas fa-chevron-down"></i>
                            </button>

                            <ul class="submenu {{ request()->routeIs('master-data.kategori') || request()->routeIs('master-data.obat') ? 'show' : '' }}"
                                id="masterDataSubmenu">
                                <li class="nav-item mt-1">
                                    <a class="nav-link {{ request()->routeIs('master-data.kategori') ? 'sub-active' : '' }}"
                                        href="{{ route('master-data.kategori') }}">
                                        <i class="fas fa-circle-dot me-2"></i>
                                        Data Kategori
                                    </a>
                                </li>

                                <li class="nav-item mt-1">
                                    <a class="nav-link {{ request()->routeIs('master-data.obat') ? 'sub-active' : '' }}"
                                        href="{{ route('master-data.obat') }}">
                                        <i class="fas fa-circle-dot me-2"></i>
                                        Data Obat
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('transaksi.obat.index') ? 'active' : '' }}"
                                href="{{ route('transaksi.obat.index') }}">
                                <i class="fas fa-exchange-alt me-2"></i> Transaksi
                            </a>
                        </li>

                        {{-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('laporan') }}">
                                <i class="fas fa-file-alt me-2"></i> Laporan
                            </a>
                        </li> --}}
                    </ul>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="col p-0">
                <nav class="navbar navbar-expand-lg bg-body-tertiary mb-1">
                    <div class="container-fluid d-flex justify-items-between">
                        <button class="btn" type="button" onclick="toggleSidebar()">
                            <i class="fas fa-bars me-2"></i>
                        </button>

                        <img src="https://avatar.iran.liara.run/public" alt="User"
                            style="height: 40px; object-fit: cover;">
                    </div>
                </nav>

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and its dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSubmenu(event) {
            event.preventDefault();
            const submenu = document.getElementById('masterDataSubmenu');
            submenu.classList.toggle('show');

            const icon = event.currentTarget.querySelector('.fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const dropdowns = document?.querySelectorAll('.dropdown-container');

            dropdowns.forEach(dropdown => {
                const select = dropdown?.querySelector('.dropdown-trigger');
                const icon = dropdown?.querySelector('.dropdown-icon');

                select?.addEventListener('click', () => {
                    icon.style.transform = icon.style.transform ===
                        'translateY(-50%) rotate(180deg)' ?
                        'translateY(-50%) rotate(0deg)' :
                        'translateY(-50%) rotate(180deg)';
                });
            });
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('d-none');
        }
    </script>

    {{-- datatable cdn --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    {{-- sweetAlert2 cdn --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- axios cdn to handle ajax --}}
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    {{-- section('scripts') --}}
    @yield('scripts')
</body>

</html>
