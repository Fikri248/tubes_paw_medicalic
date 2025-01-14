@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Data Transaksi</h1>
            <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
                <i class="bi-plus-circle me-2"></i> New Order
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
                    <th>Actions</th>
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
            columns: [
                {
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "nama_obat",
                    name: "nama_obat",
                    orderable: false,
                    searchable: true,

                },
                {
                    data: "jumlah_per_obat",
                    name: "jumlah_per_obat",
                    orderable: false,
                    searchable: false,
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
                    searchable: false
                },
                {
                    data: "total_harga",
                    name: "total_harga",
                    render: $.fn.dataTable.render.number('.', ',', 2, 'Rp ')
                },
                {
                    data: "created_at",
                    name: "created_at",
                    render: function(data) {
    if (data) {
        // Pastikan format sesuai dengan yang dikirim dari backend
        return moment(data, 'YYYY-MM-DDTHH:mm:ss').utcOffset(7).format('D/M/YYYY, HH.mm');
    }
    return '';
}

                },
                {
                    data: "actions",
                    name: "actions",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Sweet Alert for delete confirmation
        $("#transaksiTable").on("click", ".btn-delete", function(e) {
            e.preventDefault();
            const form = $(this).closest("form");
            const name = $(this).data("name");

            Swal.fire({
                title: `Apakah Anda yakin ingin menghapus ${name}?`,
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
