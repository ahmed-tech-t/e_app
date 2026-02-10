<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::create(
            [
                'code' => 'LOC-1234',
                'name' => 'Store 1',
                'address' => '123 Main Street',
                'phone' => '123-456-7890',
                'type' => 'store',
            ]
        );
        Location::create(
            [
                'code' => 'LOC-1235',
                'name' => 'Warehouse 1',
                'type' => 'wharehouse',
            ]
        );
    }
}
