<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\UserModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use App\Models\SupplierModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Stok Barang',
            'list' => ['Home', 'Stok']
        ];

        $page = (object) [
            'title' => 'Daftar data stok barang'
        ];

        $activeMenu = 'stok';

        $barang = BarangModel::all(); // untuk filter barang
        $supplier = SupplierModel::all(); // untuk filter supplier
        

        return view('stok.index', compact('breadcrumb', 'page', 'activeMenu', 'barang', 'supplier'));
    }
    public function list(Request $request)
    {
        $stok = StokModel::with(['barang', 'user', 'supplier']);

        if ($request->barang_id) {
            $stok->where('barang_id', $request->barang_id);
        }

        if ($request->supplier_id) {
            $stok->where('supplier_id', $request->supplier_id);
        }

        if ($request->stok_jumlah == 'kurang10') {
            $stok->where('stok_jumlah', '<', 10);
        } elseif ($request->stok_jumlah == 'lebih100') {
            $stok->where('stok_jumlah', '>', 100);
        } elseif ($request->stok_jumlah == 'habis') {
            $stok->where('stok_jumlah', '=', 0);
        }

        return DataTables::of($stok)
            ->addIndexColumn()
            ->addColumn('aksi', function ($s) {
            //     $btn  = '<a href="' . url('/stok/' . $s->stok_id) . '" class="btn btn-info btn-sm">Detail</a> ';
            //     $btn .= '<a href="' . url('/stok/' . $s->stok_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
            //     $btn .= '<form class="d-inline-block" method="POST" action="' . url('/stok/' . $s->stok_id) . '">'
            //           . csrf_field()
            //           . method_field('DELETE')
            //           . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus data ini?\');">Hapus</button>'
            //           . '</form>';
            //     return $btn;
            $btn = ''; 
            $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $s->stok_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $s->stok_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Stok',
            'list' => ['Home', 'Stok', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah data stok baru'
        ];

        $barang = BarangModel::all();
        $user = UserModel::all();
        $supplier = SupplierModel::all();
        $activeMenu = 'stok';

        return view('stok.create', compact('breadcrumb', 'page', 'barang', 'user', 'supplier', 'activeMenu'));
    }

    // Simpan data stok baru
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|integer',
            'user_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:1',
        ]);

        StokModel::create($request->all());

        return redirect('/stok')->with('success', 'Data stok berhasil disimpan');
    }


    public function show($id)
    {
        $stok = StokModel::with(['barang', 'user', 'supplier'])->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Stok',
            'list' => ['Home', 'Stok', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail data stok'
        ];

        $activeMenu = 'stok';

        return view('stok.show', compact('breadcrumb', 'page', 'stok', 'activeMenu'));
    }

    public function edit($id)
    {
        $stok = StokModel::find($id);
        $barang = BarangModel::all();
        $user = UserModel::all();
        $supplier = SupplierModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Stok',
            'list' => ['Home', 'Stok', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit data stok'
        ];

        $activeMenu = 'stok';

        return view('stok.edit', compact('breadcrumb', 'page', 'stok', 'barang', 'user', 'supplier', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|integer',
            'user_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:1',
        ]);

        StokModel::find($id)->update($request->all());

        return redirect('/stok')->with('success', 'Data stok berhasil diubah');
    }

    public function destroy($id)
    {
        $check = StokModel::find($id);

        if (!$check) {
            return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
        }

        try {
            StokModel::destroy($id);
            return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/stok')->with('error', 'Gagal menghapus data stok karena data masih terhubung dengan tabel lain');
        }
    }

    public function create_ajax()
    {
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get(); // Ambil data supplier

        return view('stok.create_ajax', [
            'barang' => $barang,
            'user' => $user,
            'supplier' => $supplier
        ]);
    }

    // Simpan data stok baru
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id'    => 'required|integer|exists:m_barang,barang_id',
                'user_id'      => 'required|integer|exists:m_user,user_id',
                'supplier_id'  => 'required|integer|exists:m_supplier,supplier_id', // Tambahkan validasi supplier
                'stok_tanggal' => 'required|date',
                'stok_jumlah'  => 'required|integer|min:1',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            StokModel::create($request->all());

            return response()->json([
                'status'  => true,
                'message' => 'Data stok berhasil disimpan.',
            ]);
        }

        return redirect('/');
    }
    // Form edit data stok
    public function edit_ajax(string $id)
    {
        $stok = StokModel::find($id);
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get(); // Ambil daftar supplier

        return view('stok.edit_ajax', [
            'stok' => $stok,
            'barang' => $barang,
            'user' => $user,
            'supplier' => $supplier,
        ]);
    }

    // Update data stok
    public function update_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'stok_tanggal' => 'required|date',
            'stok_jumlah'  => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi gagal.',
                'msgField' => $validator->errors(),
            ]);
        }

        $stok = StokModel::find($id);
        if ($stok) {
            $jumlah_lama = $stok->stok_jumlah;
            $jumlah_baru = $request->input('stok_jumlah');

            if ($jumlah_baru < $jumlah_lama) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Pengurangan jumlah stok tidak diizinkan melalui form ini.',
                ]);
            }

            $stok->update($request->all());

            return response()->json([
                'status'  => true,
                'message' => 'Data stok berhasil diupdate.',
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
        $stok = StokModel::with(['barang', 'user', 'supplier'])->find($id);

        return view('stok.show_ajax', ['stok' => $stok]);
    }
    public function confirm_ajax(string $id)
    {
        $stok = StokModel::find($id);

        return view('stok.confirm_ajax', ['stok' => $stok]);
    }
    public function delete_ajax(Request $request, $id)
        {
            if ($request->ajax() || $request->wantsJson()) {
                $stok = StokModel::find($id);

                if ($stok) {
                    $stok->delete();

                    return response()->json([
                        'status' => true,
                        'message' => 'Data stok berhasil dihapus.',
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

    public function import()
     {
         return view('stok.import');
     }
     public function import_ajax(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = ['file_stok' => 'required|mimes:xlsx|max:1024'];
             $validator = Validator::make($request->all(), $rules);

             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors()
                 ]);
             }

             $file = $request->file('file_stok'); // Ambil file dari request
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
                             'barang_id' => $value['A'],
                             'supplier_id' => $value['B'],
                             'stok_tanggal' => $value['C'],
                             'stok_jumlah' => $value['D'],
                             'created_at'  => now(),
                         ];
                     }
                 }

                 if (count($insert) > 0) {
                     StokModel::insertOrIgnore($insert);
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
     public function export_excel()
        {
            $stok = StokModel::with(['barang', 'user', 'supplier'])
                ->get()
                ->sortBy(function ($item) {
                    return $item->barang->barang_kode ?? '';
                });

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Judul kolom
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Kode Barang');
            $sheet->setCellValue('C1', 'Nama Barang');
            $sheet->setCellValue('D1', 'Supplier');
            $sheet->setCellValue('E1', 'Jumlah');
            $sheet->setCellValue('F1', 'Tanggal');
            $sheet->getStyle('A1:F1')->getFont()->setBold(true);

            $no = 1;
            $baris = 2;

            foreach ($stok as $item) {
                $sheet->setCellValue('A' . $baris, $no);
                $sheet->setCellValue('B' . $baris, $item->barang->barang_kode ?? '-');
                $sheet->setCellValue('C' . $baris, $item->barang->barang_nama ?? '-');
                $sheet->setCellValue('D' . $baris, $item->supplier->supplier_nama ?? '-');
                $sheet->setCellValue('E' . $baris, $item->stok_jumlah);
                $sheet->setCellValue('F' . $baris, $item->stok_tanggal);

                $no++;
                $baris++;
            }

            foreach (range('A', 'F') as $kolom) {
                $sheet->getColumnDimension($kolom)->setAutoSize(true);
            }

            $sheet->setTitle('Data Stok');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'Data Stok ' . date('Y-m-d H-i-s') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');

            $writer->save('php://output');
            exit;
        }

        public function export_pdf()
        {
            $stok = StokModel::select('barang_id', 'user_id', 'supplier_id', 'stok_tanggal', 'stok_jumlah')
                ->with(['barang', 'user', 'supplier']) // memuat relasi
                ->get()
                ->sortBy(function ($item) {
                    return $item->barang->barang_kode ?? ''; // urut berdasarkan kode barang
                });

            $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption("isRemoteEnabled", true);
            $pdf->render();

            return $pdf->stream('Data Stok ' . date('Y-m-d H:i:s') . '.pdf');
        }

}