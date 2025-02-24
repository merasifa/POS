<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            
            ['barang_id' => 1, 'kategori_id' => 1, 'supplier_id' => 1, 'barang_kode' => 'BRG001', 'barang_nama' => 'Beras Premium', 'harga_beli' => 12000, 'harga_jual' => 13500],
            ['barang_id' => 2, 'kategori_id' => 1, 'supplier_id' => 1, 'barang_kode' => 'BRG002', 'barang_nama' => 'Telur Ayam', 'harga_beli' => 2000, 'harga_jual' => 2500],
            ['barang_id' => 3, 'kategori_id' => 2, 'supplier_id' => 1, 'barang_kode' => 'BRG003', 'barang_nama' => 'Teh Celup', 'harga_beli' => 5000, 'harga_jual' => 6000],
            ['barang_id' => 4, 'kategori_id' => 2, 'supplier_id' => 1, 'barang_kode' => 'BRG004', 'barang_nama' => 'Kopi Instan', 'harga_beli' =>7000, 'harga_jual' => 8500],
            ['barang_id' => 5, 'kategori_id' => 2, 'supplier_id' => 1, 'barang_kode' => 'BRG005', 'barang_nama' => 'Susu UHT', 'harga_beli' => 9000, 'harga_jual' => 10500],

            
            ['barang_id' => 6, 'kategori_id' => 3, 'supplier_id' => 2, 'barang_kode' => 'BRG006', 'barang_nama' => 'Sabun Mandi', 'harga_beli' => 5000, 'harga_jual' => 6000],
            ['barang_id' => 7, 'kategori_id' => 3, 'supplier_id' => 2, 'barang_kode' => 'BRG007', 'barang_nama' => 'Shampoo', 'harga_beli' => 10000, 'harga_jual' => 12000],
            ['barang_id' => 8, 'kategori_id' => 4, 'supplier_id' => 2, 'barang_kode' => 'BRG008', 'barang_nama' => 'Lotion', 'harga_beli' => 15000, 'harga_jual' => 18000],
            ['barang_id' => 9, 'kategori_id' => 4, 'supplier_id' => 2, 'barang_kode' => 'BRG009', 'barang_nama' => 'Lipstick', 'harga_beli' => 25000, 'harga_jual' => 28000],
            ['barang_id' => 10, 'kategori_id' => 4, 'supplier_id' => 2, 'barang_kode' => 'BRG010', 'barang_nama' => 'Foundation', 'harga_beli' => 30000, 'harga_jual' => 35000],

            
            ['barang_id' => 11, 'kategori_id' => 5, 'supplier_id' => 3, 'barang_kode' => 'BRG011', 'barang_nama' => 'Lampu LED', 'harga_beli' => 25000, 'harga_jual' => 28000],
            ['barang_id' => 12, 'kategori_id' => 5, 'supplier_id' => 3, 'barang_kode' => 'BRG012', 'barang_nama' => 'Kipas Angin', 'harga_beli' => 200000, 'harga_jual' => 230000],
            ['barang_id' => 13, 'kategori_id' => 5, 'supplier_id' => 3, 'barang_kode' => 'BRG013', 'barang_nama' => 'Rice Cooker', 'harga_beli' => 300000, 'harga_jual' => 350000],
            ['barang_id' => 14, 'kategori_id' => 5, 'supplier_id' => 3, 'barang_kode' => 'BRG014', 'barang_nama' => 'Setrika', 'harga_beli' => 150000, 'harga_jual' => 170000],
            ['barang_id' => 15, 'kategori_id' => 5, 'supplier_id' => 3, 'barang_kode' => 'BRG015', 'barang_nama' => 'Blender', 'harga_beli' => 250000, 'harga_jual' => 300000],
        ];

        DB::table('m_barang')->insert($data);
    }
}
