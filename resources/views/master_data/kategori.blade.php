@extends('layouts.app')

@section('title', 'Kategori Obat')
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Daftar Kategori Obat</h1>
            <a href="{{ route('master-data.kategori.create') }}" class="btn btn-primary">
                <i class="bi-plus-circle me-2"></i> Buat Kategori Baru
            </a>
        </div>

        <table id="categoriesTable" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Dibuat Pada</th>
                    <th>Diubah Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr id="row-{{ $category->id }}">
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $category->updated_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <form action="{{ route('master-data.kategori.destroy', $category->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="button" class="btn btn-danger btn-sm destroy-category-btn"
                                    data-id="{{ $category->id }}">
                                    <i class="bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
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
        $('#categoriesTable').DataTable({
            dom: 't<"d-flex justify-content-between mt-3"i<"pagination-wrapper"p>>',
            language: {
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                lengthMenu: "Tampilkan _MENU_ entri",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Selanjutnya"
                },
                emptyTable: 'Kategori obat belum tersedia'
            },
            paging: true,
            pageLength: 10,
            lengthChange: true,
            initComplete: function() {

                $('#categoriesTable_wrapper').prepend(`
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
                    $('#categoriesTable').DataTable().page.len($(this).val()).draw();
                });


                $('#entries input[type="text"]').on('keyup', function() {
                    const value = $(this).val();
                    $('#categoriesTable').DataTable().search(value).draw();
                });
            }
        });


        document.querySelectorAll('.destroy-category-btn').forEach(element => {
            element.addEventListener('click', async function(e) {
                e.preventDefault();

                const id = this.getAttribute('data-id');
                const url = `/master-data/kategori/${id}`;

                try {

                    const result = await Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Semua obat yang terkait dengan kategori ini akan dihapus!",
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
                            ).then(() => window.location.reload());

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
