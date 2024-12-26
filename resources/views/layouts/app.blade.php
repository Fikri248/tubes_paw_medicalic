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
    <style>
        .sidebar {
            background: #fff;
            min-height: 100vh;
            border-right: 1px solid #eee;
        }

        .nav-link {
            color: #333;
            padding: 0.8rem 1rem;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }

        .nav-link:hover {
            background: #f8f9fa;
        }

        .dropdown-menu {
            border: none;
            margin-left: 2rem;
            background: transparent;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            color: #333;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
        }
        .dropdown-container {
    position: relative;
}

.dropdown-container .dropdown-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    transition: transform 0.3s ease;
}
        .dropdown-container .fa-chevron-down {
    transition: transform 0.3s ease;
}
        .submenu {
            list-style: none;
            padding-left: 2rem;
            display: none;
        }

        .submenu li {
            padding: 0.5rem 0;
        }

        .submenu li a {
            color: #333;
            text-decoration: none;
        }

        .submenu.show {
            display: block;
        }

        .stats-card {
            border-radius: 8px;
            padding: 1rem;
            color: white;
            margin-bottom: 1rem;
        }

        .blue-card {
            background: #007bff;
            /* Warna biru */
        }

        .green-card {
            background: #28a745;
            /* Warna hijau */
        }

        .orange-card {
            background: #ffc107;
            /* Warna oranye */
        }

        .purple-card {
            background: #6f42c1;
            /* Warna ungu */
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h4 style="font-size: 26px;">APOTEK MEDICALIC</h4>
                </div>
                <div class="navigation">
                    <ul class="nav flex-column">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>

                        <li class="nav-item">
                            <a class="nav-link d-flex justify-content-between align-items-center" href="#"
                                onclick="toggleSubmenu(event)">
                                <div>
                                    <i class="fas fa-database me-2"></i> Master Data
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </a>
                            <ul class="submenu" id="masterDataSubmenu">
                                <li><a href="{{ route('master-data.kategori') }}"><i class="fas fa-circle-dot me-2"></i>
                                        Data Kategori</a></li>
                                <li><a href="{{ route('master-data.obat') }}"><i class="fas fa-circle-dot me-2"></i>
                                        Data Obat</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('transaksi') }}">
                                <i class="fas fa-exchange-alt me-2"></i> Transaksi
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('laporan') }}">
                                <i class="fas fa-file-alt me-2"></i> Laporan
                            </a>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                @yield('content') <!-- Placeholder untuk konten setiap halaman -->
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
    const dropdowns = document.querySelectorAll('.dropdown-container');

    dropdowns.forEach(dropdown => {
        const select = dropdown.querySelector('.dropdown-trigger');
        const icon = dropdown.querySelector('.dropdown-icon');

        select.addEventListener('click', () => {
            icon.style.transform = icon.style.transform === 'translateY(-50%) rotate(180deg)'
                ? 'translateY(-50%) rotate(0deg)'
                : 'translateY(-50%) rotate(180deg)';
        });
    });
});

    </script>
</body>

</html>
