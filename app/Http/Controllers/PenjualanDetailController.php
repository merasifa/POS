<?php

namespace App\Http\Controllers;


use App\Models\UserModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PenjualanDetailModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) ['title' => 'Data Detail Penjualan', 'list' => ['Home', 'Detail Penjualan']];
        $page = (object) ['title' => 'Daftar Detail Penjualan'];
        $activeMenu = 'penjualan_detail';

        return view('penjualan_detail.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
    if ($request->ajax()) {
        $penjualanDetail = PenjualanDetailModel::with(['penjualan', 'barang']);

        return DataTables::of($penjualanDetail)
            ->addIndexColumn()
            ->addColumn('penjualan', function ($row) {
                return $row->penjualan->penjualan_id ?? '-';
            })
            ->addColumn('barang', function ($row) {
                return $row->barang->barang_nama ?? '-';
            })
            ->addColumn('subtotal', function ($row) {
                return number_format($row->subtotal, 0, ',', '.'); // format Rupiah
            })
            ->addColumn('aksi', function ($row) {
                $btn  = '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $row->detail_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $row->detail_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $row->detail_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    }


public function create_ajax()
{
    $penjualans = PenjualanModel::all();
    $barangs = BarangModel::all();
    return view('penjualan_detail.create_ajax', compact('barangs', 'penjualans'));
}

public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'penjualan_id' => 'required|exists:t_penjualan,penjualan_id',
            'barang_id' => 'required|exists:m_barang,barang_id',
            'harga' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors(),
            ]);
        }

        PenjualanDetailModel::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data detail penjualan berhasil disimpan.',
        ]);
    }

    return redirect('/');
}

public function show_ajax(string $id)
{
    $detail = PenjualanDetailModel::with(['penjualan', 'barang'])->find($id);

    return view('penjualan_detail.show_ajax', ['detail' => $detail]);
}

public function edit_ajax(string $id)
{
    $penjualan_detail = PenjualanDetailModel::find($id);

    if (!$penjualan_detail) {
        return response()->json(['status' => false, 'message' => 'Data tidak ditemukan.']);
    }

    $barangs = BarangModel::all();

    return view('penjualan_detail.edit_ajax', compact('penjualan_detail', 'barangs'));
}



public function update_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'barang_id' => 'required|exists:m_barang,barang_id',
            'harga' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors(),
            ]);
        }

        $detail = PenjualanDetailModel::find($id);

        if ($detail) {
            $detail->update([
                'barang_id' => $request->barang_id,
                'harga' => $request->harga,
                'jumlah' => $request->jumlah,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data detail penjualan berhasil diupdate.',
                'subtotal' => $detail->subtotal, // ← akses accessor
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.',
            ]);
        }
    }

    return redirect('/');
}



public function confirm_ajax(string $id)
{
    $detail = PenjualanDetailModel::find($id);

    return view('penjualan_detail.confirm_ajax', ['detail' => $detail]);
}

public function delete_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $detail = PenjualanDetailModel::find($id);

        if ($detail) {
            $detail->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data detail penjualan berhasil dihapus.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.',
            ]);
        }
    }

    return redirect('/');
}


public function export_excel()
{
    $details = PenjualanDetailModel::with(['penjualan', 'barang'])->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set judul kolom
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'ID Penjualan');
    $sheet->setCellValue('C1', 'Nama Barang');
    $sheet->setCellValue('D1', 'Harga');
    $sheet->setCellValue('E1', 'Jumlah');
    $sheet->setCellValue('F1', 'Total Harga');

    $sheet->getStyle('A1:F1')->getFont()->setBold(true);

    $no = 1;
    $baris = 2;

    foreach ($details as $item) {
        $sheet->setCellValue('A' . $baris, $no);
        $sheet->setCellValue('B' . $baris, $item->penjualan->penjualan_id ?? '-');
        $sheet->setCellValue('C' . $baris, $item->barang->barang_nama ?? '-');
        $sheet->setCellValue('D' . $baris, $item->harga);
        $sheet->setCellValue('E' . $baris, $item->jumlah);
        $sheet->setCellValue('F' . $baris, $item->harga * $item->jumlah);

        $no++;
        $baris++;
    }

    foreach (range('A', 'F') as $kolom) {
        $sheet->getColumnDimension($kolom)->setAutoSize(true);
    }

    $sheet->setTitle('Detail Penjualan');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Detail Penjualan ' . date('Y-m-d H-i-s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit;
}
public function export_pdf()
{
    $data = PenjualanDetailModel::with(['penjualan', 'barang'])->get();

    $pdf = Pdf::loadView('penjualan_detail.export_pdf', ['data' => $data]);
    $pdf->setPaper('a4', 'landscape');
    $pdf->setOption("isRemoteEnabled", true);
    return $pdf->stream('Detail Penjualan ' . date('Y-m-d H:i:s') . '.pdf');
}
public function import() {
    return view('penjualan_detail.import');
}
public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = ['file_penjualan_detail' => 'required|mimes:xlsx|max:1024'];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_penjualan_detail'); // Ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // Load reader file Excel
            $reader->setReadDataOnly(true); // Hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // Load file Excel
            $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) { // Jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // Baris ke-1 adalah header, maka lewati
                        $insert[] = [
                            'penjualan_id' => $value['A'],
                            'barang_id' => $value['B'],
                            'harga' => $value['C'],
                            'jumlah' => $value['D'],
                            'created_at'  => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    // Insert data ke database, jika data sudah ada, maka diabaikan
                    PenjualanDetailModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }

        return redirect('/');
    }
}