@extends('layouts.app') <!-- Pastikan ini merujuk ke layout utama Anda -->

@section('title', 'Data Obat')

@section('content')
    <div class="container p-3 rounded-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Daftar Obat</h3>

            <div class="d-flex gap-2">
                <a href="{{ route('master-data.obat.pdf') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-print me-2"></i> Cetak PDF
                </a>
                <a href="{{ route('master-data.obat.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi-plus-circle me-2"></i> Tambah Obat Baru
                </a>
            </div>
        </div>

        <table id="obatTable" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Obat</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($obats as $obat)
                    <tr id="row-{{ $obat->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $obat->nama }}</td>
                        <td>{{ $obat->category->name }}</td> <!-- Menampilkan nama kategori yang terkait -->
                        <td>{{ $obat->jenis }}</td>
                        <td>{{ $obat->stok }}</td>
                        <td>Rp {{ number_format($obat->harga, 2, ',', '.') }}</td>
                        <td>{{ $obat->deskripsi }}</td>
                        <td>
                            <div class="d-flex gap-1 align-items-center flex-wrap">
                                <!-- Tombol Edit -->
                                <a href="{{ route('master-data.obat.edit', $obat->id) }}"
                                    class="btn btn-warning btn-sm d-flex gap-1 align-items-center">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <!-- Form untuk Hapus -->
                                <form action="{{ route('master-data.obat.destroy', $obat->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="btn btn-danger btn-sm d-flex gap-1 align-items-center destroy-obat-btn"
                                        data-id="{{ $obat->id }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
@endsection


@section('scripts')
    {{-- SweetAlert2 handle session success --}}
    @if (session('success'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{ session('success') }}',
                timer: 2500
            });
        </script>
    @endif

    <script>
        $('#obatTable').DataTable({
            dom: 't<"d-flex justify-content-between mt-3"i<"pagination-wrapper"p>>',
            language: {
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                lengthMenu: "Tampilkan _MENU_ entri",
                emptyTable: 'Obat belum tersedia',
                paginate: {
                    previous: "Sebelumnya",
                    next: "Selanjutnya"
                }
            },
            paging: true,
            pageLength: 10,
            lengthChange: true,
            initComplete: function() {

                $('#obatTable_wrapper').prepend(`
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


        document.querySelectorAll('.destroy-obat-btn').forEach(element => {
            element.addEventListener('click', async function(e) {
                e.preventDefault();

                const id = this.getAttribute('data-id');
                const url = `/master-data/obat/${id}`;

                try {

                    const result = await Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data obat ini akan dihapus secara permanen!",
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
