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
            $order = Order::create($data);

            $totalHarga = Transaksi::where('order_id', $order->id)->sum('total_harga');

            if ($totalHarga == 0) {
                $order->delete();
            } else {
                $order->update(['total_harga' => $totalHarga]);
            }
        }
    }
}
