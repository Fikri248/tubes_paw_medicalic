@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-lg mb-4">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-shopping-cart me-2 text-primary"></i>Edit Transaksi
                    </h5>
                    <a href="{{ route('transaksi') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <form id="updateTransactionForm" action="{{ route('transaksi.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-lg mb-4">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="fas fa-list me-2"></i>Edit Obat
                                </h6>
                                <button type="button" class="add-product-btn btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Tambah Produk
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0 products-table">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th class="py-3">Produk</th>
                                            <th class="py-3">Harga</th>
                                            <th class="py-3" style="width: 120px;">Jumlah</th>
                                            <th class="py-3">Subtotal</th>
                                            <th class="py-3" style="width: 80px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="order-items">
                                        @foreach ($order->transaksi as $transaksi)
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <div class="dropdown-container">
                                                        <select class="form-select product-select dropdown-with-icon"
                                                            name="obat_id[]" required>
                                                            <option value="" disabled>Pilih Obat</option>
                                                            @foreach ($obats as $obat)
                                                                <option value="{{ $obat->id }}"
                                                                    data-harga="{{ $obat->harga }}"
                                                                    data-stok="{{ $obat->stok_sisa }}"
                                                                    {{ $transaksi->obat_id == $obat->id ? 'selected' : '' }}>
                                                                    {{ $obat->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <i class="fas fa-chevron-down dropdown-icon"></i>
                                                    </div>
                                                </td>
                                                <td class="price align-middle text-center">Rp {{ number_format($transaksi->obat->harga, 0, ',', '.') }}</td>
                                                <td class="align-middle text-center">
                                                    <input type="number" name="jumlah[]"
                                                        class="form-control quantity text-center" value="{{ $transaksi->jumlah }}" min="1"
                                                        required>
                                                </td>
                                                <td class="subtotal align-middle text-center">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                                <td class="align-middle text-center">
                                                    <button type="button" class="btn btn-danger btn-sm remove-btn">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-lg">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-calculator me-2"></i>Hitungan Pembelian
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-bold" id="subtotal-amount">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">PPN (12%)</span>
                                <span class="fw-bold" id="ppn-amount">Rp {{ number_format($ppn, 0, ',', '.') }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="h6 mb-0">Total</span>
                                <span class="h6 mb-0 text-primary" id="total-amount">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-check me-2"></i>Update Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('updateTransactionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Perubahan',
                text: "Apakah Anda yakin ingin menyimpan perubahan transaksi?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3498db',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const btn = this.querySelector('button[type="submit"]');
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                    btn.disabled = true;
                    this.submit();
                }
            });
        });
    </script>
@endpush
