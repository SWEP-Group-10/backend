<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = Department::all();

        foreach ($departments as $department) {
            $department->code = $this->generateCode($department->name);
            $department->save();
        }
    }

    private function generateCode(string $name): string
    {
        return preg_replace("/[^A-Z0-9]/", '', $name);
    }
}
