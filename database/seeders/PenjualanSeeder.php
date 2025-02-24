<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['penjualan_id' => 1, 'user_id' => 3, 'pembeli' => 'Devina', 'penjualan_kode' => 'PJ001', 'penjualan_tanggal' => '2025-02-16'],
            ['penjualan_id' => 2, 'user_id' => 3, 'pembeli' => 'Riska', 'penjualan_kode' => 'PJ002', 'penjualan_tanggal' => '2025-02-17'],
            ['penjualan_id' => 3, 'user_id' => 3, 'pembeli' => 'Isna', 'penjualan_kode' => 'PJ003', 'penjualan_tanggal' => '2025-02-18'],
            ['penjualan_id' => 4, 'user_id' => 3, 'pembeli' => 'Hana', 'penjualan_kode' => 'PJ004', 'penjualan_tanggal' => '2025-02-19'],
            ['penjualan_id' => 5, 'user_id' => 3, 'pembeli' => 'Rea', 'penjualan_kode' => 'PJ005', 'penjualan_tanggal' => '2025-02-20'],
            ['penjualan_id' => 6, 'user_id' => 3, 'pembeli' => 'Anam', 'penjualan_kode' => 'PJ006', 'penjualan_tanggal' => '2025-02-21'],
            ['penjualan_id' => 7, 'user_id' => 3, 'pembeli' => 'Faiz', 'penjualan_kode' => 'PJ007', 'penjualan_tanggal' => '2025-02-22'],
            ['penjualan_id' => 8, 'user_id' => 3, 'pembeli' => 'Agung', 'penjualan_kode' => 'PJ008', 'penjualan_tanggal' => '2025-02-23'],
            ['penjualan_id' => 9, 'user_id' => 3, 'pembeli' => 'Marysa', 'penjualan_kode' => 'PJ009', 'penjualan_tanggal' => '2025-02-24'],
            ['penjualan_id' => 10, 'user_id' => 3, 'pembeli' => 'Mifta', 'penjualan_kode' => 'PJ010', 'penjualan_tanggal' => '2025-02-25'],
        ];
        
        DB::table('t_penjualan')->insert($data);
        
    }
}
