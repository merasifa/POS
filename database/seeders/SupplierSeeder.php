<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['supplier_id' => 1, 'supplier_nama' => 'PT Maju Jaya', 'alamat' => 'Jakarta'],
            ['supplier_id' => 2, 'supplier_nama' => 'CV Sejahtera', 'alamat' => 'Bandung'],
            ['supplier_id' => 3, 'supplier_nama' => 'UD Barokah', 'alamat' => 'Surabaya'],
        ];
        DB::table('m_supplier')->insert($data);
    }
}
