<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = [
            'Computer Science with Mathematics',
            'Computer Engineering',
            'Computer Science with Economics',
            'Mechanical Engineering',
            'Electrical and Electronics Engineering',
            'Chemical Engineering',
            'Civil Engineering',
            'Food Science and Technology',
            'Material Science and Engineering',
            'Agricultural Engineering',
        ];
        $departments = [];

        foreach ($options as $option) {
            for ($i = 1; $i <= 5; $i++) {
                $departments[] = ['name' => "$option ${i}00 level"];
            }
        }

        Department::insert($departments);
    }
}
