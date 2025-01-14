@extends('layouts.app')

@section('title', 'Transaksi') <!-- Judul halaman -->
@section('content')
    <div class="container p-3 rounded-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Daftar Transaksi</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('transaksi.obat.pdf') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-print me-2"></i> Cetak PDF
                </a>
                <a href="{{ route('transaksi.obat.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi-plus-circle me-2"></i> Tambah Transaksi
                </a>
            </div>
        </div>

        <table id="transactionsTable" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Obat</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaksi)
                    <tr id="row-{{ $transaksi->id }}">
                        <td>{{ $transaksi->id }}</td>
                        <td>{{ $transaksi->nama }}</td>
                        <td>{{ $transaksi->jenis }}</td>
                        <td>{{ $transaksi->jumlah }}</td>
                        <td>{{ $transaksi->harga }}</td>

                        <td style="max-width: 70px">
                            <div class="d-flex gap-1 align-items-center flex-wrap">
                                <!-- Tombol Edit -->
                                <a href="{{ route('transaksi.obat.edit', $transaksi->id) }}"
                                    class="btn btn-warning btn-sm d-flex gap-1 align-items-center">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                {{-- Tombol Hapus --}}
                                <button type="button" class="btn btn-danger btn-sm destroy-transaksi-btn"
                                    data-id="{{ $transaksi->id }}">
                                    <i class="bi-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-end mt-3">
            <h4 class="fw-bold">
                Total: Rp. {{ number_format($priceTotal, 2, ',', '.') }}
            </h4>
        </div>
    </div>
@endsection

@section('scripts')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
            }).then(() => window.location.reload())
        </script>
    @endif

    <script>
        $('#transactionsTable').DataTable({
            dom: 't<"d-flex justify-content-between mt-3"i<"pagination-wrapper"p>>',
            deferRender: true,
            language: {
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                lengthMenu: "Tampilkan _MENU_ entri",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Selanjutnya"
                },
                emptyTable: 'Transaksi belum tersedia'
            },
            paging: true,
            pageLength: 10,
            lengthChange: true,
            initComplete: function() {
                $('#transactionsTable_wrapper').prepend(`
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
                    $('#transactionsTable').DataTable().page.len($(this).val()).draw();
                });


                $('#entries input[type="text"]').on('keyup', function() {
                    const value = $(this).val();
                    $('#transactionsTable').DataTable().search(value).draw();
                });
            }
        });


        document.querySelectorAll('.destroy-transaksi-btn').forEach(element => {
            element.addEventListener('click', async function(e) {
                e.preventDefault();

                const id = this.getAttribute('data-id');
                const url = `/transaksi/delete/${id}`;

                try {
                    const result = await Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data transaksi ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    });

                    if (result.isConfirmed) {

                        const response = await axios.delete(url, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            }
                        });


                        if (response.data.success) {
                            Swal.fire(
                                'Terhapus!',
                                'Data berhasil dihapus.',
                                'success'
                            );

                            document.getElementById(`row-${id}`).remove();
                        } else {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            );
                        }
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire(
                        'Gagal!',
                        'Terjadi kesalahan pada server.',
                        'error'
                    );
                }
            })
        });
    </script>
@endsection
