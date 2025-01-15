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
        $order = Order::create([
            'total_harga' => 0
        ]);

        $obats = Obat::take(3)->get();
        $subtotal = 0;

        foreach ($obats as $index => $obat) {
            $jumlah = 5;
            $itemSubtotal = $obat->harga * $jumlah;

            if ($obat->stok_sisa >= $jumlah) {
                Transaksi::create([
                    'order_id' => $order->id,
                    'obat_id' => $obat->id,
                    'jumlah' => $jumlah,
                    'total_harga' => $itemSubtotal,
                ]);

                $obat->stok_sisa -= $jumlah;
                $obat->save();

                $subtotal += $itemSubtotal;
            } else {
                echo "Stok obat '{$obat->nama}' tidak cukup untuk transaksi ini.\n";
            }
        }

        $ppn = $subtotal * 0.12;

        $totalHarga = $subtotal + $ppn;

        $order->update(['total_harga' => $totalHarga]);
    }
}
