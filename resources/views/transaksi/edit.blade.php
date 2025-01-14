@extends('layouts.app')

@section('title', 'Tambah Obat')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Edit Transaksi</h1>
            <a href="{{ route('transaksi.obat.index') }}" class="btn btn-secondary">
                <i class="bi-arrow-left-circle me-2"></i> Kembali ke Daftar Transaksi
            </a>
        </div>

        <form action="{{ route('transaksi.obat.update', $transaksi->id) }}" method="POST" id="form-transaksi">
            @method('PUT')
            @csrf
            <input type="hidden" name="obat_id" value="{{ $transaksi->obat_id }}">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Obat</label>
                <div class="dropdown-container">
                    <select name="nama" id="obat"
                        class="form form-control dropdown-trigger  @error('nama') is-invalid @enderror">
                        <option value="" disabled selected>Pilih Obat</option>
                        @foreach ($obat as $medicine)
                            <option value="{{ $medicine->id }}"
                                {{ old('nama', $medicine->id) == $transaksi->obat_id ? 'selected' : '' }}
                                {{ $transaksi->obat_id !== $medicine->id ? 'disabled' : '' }}>
                                {{ $medicine->nama }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </div>

                @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis Obat</label>
                <input type="text" name="jenis" id="jenis"
                    class="form-control  @error('jenis') is-invalid @enderror" required
                    placeholder="Pilih obat terlebih dahulu" value="{{ $transaksi->jenis }}">
                @error('jenis')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>


            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" class="form-control" id="stok" name="stok" readonly
                    style="cursor: not-allowed;" placeholder="Pilih obat terlebih dahulu"
                    data-original-stok="{{ $transaksi->obat->stok }}">
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah"
                    name="jumlah" required placeholder="Pilih obat terlebih dahulu" min="1"
                    data-original-jumlah="{{ $transaksi->jumlah }}">
                @error('jumlah')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga Subtotal</label>
                <input type="text" class="form-control  @error('harga') is-invalid @enderror" id="harga"
                    name="harga" required placeholder="Pilih obat terlebih dahulu"
                    data-original-harga="{{ $transaksi->obat->harga }}">
                @error('harga')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        async function getSelected(obat) {
            try {
                const res = await axios.get(`/transaksi/${obat}/selected`);
                console.log(res);
                return res.data;
            } catch (err) {
                console.error("Error fetching jenis obat:", err);
                throw new Error(err);
            }
        }

        $(document).ready(function() {
            $('#obat').on('change', async function() {
                const obat = $(this).val();
                const data = await getSelected(obat);

                $('#harga').prop('disabled', false);
                $('#jumlah').prop('disabled', false);

                $('#jenis').val(data.jenis);
                $('#stok').val(data.stok);
                $('#stok').data('original-stok', data.stok); // Simpan stok asli
                $('#harga').data('original-harga', data.harga); // Simpan harga asli
                $('#harga').val(data.harga);
                $('#jumlah').val(1);
            });


            $('#stok').val($('#stok').data('original-stok'));
            $('#jumlah').val($('#jumlah').data('original-jumlah'));
            $('#harga').val($('#harga').data('original-harga') * $('#jumlah').val());

            $('#jumlah').on('input', function() {
                let jumlah = $(this).val();
                let harga = parseFloat($('#harga').data('original-harga')) || 0;
                let stok = parseInt($('#stok').data('original-stok')) || 0;

                if (jumlah === '' || isNaN(jumlah)) {
                    $('#stok').val(stok);
                    $('#harga').val(harga);
                    return;
                }

                jumlah = parseInt(jumlah);

                if (jumlah < 1) {
                    $(this).val(1);
                    jumlah = 1;
                }

                if (jumlah > stok) {
                    alert('Jumlah melebihi stok tersedia!');
                    $(this).val(stok);
                    jumlah = stok;
                }

                const sisaStok = stok - jumlah;
                const totalHarga = harga * jumlah;

                $('#stok').val(sisaStok);
                $('#harga').val(totalHarga);
            });
        });
    </script>
@endsection
