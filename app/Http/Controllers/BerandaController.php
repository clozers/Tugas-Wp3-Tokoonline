<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Carbon;

use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function berandaBackend()
    {
        $newCustomer = Customer::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $ttlpesananproses = Order::where('status', 'paid')->count();
        $ttlpesananselesai = Order::where('status', 'selesai')->count();
        $ttlpesanankirim = Order::where('status', 'kirim')->count();
        $totalcus = Customer::count();

        // Tambahkan: jumlah order per bulan
        $ordersPerMonth = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $dataChart = [];
        foreach ($ordersPerMonth as $row) {
            $dataChart[] = [$row->month, $row->total]; // format: [1, 15]
        }

        return view('backend.v_beranda.index', [
            'judul' => 'Beranda',
            'sub' => 'Halaman Beranda',
            'totalcus' => $totalcus,
            'ttlpesananproses' => $ttlpesananproses,
            'ttlpesananselesai' => $ttlpesananselesai,
            'ttlpesanankirim' => $ttlpesanankirim,
            'newCustomer' => $newCustomer,
            'dataChart' => json_encode($dataChart), 
        ]);
    }

    public function index()
    {


        $produk = Produk::where('status', 1)->orderBy('updated_at', 'desc')->paginate(6);
        $topRated = Produk::find(1); // contoh ambil produk id = 1
        return view('v_beranda.index', [
            'judul' => 'Halan Beranda',
            'produk' => $produk,
            'topRated' => $topRated,
        ]);
    }
}
