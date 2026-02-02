<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(
            [
                'code' => 'ESS-1234',
                'name_ar' => 'اكواب',
                'name_en' => 'cups',
            ]
        );
        Category::create(
            [
                'code' => 'ESY-1235',
                'name_ar' => 'اطباق',
                'name_en' => 'dishes',
            ]
        );
        Category::create(
            [
                'code' => 'EGS-1237',
                'name_ar' => 'سلطانية',
                'name_en' => 'bowl',
            ]
        );
    }
}
