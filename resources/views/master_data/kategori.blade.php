@extends('layouts.app')

@section('title', 'Kategori Obat')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Daftar Kategori Obat</h1>
            <a href="{{ route('master-data.kategori.create') }}" class="btn btn-primary">
                <i class="bi-plus-circle me-2"></i> Tambah Kategori Baru
            </a>
        </div>

        <table class="table table-bordered table-hover table-striped" id="kategoriTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Dibuat Pada</th>
                    <th class="text-start">Jumlah Obat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module">
        $(document).ready(function() {
            const table = $("#kategoriTable").DataTable({
                serverSide: true,
                processing: true,
                ajax: "{{ route('master-data.kategori.data') }}",
                dom: 't<"d-flex justify-content-between mt-3"i<"pagination-wrapper"p>>',
                language: {
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    infoEmpty: "Menampilkan 0 hingga 0 dari 0 entri",
                    infoFiltered: "(difilter dari _MAX_ total entri)",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    loadingRecords: "Memuat...",
                    processing: "Memproses...",
                    search: "Cari:",
                    emptyTable: "Kategori belum tersedia",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                },
                columns: [{
                        data: "id",
                        name: "id",
                        className: "text-center"
                    },
                    {
                        data: "name",
                        name: "name",
                        className: "text-center"
                    },
                    {
                        data: "created_at",
                        name: "created_at",
                        className: "text-center",
                        render: function(data) {
                            if (data) {
                                const date = new Date(data);
                                let tanggal = date.getDate();
                                let bulan = date.getMonth() + 1;
                                let tahun = date.getFullYear();

                                let hours = date.getHours().toString().padStart(2, '0');
                                let minutes = date.getMinutes().toString().padStart(2, '0');
                                let seconds = date.getSeconds().toString().padStart(2, '0');

                                return `${tanggal}/${bulan}/${tahun}, ${hours}.${minutes}.${seconds}`;
                            }
                            return "-";
                        }
                    },
                    {
                        data: "jumlah_obat",
                        name: "jumlah_obat",
                        className: "text-center"
                    },
                    {
                        data: "actions",
                        name: "actions",
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    }
                ],
                paging: true,
                pageLength: 10,
                lengthChange: true,
                initComplete: function() {
                    const wrapper = $('#kategoriTable_wrapper');
                    wrapper.prepend(`
                <div id="entries" class="row mb-3 gap-3 justify-content-between">
                    <div class="col-sm-4">
                        <div class="input-group">
                            <label class="input-group-text" for="customLengthSelect">Tampilkan</label>
                            <select class="form-select" id="customLengthSelect">
                                <option selected disabled>Pilih Entri</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="input-group">
                            <label class="input-group-text" id="customLength">
                                <i class="fas fa-search text-muted"></i>
                            </label>
                            <input type="text" class="form-control" placeholder="Cari kategori...">
                        </div>
                    </div>
                </div>
            `);

                    $('#customLengthSelect').on('change', function() {
                        table.page.len($(this).val()).draw();
                    });

                    $('#entries input[type="text"]').on('keyup', function() {
                        const value = $(this).val();
                        table.search(value).draw();
                    });
                }
            });

            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                let name = $(this).data('name');

                Swal.fire({
                    title: `Apakah Anda yakin ingin menghapus kategori ${name}?`,
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
