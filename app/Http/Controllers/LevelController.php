<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Level;
use Yajra\DataTables\Facades\DataTables;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class LevelController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar Level yang terdaftar di sistem'
        ];

        $activeMenu = 'level';
        $data = DB::select('select * from m_level');

        return view('level.index', compact('breadcrumb', 'page', 'activeMenu', 'data'));
    }

    public function list(Request $request)
    {
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama');

        if ($request->level_nama) {
            $level->where('level_nama', 'like', '%' . $request->level_nama . '%');
        }

        return DataTables::of($level)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) {
                // $btn = '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level/' . $level->level_id) . '">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                $btn = '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Level Baru'
        ];

        $activeMenu = 'level';

        return view('level.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|unique:m_level',
            'level_nama' => 'required'
        ]);

        LevelModel::create($request->all());

        return redirect('/level')->with('status', 'Data level berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Level'
        ];

        $activeMenu = 'level';
        $level = LevelModel::findOrFail($id);

        return view('level.edit', compact('breadcrumb', 'page', 'activeMenu', 'level'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'level_kode' => 'required|unique:m_level,level_kode,' . $id . ',level_id',
            'level_nama' => 'required'
        ]);

        LevelModel::findOrFail($id)->update($request->all());

        return redirect('/level')->with('status', 'Data level berhasil diubah!');
    }

    public function destroy($id)
    {
        $check = LevelModel::find($id);
        if (!$check) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }

        try {
            LevelModel::destroy($id);
            return redirect('/level')->with('success', 'Data level berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
    public function create_ajax()
    {
        return view('level.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|max:10|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $level = LevelModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil disimpan',
            ]);
        }
        return redirect('/');
    }

    public function show_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.show_ajax', ['level' => $level]);
    }

    public function edit_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.edit_ajax', ['level' => $level]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => "required|string|max:10|unique:m_level,level_kode,{$id},level_id",
                'level_nama' => 'required|string|max:100',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }
            $check = LevelModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
            
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', ['level' => $level]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $check = LevelModel::find($id);
            if ($check) {
                try {
                    $check = LevelModel::find($id);
                    if ($check) {
                        $check->delete();
                        return response()->json([
                            'status' => true,
                            'message' => 'Data berhasil dihapus'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Data tidak ditemukan'
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error deleting user: ' . $e->getMessage());
                    if (str_contains($e->getMessage(), 'SQLSTATE[23000]')) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Data tidak dapat dihapus karena masih terkait dengan data lain di sistem'
                        ]);
                    }
            }
        }
        return redirect('/');
    }
}
public function import()
{
    return view('kategori.import');
}

public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_kategori' => ['required', 'mimes:xlsx', 'max:1024']
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_kategori');
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
                        'level_id' => $value['A'],
                        'level_kode' => $value['B'],
                        'level_nama' => $value['C'],
                        'created_at' => now(),
                    ];
                }
            }

            if (count($insert) > 0) {
                levelModel::insertOrIgnore($insert);
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
     // ambil data level yang akan di export
     $level = levelModel::select('level_id', 'level_kode', 'level_nama')
         ->orderBy('level_id')
         ->get();

         // load library excel
         $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
         $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

         $sheet->setCellValue('A1', 'No');
         $sheet->setCellValue('B1', 'Kode level');
         $sheet->setCellValue('C1', 'Nama level');

         $sheet->getStyle('A1:F1')->getFont()->setBold(true); // bold header

         $no = 1;         // nomor data dimulai dari 1
         $baris = 2;      // baris data dimulai dari baris ke 2

     foreach ($level as $key => $value) {
         $sheet->setCellValue('A' . $baris, $no);
         $sheet->setCellValue('B' . $baris, $value->level_kode);
         $sheet->setCellValue('C' . $baris, $value->level_nama);
         $baris++;
         $no++;
     }

     foreach (range('A', 'F') as $columnID) {
         $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
     }

     $sheet->setTitle('Data level'); // set title sheet

     $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
     $filename = 'Data level ' . date('Y-m-d H:i:s') . '.xlsx';

     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
     header('Content-Disposition: attachment;filename="' . $filename . '"');
     header('Cache-Control: max-age=0');
     header('Cache-Control: max-age=1');
     header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
     header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
     header('Cache-Control: cache, must-revalidate');
     header('Pragma: public');

     $writer->save('php://output');
     exit;
 } // end function export_excel

 public function export_pdf()
 {
     $level = LevelModel::select('level_id', 'level_kode', 'level_nama')
                 ->orderBy('level_id')
                 ->orderBy('level_kode')
                 ->get();

     // use Barryvdh\DomPDF\Facade\Pdf;
     $pdf = Pdf::loadView('level.export_pdf', ['level' => $level]);
     $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
     $pdf->setOption('isRemoteEnabled', true); // set true jika ada gambar dari url
     $pdf->render();

     return $pdf->stream('Data Kategori ' . date('Y-m-d H:i:s') . '.pdf');
 }
}