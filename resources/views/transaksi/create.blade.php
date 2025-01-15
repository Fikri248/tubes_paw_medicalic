@extends('layouts.app')

@section('title', 'New Order')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-lg mb-4">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-shopping-cart me-2 text-primary"></i>Menu Transaksi Baru
                    </h5>
                    <a href="{{ route('transaksi') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-lg mb-4">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="fas fa-list me-2"></i>Pesan Obat
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
                                            <th class="py-3">Satuan</th>
                                            <th class="py-3" style="width: 80px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="order-items">
                                        <tr>
                                            <td class="align-middle text-center">
                                                <div class="dropdown-container">
                                                    <select class="form-select product-select dropdown-with-icon"
                                                        name="obat_id[]" required>
                                                        <option value="" disabled selected>Pilih Obat</option>
                                                        @foreach ($obats as $obat)
                                                            <option value="{{ $obat->id }}"
                                                                data-harga="{{ $obat->harga }}"
                                                                data-stok="{{ $obat->stok_sisa }}">
                                                                {{ $obat->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fas fa-chevron-down dropdown-icon"></i>
                                                </div>
                                            </td>
                                            <td class="price align-middle text-center">Rp 0</td>
                                            <td class="align-middle text-center">
                                                <input type="number" name="jumlah[]"
                                                    class="form-control quantity text-center" value="1" min="1"
                                                    required>
                                            </td>
                                            <td class="subtotal align-middle text-center">Rp 0</td>
                                            <td class="align-middle text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
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
                                <span class="fw-bold" id="subtotal-amount">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">PPN (12%)</span>
                                <span class="fw-bold" id="ppn-amount">Rp 0</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="h6 mb-0">Total</span>
                                <span class="h6 mb-0 text-primary" id="total-amount">Rp 0</span>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-check me-2"></i>Simpan Transaksi
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
    <script type="module">
        $(document).ready(function() {
            // menghitung stok terpakai
            function calculateRemainingStock(ignoreRow = null) {
                const stokTerpakai = {};
                $('#order-items tr').each(function() {
                    if (this === ignoreRow) return;
                    const obatId = $(this).find('select[name="obat_id[]"]').val();
                    const jumlah = parseInt($(this).find('.quantity').val() || 0);
                    if (obatId) {
                        stokTerpakai[obatId] = (stokTerpakai[obatId] || 0) + jumlah;
                    }
                });
                return stokTerpakai;
            }

            // memperbarui total harga
            function updateTotal() {
                let subtotal = 0;
                $('#order-items .subtotal').each(function() {
                    let amount = $(this).text().replace(/[^\d]/g, '');
                    subtotal += parseInt(amount || 0);
                });

                $('#subtotal-amount').text(`Rp ${subtotal.toLocaleString('id-ID')}`);
                let ppn = Math.round(subtotal * 0.12);
                $('#ppn-amount').text(`Rp ${ppn.toLocaleString('id-ID')}`);
                let total = subtotal + ppn;
                $('#total-amount').text(`Rp ${total.toLocaleString('id-ID')}`);
            }

            $('.add-product-btn').on('click', function() {
                let newRow = `<tr>
    <td class="align-middle text-center">
        <div class="dropdown-container">
            <select class="form-select product-select dropdown-with-icon" name="obat_id[]" required>
            <option value="" disabled selected>Pilih Obat</option>
            @foreach ($obats as $obat)
                <option value="{{ $obat->id }}"
                        data-harga="{{ $obat->harga }}"
                        data-stok="{{ $obat->stok_sisa }}">
                    {{ $obat->nama }}
                </option>
            @endforeach
        </select>
        <i class="fas fa-chevron-down dropdown-icon"></i>
                            </div>
    </td>
    <td class="price align-middle text-center">Rp 0</td>
    <td class="align-middle text-center">
        <input type="number" name="jumlah[]"
               class="form-control quantity text-center" value="1" min="1" required>
    </td>
    <td class="subtotal align-middle text-center">Rp 0</td>
    <td class="align-middle text-center">
        <button type="button" class="btn btn-danger btn-sm remove-btn">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>`;
                $('#order-items').append(newRow);
            });

            $(document).on('click', '.remove-btn', function() {
                $(this).closest('tr').remove();
                updateTotal();
            });

            // cek stok saat dropdown berubah
            $(document).on('change', 'select[name="obat_id[]"]', function() {
                const row = $(this).closest('tr');
                const obatId = $(this).val();
                const stok = parseInt($(this).find('option:selected').data('stok') || 0);
                const stokTerpakai = calculateRemainingStock(row[
                    0]);

                const currentQuantity = parseInt(row.find('.quantity').val() || 0);
                const totalTerpakai = (stokTerpakai[obatId] || 0) + currentQuantity;

                if (totalTerpakai > stok) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Stok Tidak Cukup',
                        text: 'Anda sudah memesan semua stok yang tersedia untuk salah satu obat. Tidak bisa menambahkan produk lagi.',
                    });

                    $(this).val('');
                    row.find('.price').text('Rp 0');
                    row.find('.quantity').val(1);
                    row.find('.subtotal').text('Rp 0');
                    updateTotal();
                } else {
                    const price = parseInt($(this).find('option:selected').data('harga') || 0);
                    row.find('.price').text(`Rp ${price.toLocaleString('id-ID')}`);
                    const subtotal = currentQuantity * price;
                    row.find('.subtotal').text(`Rp ${subtotal.toLocaleString('id-ID')}`);
                    updateTotal();
                }
            });

            $(document).on('change', '.quantity', function() {
                const row = $(this).closest('tr');
                const obatId = row.find('select[name="obat_id[]"]').val();
                const stok = parseInt(row.find('select[name="obat_id[]"] option:selected').data('stok') ||
                    0);
                const quantity = parseInt($(this).val() || 0);
                const stokTerpakai = calculateRemainingStock(row[0]);

                if ((stokTerpakai[obatId] || 0) + quantity > stok) {
                    const maxQuantity = stok - (stokTerpakai[obatId] || 0);

                    Swal.fire({
                        icon: 'error',
                        title: 'Stok Tidak Cukup',
                        text: `Anda hanya bisa memesan sisa stok sebanyak ${maxQuantity}.`,
                    });

                    $(this).val(maxQuantity);
                }

                const adjustedQuantity = parseInt($(this).val() || 0);
                const price = parseInt(row.find('select[name="obat_id[]"] option:selected').data('harga') ||
                    0);
                const subtotal = adjustedQuantity * price;
                row.find('.subtotal').text(`Rp ${subtotal.toLocaleString('id-ID')}`);
                updateTotal();
            });

            $('form').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi Pesanan',
                    text: "Apakah Anda yakin ingin menyimpan transaksi ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endpush
