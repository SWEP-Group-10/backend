<?php
declare(strict_types=1);

namespace App\Domain;

use App\Models\Course;
use App\Models\Department;
use App\Models\Period;
use App\Models\Venue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

final class TimetableGenerator
{
    public function generate(string $code, string $studentCount, array $departments, array $prefferedDays): Course
    {
        if (Course::where('code', $code)->exists()) {
            return $this->update($code, $studentCount, $departments, $prefferedDays);
        }

        DB::beginTransaction();
        try {
            $course = new Course;
            $course->code = Str::replace(' ', '', $code);
            $course->student_count = $studentCount;

            $depts = array_map(fn ($dept) => Department::find($dept), $departments);
            $course->departments()->saveMany($depts);

            foreach ($prefferedDays as $day) {
                $period = self::freePeriod($day, $departments);
                $venue = Venue::where('size', '>=', $studentCount)->get()->random();
                $venue->periods()->save($period);
                $venue->save();
                $course->periods()->save($period);
            }

            $course->save();
            DB::commit();
            $course->refresh();

            return $course;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(string $code, string $studentCount, array $departments, array $prefferedDays): Course
    {
        $course = Course::findOrFail($code);
        $course->student_count = $studentCount;
        $depts = array_map(fn ($dept) => Department::find($dept), $departments);
        $course->departments()->detach();
        $course->departments()->saveMany($depts);
        $course->periods()->update([
            'course_code' => null,
            'venue_code' => null,
        ]);

        foreach ($prefferedDays as $day) {
            $period = self::freePeriod($day, $departments);
            $venue = Venue::where('size', '>=', $studentCount)->get()->random();
            $venue->periods()->save($period);
            $venue->save();
            $course->periods()->save($period);
        }

        $course->save();
        $course->refresh();

        return $course;
    }

    /**
     * Find period that is free.
     * No course is offered by department in that period
     *
     * @param string $day
     * @param array $departments
     * @return Period
     */
    public static function freePeriod(string $day, array $departments): Period
    {
        $periods = Period::where('day', $day)->get();
        $validPeriods = [];

        foreach ($periods as $period) {
            if (is_null($period->course_code) || !in_array($period->course_code, $departments)) {
                $validPeriods[] = $period;
            }
        }

        return $validPeriods[array_rand($validPeriods)];
    }
}
