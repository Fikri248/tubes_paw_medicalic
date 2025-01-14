@extends('layouts.app')

@section('title', 'New Order')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>New Order</h1>
            <a href="{{ route('transaksi') }}" class="btn btn-secondary">
                <i class="bi-arrow-left-circle me-2"></i> Back to Transactions
            </a>
        </div>

        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf

            <!-- Section: Order Items -->
            <div class="form-section">
                <h3>Order Items</h3>
                <button type="button" class="add-product-btn mb-3 btn btn-success">Add Product</button>

                <table class="table table-bordered products-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="order-items">
                        <tr>
                            <td>
                                <div class="dropdown-container">
                                    <select class="form-control dropdown-with-icon" name="obat_id[]" required>
                                        <option value="" disabled selected>Select Product</option>
                                        @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}" data-harga="{{ $obat->harga }}" data-stok="{{ $obat->stok_sisa }}">{{ $obat->nama }}</option>


                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down dropdown-icon"></i>
                                </div>
                            </td>
                            <td class="price">Rp 0</td>
                            <td>
                                <input type="number" name="jumlah[]" class="form-control quantity" value="1"
                                    min="1" required>
                            </td>
                            <td class="subtotal">Rp 0</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-btn">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <!-- Section: Total -->
            <div class="form-section" style="margin-top: 20px;">
                <h3>Total</h3>
                <table class="table total-table">
                    <tbody>
                        <tr class="no-border">
                            <td><strong>Subtotal</strong></td>
                            <td class="text-end" id="subtotal-amount">Rp 0</td>
                        </tr>
                        <tr class="no-border">
                            <td><strong>PPN (12%)</strong></td>
                            <td class="text-end" id="ppn-amount">Rp 0</td>
                        </tr>
                        <tr class="no-border">
                            <td><strong>Total</strong></td>
                            <td class="text-end" id="total-amount">Rp 0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary submit-btn">Submit Order</button>
            </div>
        </form>
    </div>

@endsection

    @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module">
    $(document).ready(function() {
        // Fungsi untuk menghitung stok terpakai
        function calculateRemainingStock(ignoreRow = null) {
            const stokTerpakai = {}; // Menyimpan stok yang sudah dipesan
            $('#order-items tr').each(function() {
                if (this === ignoreRow) return; // Abaikan baris yang sedang diubah
                const obatId = $(this).find('select[name="obat_id[]"]').val();
                const jumlah = parseInt($(this).find('.quantity').val() || 0);
                if (obatId) {
                    stokTerpakai[obatId] = (stokTerpakai[obatId] || 0) + jumlah;
                }
            });
            return stokTerpakai;
        }

        // Fungsi untuk memperbarui total harga
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

        // Tambahkan baris produk baru
        $('.add-product-btn').on('click', function() {
            let newRow = `
                <tr>
                    <td>
                        <div class="dropdown-container">
                            <select class="form-control dropdown-with-icon" name="obat_id[]" required>
                                <option value="" disabled selected>Select Product</option>
                                @foreach ($obats as $obat)
                                    <option value="{{ $obat->id }}" data-harga="{{ $obat->harga }}" data-stok="{{ $obat->stok_sisa }}">{{ $obat->nama }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </div>
                    </td>
                    <td class="price">Rp 0</td>
                    <td>
                        <input type="number" name="jumlah[]" class="form-control quantity" value="1" min="1" required>
                    </td>
                    <td class="subtotal">Rp 0</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-btn">Remove</button>
                    </td>
                </tr>`;
            $('#order-items').append(newRow);
        });

        // Hapus baris produk
        $(document).on('click', '.remove-btn', function() {
            $(this).closest('tr').remove();
            updateTotal();
        });

        // Pengecekan stok saat dropdown berubah
        $(document).on('change', 'select[name="obat_id[]"]', function() {
            const row = $(this).closest('tr');
            const obatId = $(this).val(); // ID obat yang dipilih
            const stok = parseInt($(this).find('option:selected').data('stok') || 0); // Stok obat
            const stokTerpakai = calculateRemainingStock(row[0]); // Hitung stok terpakai tanpa baris ini

            // Tambahkan stok dari baris ini (jika ada)
            const currentQuantity = parseInt(row.find('.quantity').val() || 0);
            const totalTerpakai = (stokTerpakai[obatId] || 0) + currentQuantity;

            // Validasi stok
            if (totalTerpakai > stok) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Tidak Cukup',
                    text: 'Anda sudah memesan semua stok yang tersedia untuk salah satu obat. Tidak bisa menambahkan produk lagi.',
                });

                // Reset dropdown ke default
                $(this).val('');
                row.find('.price').text('Rp 0');
                row.find('.quantity').val(1);
                row.find('.subtotal').text('Rp 0');
                updateTotal();
            } else {
                // Update harga dan subtotal
                const price = parseInt($(this).find('option:selected').data('harga') || 0);
                row.find('.price').text(`Rp ${price.toLocaleString('id-ID')}`);
                const subtotal = currentQuantity * price;
                row.find('.subtotal').text(`Rp ${subtotal.toLocaleString('id-ID')}`);
                updateTotal();
            }
        });

        // Validasi stok saat quantity diubah
        $(document).on('change', '.quantity', function() {
            const row = $(this).closest('tr');
            const obatId = row.find('select[name="obat_id[]"]').val();
            const stok = parseInt(row.find('select[name="obat_id[]"] option:selected').data('stok') || 0);
            const quantity = parseInt($(this).val() || 0);
            const stokTerpakai = calculateRemainingStock(row[0]);

            // Validasi jika stok yang dimasukkan melebihi sisa stok
            if ((stokTerpakai[obatId] || 0) + quantity > stok) {
                const maxQuantity = stok - (stokTerpakai[obatId] || 0);

                Swal.fire({
                    icon: 'error',
                    title: 'Stok Tidak Cukup',
                    text: `Anda hanya bisa memesan sisa stok sebanyak ${maxQuantity}.`,
                });

                // Set jumlah ke sisa stok yang tersedia
                $(this).val(maxQuantity);
            }

            // Update subtotal
            const adjustedQuantity = parseInt($(this).val() || 0);
            const price = parseInt(row.find('select[name="obat_id[]"] option:selected').data('harga') || 0);
            const subtotal = adjustedQuantity * price;
            row.find('.subtotal').text(`Rp ${subtotal.toLocaleString('id-ID')}`);
            updateTotal();
        });

        // Sweet Alert untuk konfirmasi form submission
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
                    this.submit(); // Submit the form jika konfirmasi
                }
            });
        });
    });
</script>
@endpush

