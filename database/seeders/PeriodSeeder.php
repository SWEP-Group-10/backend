<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $periods = [];
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        foreach ($days as $day) {
            for ($i = 8; $i < 18; $i++) {
                $periods[] = [
                    'day' => $day,
                    'start' => $i,
                    'stop' => $i+1,
                ];
            }
        }

        Period::insert($periods);
    }
}
