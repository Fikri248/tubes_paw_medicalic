@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Data Transaksi</h1>
            <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
                <i class="bi-plus-circle me-2"></i> Tambah Transaksi Baru
            </a>
        </div>
        <table class="table table-bordered table-hover table-striped" id="transaksiTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Obat</th>
                    <th>Jumlah Obat</th>
                    <th>Total Jumlah Obat</th>
                    <th>Total Harga</th>
                    <th>Waktu Pembelian</th>
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
            const table = $("#transaksiTable").DataTable({
                serverSide: true,
                processing: true,
                ajax: "{{ route('transaksi.getData') }}",
                dom: 't<"d-flex justify-content-between mt-3"i<"pagination-wrapper"p>>',
                language: {
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    infoEmpty: "Menampilkan 0 hingga 0 dari 0 entri",
                    infoFiltered: "(difilter dari _MAX_ total entri)",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    loadingRecords: "Memuat...",
                    processing: "Memproses...",
                    search: "Cari:",
                    emptyTable: "Data transaksi belum tersedia",
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
                        className: "text-center",
                    },
                    {
                        data: "nama_obat",
                        name: "nama_obat",
                        orderable: false,
                        searchable: true,
                        className: "text-center",
                    },
                    {
                        data: "jumlah_per_obat",
                        name: "jumlah_per_obat",
                        orderable: false,
                        searchable: false,
                        className: "text-center",
                        render: function(data) {
                            if (Array.isArray(data)) {
                                return data.join('<br>');
                            }
                            return data;
                        }
                    },
                    {
                        data: "total_jumlah_obat",
                        name: "total_jumlah_obat",
                        orderable: false,
                        searchable: false,
                        className: "text-center",
                    },
                    {
                        data: "total_harga",
                        name: "total_harga",
                        className: "text-center",
                        render: function(data) {
                            if (data) {
                                const numericValue = parseFloat(
                                    data.replace("Rp", "").replace(".", "").replace(",", ".")
                                    .trim()
                                );

                                if (!isNaN(numericValue)) {
                                    return `Rp ${numericValue.toLocaleString('id-ID', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            })}`;
                                }
                            }
                            return 'Rp 0';
                        }
                    },
                    {
                        data: "created_at",
                        name: "created_at",
                        className: "text-center",
                        render: function(data) {
                            if (data) {
                                return moment(data, 'YYYY-MM-DDTHH:mm:ss').utcOffset(7).format(
                                    'D/M/YYYY, HH.mm');
                            }
                            return '';
                        }
                    },
                    {
                        data: "actions",
                        name: "actions",
                        orderable: false,
                        searchable: false,
                        className: "text-center",
                    }
                ],
                pageLength: 10,
                lengthChange: true,
                initComplete: function() {
                    const wrapper = $('#transaksiTable_wrapper');
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
                            <input type="text" class="form-control" placeholder="Cari transaksi...">
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

            $("#transaksiTable").on("click", ".btn-delete", function(e) {
                e.preventDefault();
                const form = $(this).closest("form");
                const name = $(this).data("name");

                Swal.fire({
                    title: `Apakah Anda yakin ingin menghapus transaksi obat ${name}?`,
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
