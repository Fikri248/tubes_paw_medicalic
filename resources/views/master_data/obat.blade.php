@extends('layouts.app')

@section('title', 'Data Obat')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Daftar Obat</h1>
            <a href="{{ route('master-data.obat.create') }}" class="btn btn-primary">
                <i class="bi-plus-circle me-2"></i> Tambah Obat Baru
            </a>
        </div>

        <table class="table table-bordered table-hover table-striped" id="obatTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Obat</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Stok Awal</th>
                    <th>Stok Sisa</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
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
            $("#obatTable").DataTable({
                serverSide: true,
                processing: true,
                ajax: "{{ route('master-data.obat.data') }}",
                dom: 't<"d-flex justify-content-between mt-3"i<"pagination-wrapper"p>>',
                language: {
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    infoEmpty: "Menampilkan 0 hingga 0 dari 0 entri",
                    infoFiltered: "(difilter dari _MAX_ total entri)",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    loadingRecords: "Memuat...",
                    processing: "Memproses...",
                    search: "Cari:",
                    emptyTable: "Data obat belum tersedia",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    },
                    {
                        data: "nama",
                        name: "nama",
                        className: "text-center"
                    },
                    {
                        data: "kategori",
                        name: "kategori",
                        className: "text-center"
                    },
                    {
                        data: "jenis",
                        name: "jenis",
                        className: "text-center"
                    },
                    {
                        data: "stok_awal",
                        name: "stok_awal",
                        className: "text-center"
                    },
                    {
                        data: "stok_sisa",
                        name: "stok_sisa",
                        className: "text-center"
                    },
                    {
                        data: "harga",
                        name: "harga",
                        className: "text-center",
                        render: function(data) {
                            const formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(data);
                            return formattedPrice.replace('IDR', 'Rp ');
                        }
                    },
                    {
                        data: "deskripsi",
                        name: "deskripsi",
                        className: "text-center",
                        render: function(data) {
                            return `<div style="text-align: start;">${data}</div>`;
                        }
                    },
                    {
                        data: "actions",
                        name: "actions",
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    }
                ],
                columnDefs: [{
                        width: "6%",
                        targets: 4
                    },
                    {
                        width: "6%",
                        targets: 5
                    }
                ],
                autoWidth: false,
                pageLength: 10,
                lengthChange: true,
                initComplete: function() {
                    const wrapper = $('#obatTable_wrapper');
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
                        <input type="text" class="form-control" placeholder="Cari obat...">
                    </div>
                </div>
            </div>
        `);

                    $('#customLengthSelect').on('change', function() {
                        $('#obatTable').DataTable().page.len($(this).val()).draw();
                    });

                    $('#entries input[type="text"]').on('keyup', function() {
                        const value = $(this).val();
                        $('#obatTable').DataTable().search(value).draw();
                    });
                }
            });

            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                const name = $(this).data('name');

                Swal.fire({
                    title: `Apakah Anda yakin ingin menghapus obat ${name}?`,
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
