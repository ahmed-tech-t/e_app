<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create([
            'code' => '00154',
            'name' => 'Alex Store',
            'address' => 'Alexandria Egypt',
        ]);

        Store::create([
            'code' => '00164',
            'name' => 'Cairo Store',
            'address' => 'Cairo Egypt',
        ]);
    }
}
