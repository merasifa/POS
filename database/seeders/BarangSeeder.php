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
            
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'BRG001', 'barang_nama' => 'Beras Premium', 'harga_beli' => 12000, 'harga_jual' => 13500],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'BRG002', 'barang_nama' => 'Minyak Goreng 1L', 'harga_beli' => 15000, 'harga_jual' => 17000],
            ['barang_id' => 3, 'kategori_id' => 1, 'barang_kode' => 'BRG003', 'barang_nama' => 'Gula Pasir 1kg', 'harga_beli' => 14000, 'harga_jual' => 16000],
            ['barang_id' => 4, 'kategori_id' => 1, 'barang_kode' => 'BRG004', 'barang_nama' => 'Garam 500g', 'harga_beli' => 3000, 'harga_jual' => 4000],
            ['barang_id' => 5, 'kategori_id' => 1, 'barang_kode' => 'BRG005', 'barang_nama' => 'Tepung Terigu 1kg', 'harga_beli' => 11000, 'harga_jual' => 12500],

            ['barang_id' => 6, 'kategori_id' => 2, 'barang_kode' => 'BRG006', 'barang_nama' => 'Mie Instan', 'harga_beli' => 2500, 'harga_jual' => 3500],
            ['barang_id' => 7, 'kategori_id' => 2, 'barang_kode' => 'BRG007', 'barang_nama' => 'Biskuit Coklat', 'harga_beli' => 8000, 'harga_jual' => 9500],
            ['barang_id' => 8, 'kategori_id' => 2, 'barang_kode' => 'BRG008', 'barang_nama' => 'Keripik Singkong', 'harga_beli' => 10000, 'harga_jual' => 12000],
            ['barang_id' => 9, 'kategori_id' => 2, 'barang_kode' => 'BRG009', 'barang_nama' => 'Coklat Batang', 'harga_beli' => 12000, 'harga_jual' => 14000],
            ['barang_id' => 10, 'kategori_id' => 2, 'barang_kode' => 'BRG010', 'barang_nama' => 'Sosis Siap Makan', 'harga_beli' => 15000, 'harga_jual' => 18000],
 
            ['barang_id' => 11, 'kategori_id' => 3, 'barang_kode' => 'BRG011', 'barang_nama' => 'Teh Botol', 'harga_beli' => 4000, 'harga_jual' => 5000],
            ['barang_id' => 12, 'kategori_id' => 3, 'barang_kode' => 'BRG012', 'barang_nama' => 'Jus Jeruk Botol', 'harga_beli' => 10000, 'harga_jual' => 12000],
            ['barang_id' => 13, 'kategori_id' => 3, 'barang_kode' => 'BRG013', 'barang_nama' => 'Air Mineral Galon', 'harga_beli' => 20000, 'harga_jual' => 23000],
            ['barang_id' => 14, 'kategori_id' => 3, 'barang_kode' => 'BRG014', 'barang_nama' => 'Kopi Instan Sachet', 'harga_beli' => 1500, 'harga_jual' => 2000],
            ['barang_id' => 15, 'kategori_id' => 3, 'barang_kode' => 'BRG015', 'barang_nama' => 'Susu UHT', 'harga_beli' => 9000, 'harga_jual' => 10500],

        ];

        DB::table('m_barang')->insert($data);
    }
}
