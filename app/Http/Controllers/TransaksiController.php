<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class TransaksiController extends Controller
{
    public function getData(Request $request)
{
    $orders = Order::with('transaksi.obat')->whereHas('transaksi');

    if ($request->ajax()) {
        return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('nama_obat', function ($order) {
                $uniqueObatNames = $order->transaksi->groupBy('obat.nama')->keys();

                return $uniqueObatNames->implode(', ');
            })
            ->filterColumn('nama_obat', function ($query, $keyword) {
                $query->whereHas('transaksi.obat', function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%$keyword%");
                });
            })
            ->addColumn('jumlah_per_obat', function ($order) {
                $groupedObat = $order->transaksi->groupBy('obat.nama')->map->sum('jumlah');

                return $groupedObat->implode(', ');
            })
            ->addColumn('total_jumlah_obat', function ($order) {
                return $order->transaksi->sum('jumlah');
            })
            ->editColumn('total_harga', function ($order) {
                return 'Rp ' . number_format($order->total_harga, 2, ',', '.');
            })
            ->editColumn('created_at', function ($order) {
                return \Carbon\Carbon::parse($order->created_at)->addHours(7)->format('Y-m-d\TH:i:s');
            })
            ->addColumn('actions', function ($order) {
                $namaObat = $order->transaksi->pluck('obat.nama')->unique()->implode(', ');

                return '
                    <a href="' . route('transaksi.edit', $order->id) . '" class="btn btn-primary btn-sm me-1">Edit</a>
                    <form action="' . route('transaksi.destroy', $order->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="button" class="btn btn-danger btn-sm btn-delete" data-name="' . e($namaObat) . '">
                            Hapus
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['nama_obat', 'jumlah_per_obat', 'actions'])
            ->make(true);
    }
}


    public function index()
    {
        $orders = Order::with('transaksi.obat')->get();
        return view('transaksi.index', compact('orders'));
    }

    public function create()
    {
        $obats = Obat::all();
        return view('transaksi.create', compact('obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'obat_id' => 'required|array',
            'obat_id.*' => 'exists:obat,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
        ]);

        $obatJumlah = [];
        foreach ($request->obat_id as $index => $obatId) {
            if (!isset($obatJumlah[$obatId])) {
                $obatJumlah[$obatId] = 0;
            }
            $obatJumlah[$obatId] += $request->jumlah[$index];
        }

        foreach ($obatJumlah as $obatId => $jumlah) {
            $obat = Obat::findOrFail($obatId);

            if ($jumlah > $obat->stok_sisa) {
                return redirect()->back()->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Stok Tidak Cukup!',
                    'text' => "Stok untuk {$obat->nama} hanya tersedia {$obat->stok_sisa}.",
                ]);
            }
        }

        $subtotal = 0;
        foreach ($obatJumlah as $obatId => $jumlah) {
            $harga = Obat::findOrFail($obatId)->harga;
            $subtotal += $jumlah * $harga;
        }

        $ppn = $subtotal * 0.12;

        $totalHarga = $subtotal + $ppn;

        $order = Order::create([
            'total_harga' => $totalHarga,
        ]);

        foreach ($obatJumlah as $obatId => $jumlah) {
            $obat = Obat::findOrFail($obatId);

            $obat->stok_sisa -= $jumlah;
            $obat->save();

            Transaksi::create([
                'order_id' => $order->id,
                'obat_id' => $obatId,
                'jumlah' => $jumlah,
                'total_harga' => $jumlah * $obat->harga,
            ]);
        }

        Alert::success('Berhasil', 'Transaksi berhasil ditambahkan.');
        return redirect()->route('transaksi');
    }

    public function edit($id)
{
    $order = Order::with('transaksi.obat')->findOrFail($id);
    $obats = Obat::all();

    $subtotal = $order->transaksi->sum(function ($transaksi) {
        return $transaksi->jumlah * $transaksi->obat->harga;
    });

    $ppn = $subtotal * 0.12;
    $totalHarga = $subtotal + $ppn;

    return view('transaksi.edit', compact('order', 'obats', 'subtotal', 'ppn', 'totalHarga'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'obat_id' => 'required|array',
        'obat_id.*' => 'exists:obat,id',
        'jumlah' => 'required|array',
        'jumlah.*' => 'integer|min:1',
    ]);

    $order = Order::findOrFail($id);

    // Simpan transaksi lama untuk menyesuaikan stok
    $oldTransaksi = $order->transaksi;

    // Hapus semua transaksi lama
    $order->transaksi()->delete();

    $obatJumlah = [];
    foreach ($request->obat_id as $index => $obatId) {
        if (!isset($obatJumlah[$obatId])) {
            $obatJumlah[$obatId] = 0;
        }
        $obatJumlah[$obatId] += $request->jumlah[$index];
    }

    $subtotal = 0;

    // Kembalikan stok sisa obat berdasarkan transaksi lama
    foreach ($oldTransaksi as $transaksi) {
        $obat = Obat::findOrFail($transaksi->obat_id);
        $obat->stok_sisa += $transaksi->jumlah; // Tambahkan kembali jumlah yang digunakan
        $obat->save();
    }

    // Validasi stok dan simpan transaksi baru
    foreach ($obatJumlah as $obatId => $jumlah) {
        $obat = Obat::findOrFail($obatId);

        // Validasi jika stok sisa tidak cukup
        if ($jumlah > $obat->stok_sisa) {
            return redirect()->back()->with('sweet_alert', [
                'type' => 'error',
                'title' => 'Stok Tidak Cukup!',
                'text' => "Stok untuk {$obat->nama} hanya tersedia {$obat->stok_sisa}.",
            ]);
        }

        // Kurangi stok sisa obat
        $obat->stok_sisa -= $jumlah;
        $obat->save();

        // Hitung subtotal
        $subtotal += $jumlah * $obat->harga;

        // Simpan transaksi baru
        Transaksi::create([
            'order_id' => $order->id,
            'obat_id' => $obatId,
            'jumlah' => $jumlah,
            'total_harga' => $jumlah * $obat->harga,
        ]);
    }

    // Hitung total harga dengan PPN
    $ppn = $subtotal * 0.12;
    $totalHarga = $subtotal + $ppn;

    // Update total harga di tabel order
    $order->update(['total_harga' => $totalHarga]);

    Alert::success('Berhasil', 'Transaksi berhasil diperbarui.');
    return redirect()->route('transaksi');
}



    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        Transaksi::where('order_id', $order->id)->delete();
        $order->delete();

        Alert::success('Berhasil!', 'Order dan semua transaksinya berhasil dihapus!');
        return redirect()->route('transaksi');
    }
}
