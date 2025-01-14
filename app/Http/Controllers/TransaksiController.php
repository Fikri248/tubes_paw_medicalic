<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert; // Tambahkan ini untuk Sweet Alert

class TransaksiController extends Controller
{
    public function getData(Request $request)
    {
        $orders = Order::with('transaksi.obat')->whereHas('transaksi');

        if ($request->ajax()) {
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('nama_obat', function ($order) {
                    // Kelompokkan obat berdasarkan nama dan ambil nama uniknya
                    $uniqueObatNames = $order->transaksi->groupBy('obat.nama')->keys();

                    // Gabungkan nama obat dengan koma
                    return $uniqueObatNames->implode(', ');
                })
                ->filterColumn('nama_obat', function ($query, $keyword) {
                    // Tambahkan logika pencarian berdasarkan kata kunci
                    $query->whereHas('transaksi.obat', function ($q) use ($keyword) {
                        $q->where('nama', 'like', "%$keyword%");
                    });
                })
                ->addColumn('jumlah_per_obat', function ($order) {
                    // Kelompokkan obat berdasarkan nama dan jumlahkan
                    $groupedObat = $order->transaksi->groupBy('obat.nama')->map->sum('jumlah');

                    // Tampilkan jumlah total per obat
                    return $groupedObat->implode(', ');
                })
                ->addColumn('total_jumlah_obat', function ($order) {
                    return $order->transaksi->sum('jumlah');
                })
                ->editColumn('total_harga', function ($order) {
                    return 'Rp ' . number_format($order->total_harga, 2, ',', '.');
                })
                ->editColumn('created_at', function ($order) {
                    return \Carbon\Carbon::parse($order->created_at)->addHours(7)->format('Y-m-d\TH:i:s'); // Format ISO 8601
                })
                ->addColumn('actions', function ($order) {
                    $namaObat = $order->transaksi->pluck('obat.nama')->unique()->implode(', ');

                    return '
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

        // Gabungkan jumlah untuk obat yang sama
        $obatJumlah = [];
        foreach ($request->obat_id as $index => $obatId) {
            if (!isset($obatJumlah[$obatId])) {
                $obatJumlah[$obatId] = 0;
            }
            $obatJumlah[$obatId] += $request->jumlah[$index];
        }

        // Validasi stok sisa
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

        // Hitung subtotal
        $subtotal = 0;
        foreach ($obatJumlah as $obatId => $jumlah) {
            $harga = Obat::findOrFail($obatId)->harga;
            $subtotal += $jumlah * $harga;
        }

        // Hitung PPN (12% dari subtotal)
        $ppn = $subtotal * 0.12;

        // Hitung total harga dengan PPN
        $totalHarga = $subtotal + $ppn;

        // Simpan data order
        $order = Order::create([
            'total_harga' => $totalHarga,
        ]);

        // Simpan data transaksi dan kurangi stok sisa obat
        foreach ($obatJumlah as $obatId => $jumlah) {
            $obat = Obat::findOrFail($obatId);

            // Kurangi stok sisa obat
            $obat->stok_sisa -= $jumlah;
            $obat->save();

            // Simpan transaksi
            Transaksi::create([
                'order_id' => $order->id,
                'obat_id' => $obatId,
                'jumlah' => $jumlah,
                'total_harga' => $jumlah * $obat->harga,
            ]);
        }

        // Sweet Alert untuk notifikasi sukses
        return redirect()->route('transaksi')->with('sweet_alert', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Transaksi berhasil ditambahkan.',
        ]);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        Transaksi::where('order_id', $order->id)->delete();
        $order->delete();

        // Tambahkan Sweet Alert untuk notifikasi sukses
        Alert::success('Berhasil!', 'Order dan semua transaksinya berhasil dihapus!');
        return redirect()->route('transaksi');
    }
}
