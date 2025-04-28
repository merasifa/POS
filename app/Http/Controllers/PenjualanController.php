<?php

namespace App\Http\Controllers;

use Log;
use App\Models\StokModel;
use App\Models\UserModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\PenjualanDetailModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) ['title' => 'Data Penjualan', 'list' => ['Home', 'Penjualan']];
        $page = (object) ['title' => 'Daftar Penjualan'];
        $activeMenu = 'penjualan';

        return view('penjualan.index', compact('breadcrumb', 'page', 'activeMenu'));
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
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) ['title' => 'Tambah Penjualan', 'list' => ['Home', 'Penjualan', 'Tambah']];
        $page = (object) ['title' => 'Form Tambah Penjualan'];
        $activeMenu = 'penjualan';
        $users = UserModel::all();

        return view('penjualan.create', compact('breadcrumb', 'page', 'activeMenu', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:m_user,user_id',
            'pembeli'           => 'required|string|max:50',
            'penjualan_kode'    => 'required|string|max:20|unique:t_penjualan,penjualan_kode',
            'penjualan_tanggal' => 'required|date',
        ]);

        PenjualanModel::create($request->only(['user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal']));

        return redirect('/penjualan')->with('success', 'Data berhasil disimpan');
    }

    public function show($id)
    {
        $penjualan = PenjualanModel::with('user')->findOrFail($id);

        $breadcrumb = (object) ['title' => 'Detail Penjualan', 'list' => ['Home', 'Penjualan', 'Detail']];
        $page = (object) ['title' => 'Detail Data Penjualan'];
        $activeMenu = 'penjualan';

        return view('penjualan.show', compact('breadcrumb', 'page', 'activeMenu', 'penjualan'));
    }

    public function edit($id)
    {
        $penjualan = PenjualanModel::findOrFail($id);
        $breadcrumb = (object) ['title' => 'Edit Penjualan', 'list' => ['Home', 'Penjualan', 'Edit']];
        $page = (object) ['title' => 'Edit Data Penjualan'];
        $activeMenu = 'penjualan';
        $users = UserModel::all();

        return view('penjualan.edit', compact('breadcrumb', 'page', 'activeMenu', 'penjualan', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id'           => 'required|exists:m_user,user_id',
            'pembeli'           => 'required|string|max:50',
            'penjualan_kode'    => 'required|string|max:20|unique:t_penjualan,penjualan_kode,' . $id . ',penjualan_id',
            'penjualan_tanggal' => 'required|date',
        ]);

        PenjualanModel::findOrFail($id)->update($request->only(['user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal']));

        return redirect('/penjualan')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        try {
            PenjualanModel::destroy($id);
            return redirect('/penjualan')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/penjualan')->with('error', 'Data gagal dihapus: ' . $e->getMessage());
        }
    }

    public function create_ajax()
    {
    $users = UserModel::select('user_id', 'nama')->get();

    $barangs = BarangModel::select('m_barang.barang_id', 'm_barang.barang_nama', 'm_barang.harga_jual')
        ->join('t_stok', 't_stok.barang_id', '=', 'm_barang.barang_id')
        ->selectRaw('SUM(t_stok.stok_jumlah) as total_stok')
        ->groupBy('m_barang.barang_id', 'm_barang.barang_nama', 'm_barang.harga_jual')
        ->having('total_stok', '>', 0)
        ->get();

    return view('penjualan.create_ajax', [
        'users' => $users,
        'barangs' => $barangs,
    ]);
}



    public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'pembeli'           => 'required|string|max:50',
            'penjualan_kode'    => 'required|string|max:20|unique:t_penjualan,penjualan_kode',
            'penjualan_tanggal' => 'required|date',
            'barang_id.*'       => 'required|exists:m_barang,barang_id',
            'harga.*'           => 'required|numeric|min:0',
            'jumlah.*'          => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi gagal.',
                'msgField' => $validator->errors(),
            ]);
        }

        DB::beginTransaction();

        try {
            // Simpan data penjualan utama
            $penjualan = PenjualanModel::create([
                'user_id'           => auth()->user()->user_id,
                'pembeli'           => $request->pembeli,
                'penjualan_kode'    => $request->penjualan_kode,
                'penjualan_tanggal' => $request->penjualan_tanggal,
            ]);

            // Loop dan simpan detail penjualan
            foreach ($request->barang_id as $index => $barangId) {
                $barang = BarangModel::findOrFail($barangId);

                $harga  = $request->harga[$index];
                $jumlah = $request->jumlah[$index];

                // Simpan ke detail penjualan
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id'    => $barangId,
                    'harga'        => $harga,
                    'jumlah'       => $jumlah,
                ]);

                // Kurangi stok barang
                $stokTersedia = StokModel::where('barang_id', $barangId)->sum('stok_jumlah');

                if ($stokTersedia < $jumlah) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => false,
                        'message' => "Stok barang '{$barang->barang_nama}' tidak mencukupi.",
                    ]);
                }

                // Kurangi stok secara total
                $stok = StokModel::where('barang_id', $barangId)->orderBy('stok_tanggal')->get();
                $jumlahSisa = $jumlah;

                foreach ($stok as $s) {
                    if ($jumlahSisa <= 0) break;

                    if ($s->stok_jumlah <= $jumlahSisa) {
                        $jumlahSisa -= $s->stok_jumlah;
                        $s->delete(); // hapus stok karena habis
                    } else {
                        $s->stok_jumlah -= $jumlahSisa;
                        $s->save();
                        $jumlahSisa = 0;
                    }
                }

                // Hapus barang jika stoknya sudah 0
                $stokSekarang = StokModel::where('barang_id', $barangId)->sum('stok_jumlah');
                if ($stokSekarang <= 0) {
                    $barang->delete();
                }
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Data penjualan berhasil disimpan.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    return redirect('/');
}

    public function edit_ajax(string $id)
    {
        $penjualan = PenjualanModel::find($id);
        $users = UserModel::all();

        return view('penjualan.edit_ajax', [
            'penjualan' => $penjualan,
            'users'     => $users,
        ]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id'           => 'required|integer|exists:m_user,user_id',
                'pembeli'           => 'required|string|max:50',
                'penjualan_kode'    => 'required|string|max:20|unique:t_penjualan,penjualan_kode,' . $id . ',penjualan_id',
                'penjualan_tanggal' => 'required|date',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $penjualan = PenjualanModel::find($id);
            if ($penjualan) {
                $penjualan->update($request->all());

                return response()->json([
                    'status'  => true,
                    'message' => 'Data penjualan berhasil diupdate.',
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan.',
            ]);
        }

        return redirect('/');
    }

    public function show_ajax(string $id)
    {
        $penjualan = PenjualanModel::with(['user', 'detailPenjualan'])->find($id);
        $penjualan->total_bayar = $penjualan->detailPenjualan->sum(fn($d) => $d->harga * $d->jumlah);

        return view('penjualan.show_ajax', ['penjualan' => $penjualan]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = PenjualanModel::find($id);
            if ($penjualan) {
                $penjualan->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Data penjualan berhasil dihapus.',
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan.',
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $penjualan = PenjualanModel::find($id);

        return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
    }

    public function import()
    {
        return view('penjualan.import');
    }

    public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = ['file_penjualan' => 'required|mimes:xlsx|max:1024'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_penjualan');
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, false, true, true);

        $insert = [];
        if (count($data) > 1) {
            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    $insert[] = [
                        'user_id'           => $value['A'],
                        'pembeli'           => $value['C'],
                        'penjualan_kode'    => $value['B'],
                        'penjualan_tanggal' => $value['D'],
                        'created_at'        => now(),
                    ];
                }
            }

            if (count($insert) > 0) {
                PenjualanModel::insertOrIgnore($insert);
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang valid untuk diimport'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data dalam file'
            ]);
        }
    }

    return redirect('/');
}


    public function export_excel()
    {
        $penjualan = PenjualanModel::select('user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
                ->orderBy('penjualan_tanggal')
                ->with('user')
                ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Pembeli');
        $sheet->setCellValue('D1', 'Tanggal Penjualan');
        $sheet->setCellValue('E1', 'User');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;

        foreach ($penjualan as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->penjualan_kode);
            $sheet->setCellValue('C'.$baris, $value->pembeli);
            $sheet->setCellValue('D'.$baris, $value->penjualan_tanggal);
            $sheet->setCellValue('E'.$baris, $value->user->nama);
            $baris++;
            $no++;
        }
        foreach(range('A','E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->setTitle('Data Penjualan');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Penjualan '.date('Y-m-d H:i:s').'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: '. gmdate('D, d M Y H:i:s') .' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $penjualan = PenjualanModel::select('user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->orderBy('penjualan_tanggal')
            ->with('user')
            ->get();

        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Penjualan '.date('Y-m-d H:i:s').'.pdf');
    }

    public function chartData()
    {
        $salesData = PenjualanModel::join('t_penjualan_detail', 't_penjualan.penjualan_id', '=', 't_penjualan_detail.penjualan_id')
            ->selectRaw("DATE_FORMAT(penjualan_tanggal, '%Y-%m-%d') as date, SUM(harga * jumlah) as total")
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return response()->json($salesData);
    }

}