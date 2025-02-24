<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Penjualan 1
            ['penjualan_detail_id' => 1, 'penjualan_id' => 1, 'barang_id' => 1, 'harga' => 13500, 'jumlah' => 2],
            ['penjualan_detail_id' => 1, 'penjualan_id' => 1, 'barang_id' => 1, 'harga' => 13500, 'jumlah' => 2],
            ['penjualan_detail_id' => 1, 'penjualan_id' => 1, 'barang_id' => 1, 'harga' => 13500, 'jumlah' => 2],
        
            ];
        
        DB::table('t_penjualan_detail')->insert($data);
        
    }
}
