<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/sass/app.scss')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar p-0 hidden" id="sidebar">
                <div class="sidebar-header">
                    <h4 class="typing-text">
                        <span></span>
                    </h4>
                </div>
                <div class="navigation">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link d-flex justify-content-between align-items-center" href="#"
                                onclick="toggleSubmenu(event)">
                                <div>
                                    <i class="fas fa-database"></i> Master Data
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </a>
                            <ul class="submenu" id="masterDataSubmenu">
                                <li><a href="{{ route('master-data.kategori') }}">
                                        <i class="fas fa-circle-dot"></i> Data Kategori</a></li>
                                <li><a href="{{ route('master-data.obat') }}">
                                        <i class="fas fa-circle-dot"></i> Data Obat</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('transaksi') }}">
                                <i class="fas fa-exchange-alt"></i> Transaksi
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('laporan.index') }}">
                                <i class="fas fa-file-alt"></i> Laporan
                            </a>
                        </li>
                    </ul>

                    <div class="hide-menu-btn">
                        <button onclick="toggleSidebar()" class="btn btn-dark w-100">
                            <i class="fas fa-angles-left"></i> Hide Menu
                        </button>
                    </div>
                </div>
            </div>

            <button id="hamburger-btn" class="hamburger-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="col-md-10 main-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

    <script>
        //animasi teks berjalan
        function toggleSubmenu(event) {
            event.preventDefault();
            const submenu = document.getElementById('masterDataSubmenu');
            submenu.classList.toggle('show');

            const icon = event.currentTarget.querySelector('.fa-chevron-down');
            icon.classList.toggle('rotated');
        }
        document.addEventListener('DOMContentLoaded', function() {
            const dynamicText = document.querySelector(".typing-text span");
            const words = ["APOTEK MEDICALIC", "By Kelompok 2", "Admin Fikri", "Admin Fahrezy", "Admin Akhdan"];

            let wordIndex = 0;
            let charIndex = 0;
            let isDeleting = false;

            const typeEffect = () => {
                const currentWord = words[wordIndex];
                const currentChar = currentWord.substring(0, charIndex);
                dynamicText.textContent = currentChar;
                dynamicText.classList.add("stop-blinking");

                if (!isDeleting && charIndex < currentWord.length) {
                    charIndex++;
                    setTimeout(typeEffect, 200);
                } else if (isDeleting && charIndex > 0) {
                    charIndex--;
                    setTimeout(typeEffect, 100);
                } else {
                    isDeleting = !isDeleting;
                    dynamicText.classList.remove("stop-blinking");
                    wordIndex = !isDeleting ? (wordIndex + 1) % words.length : wordIndex;
                    setTimeout(typeEffect, 1200);
                }
            }

            typeEffect();
        });

        // tracking sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const hamburgerBtn = document.getElementById('hamburger-btn');

            sidebar.classList.toggle('hidden');
            mainContent.classList.toggle('expanded');

            const isSidebarHidden = sidebar.classList.contains('hidden');
            localStorage.setItem('sidebarHidden', isSidebarHidden ? 'true' : 'false');

            hamburgerBtn.style.display = isSidebarHidden ? 'block' : (window.innerWidth > 768 ? 'none' : 'block');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const hamburgerBtn = document.getElementById('hamburger-btn');

            const sidebarHidden = localStorage.getItem('sidebarHidden') === 'true';

            if (sidebarHidden) {
                sidebar.classList.add('hidden');
                mainContent.classList.add('expanded');
                hamburgerBtn.style.display = 'block';
            } else {
                sidebar.classList.remove('hidden');
                mainContent.classList.remove('expanded');
                hamburgerBtn.style.display = window.innerWidth > 768 ? 'none' : 'block';
            }

            window.addEventListener('resize', function() {
                if (window.innerWidth <= 768) {
                    hamburgerBtn.style.display = 'block';
                } else {
                    hamburgerBtn.style.display = sidebar.classList.contains('hidden') ? 'block' : 'none';
                }
            });
        });

        //animasi panah
        document.querySelectorAll('.dropdown-with-icon').forEach(select => {
            select.addEventListener('focus', function() {
                const icon = this.nextElementSibling;
                if (icon && icon.classList.contains('dropdown-icon')) {
                    icon.style.transform = 'translateY(-50%) rotate(180deg)';
                }
            });

            select.addEventListener('blur', function() {
                const icon = this.nextElementSibling;
                if (icon && icon.classList.contains('dropdown-icon')) {
                    icon.style.transform = 'translateY(-50%)';
                }
            });
        });
    </script>

    @vite('resources/js/app.js')
    @include('sweetalert::alert')
    @stack('scripts')
</body>

</html>
