<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Transaksi;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        $orders = [
            ['total_harga' => 0],
            ['total_harga' => 0],
            ['total_harga' => 0],
        ];

        foreach ($orders as $data) {
            // Buat order baru
            $order = Order::create($data);

            // Hitung total harga dari transaksi terkait
            $totalHarga = Transaksi::where('order_id', $order->id)->sum('total_harga');

            // Jika tidak ada transaksi, hapus order
            if ($totalHarga == 0) {
                $order->delete();
            } else {
                $order->update(['total_harga' => $totalHarga]);
            }
        }
    }
}
