<?php
namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\UserModel;
use App\Models\PenjualanModel;
use Illuminate\Support\Facades\DB;
use App\Models\PenjualanDetailModel;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class WelcomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            return redirect('/dashboard'); 
        }
        return view('auth.login'); 
    }
    public function dashboard() {
        $user = Auth::user();

        // Total jumlah pengguna
        $totalUser = UserModel::count();

        // Total stok (jumlah semua stok_jumlah)
        $totalStok = StokModel::sum('stok_jumlah');

        // Total penjualan (dari jumlah total dalam tabel penjualan detail)
        $totalPenjualan = PenjualanDetailModel::sum(DB::raw('harga * jumlah'));

        // Breadcrumb tetap pakai Auth user
        $breadcrumb = (object) [
            'title' => 'Selamat Datang, ' . $user->nama,
            'list' => ['Home', 'Welcome']
        ];

        $activeMenu = 'dashboard';

        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'totalUser' => $totalUser,
            'totalStok' => $totalStok,
            'totalPenjualan' => $totalPenjualan,
        ]);
    }
    public function list()
    {
        $penjualan = PenjualanModel::with(['user', 'detailPenjualan'])
            ->select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal');

        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('user_nama', function ($penjualan) {
                return $penjualan->user->nama ?? '-';
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp ' . number_format($penjualan->detailPenjualan->sum(function ($detail) {
                    return $detail->harga * $detail->jumlah;
                }), 0, ',', '.');
            })
            ->addColumn('aksi', function ($penjualan) {
                $btn  = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}