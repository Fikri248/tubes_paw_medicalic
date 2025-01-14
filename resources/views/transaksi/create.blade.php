x@extends('layouts.app')

@section('title', 'Tambah Obat')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Tambah Obat Baru</h1>
            <a href="{{ route('transaksi.obat.index') }}" class="btn btn-secondary">
                <i class="bi-arrow-left-circle me-2"></i> Kembali ke Daftar Transaksi
            </a>
        </div>

        <form action="{{ route('transaksi.obat.store') }}" method="POST" id="form-transaksi">
            @csrf
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Obat</label>
                <div class="dropdown-container">
                    <select name="nama" id="obat"
                        class="form form-control dropdown-trigger  @error('nama') is-invalid @enderror">
                        <option value="" disabled selected>Pilih Obat</option>
                        @foreach ($obat as $medicine)
                            <option value="{{ $medicine->id }}">{{ $medicine->nama }}</option>
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
                    class="form-control  @error('jenis') is-invalid @enderror" readonly required
                    style="cursor: not-allowed;" placeholder="Pilih obat terlebih dahulu">

                @error('jenis')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" class="form-control" id="stok" name="stok" readonly
                    style="cursor: not-allowed;" placeholder="Pilih obat terlebih dahulu" data-original-stok="0">
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah"
                    name="jumlah" required disabled placeholder="Pilih obat terlebih dahulu" min="1">
                @error('jumlah')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga Subtotal</label>
                <input type="text" class="form-control  @error('harga') is-invalid @enderror" id="harga"
                    name="harga" required disabled placeholder="Pilih obat terlebih dahulu" data-original-harga="0">
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
                $('#form-transaksi').append(`<input type="hidden" name="obat_id" value="${data.id}">`);
            });

            $('#jumlah').on('input', function() {
                let jumlah = $(this).val();
                let harga = parseFloat($('#harga').data('original-harga')) || 0;
                let stok = parseInt($('#stok').data('original-stok')) || 0;

                if (jumlah === '' || isNaN(jumlah)) {
                    $('#stok').val(stok);
                    $('#harga').val(harga);
                    console.log(jumlah)
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
