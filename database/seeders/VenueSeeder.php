<?php

namespace Database\Seeders;

use App\Models\Venue;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Venue::insert([
            ['code' => '1klt', 'name' => '1000LT', 'size' => 1000],
            ['code' => 'hslt-a', 'name' => 'HSLT A', 'size' => 500],
            ['code' => 'hslt-b', 'name' => 'HSLT B', 'size' => 500],
            ['code' => 'hslt-c', 'name' => 'HSLT C', 'size' => 600],
            ['code' => 'boo-a', 'name' => 'BOO A', 'size' => 300],
            ['code' => 'boo-b', 'name' => 'BOO B', 'size' => 300],
            ['code' => 'boo-c', 'name' => 'BOO C', 'size' => 500],
        ]);
    }
}
