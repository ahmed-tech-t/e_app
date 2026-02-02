<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Models\SaleUnit;
use Illuminate\Database\Seeder;

class SaleUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaleUnit::create([
            'code' => 'EAS-1373',
            'name_ar' => 'قطعة',
            'name_en' => 'Piece',
        ]);

        SaleUnit::create([
            'code' => 'EAS-1183',
            'name_ar' => 'دستة',
            'name_en' => 'Box',
        ]);
    }
}
