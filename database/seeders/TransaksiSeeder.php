<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\Order;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        // Buat order baru
        $order = Order::create([
            'total_harga' => 0
        ]);

        $obats = Obat::all();
        $totalHarga = 0;

        foreach ($obats as $index => $obat) {
            $jumlah = ($index + 1) * 5; // Contoh jumlah
            $subtotal = $obat->harga * $jumlah;

            // Validasi stok sisa cukup sebelum membuat transaksi
            if ($obat->stok_sisa >= $jumlah) {
                // Simpan transaksi untuk obat ini
                Transaksi::create([
                    'order_id' => $order->id,
                    'obat_id' => $obat->id,
                    'jumlah' => $jumlah,
                    'total_harga' => $subtotal,
                ]);

                // Kurangi stok sisa obat
                $obat->stok_sisa -= $jumlah;
                $obat->save(); // Simpan perubahan stok sisa

                // Tambahkan subtotal ke total harga order
                $totalHarga += $subtotal;
            } else {
                echo "Stok obat '{$obat->nama}' tidak cukup untuk transaksi ini.\n";
            }
        }

        // Update total harga di tabel order
        $order->update(['total_harga' => $totalHarga]);
    }
}
