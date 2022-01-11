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
    /**
     * Add a course to the existing timetable
     *
     * @param string $code Course code
     * @param string $studentCount Number of student offering te course
     * @param array $departments Department parts offering the course
     * @param array $preferredDays Preferred days set by lecturer
     *
     * @return Course
     * @throws Throwable
     */
    public function generate(string $code, string $studentCount, array $departments, array $preferredDays): Course
    {
        // If course already exists in timetable, modify the periods and venue instead of adding it afresh
        if (Course::where('code', $code)->exists()) {
            return $this->update($code, $studentCount, $departments, $preferredDays);
        }

        // If not, add the course
        DB::beginTransaction();
        try {
            // Create a new Course record
            $course = new Course;
            $course->code = Str::replace(' ', '', $code);
            $course->student_count = $studentCount;

            // Associate the Course record to the respective Department records
            $depts = array_map(fn ($dept) => Department::find($dept), $departments);
            $course->departments()->saveMany($depts);

            // For each preferred day,
            // 1. Allocate a free period in that day to the course
            // 2. Allocate a lecture theatre spacious enough to accommodate the students
            foreach ($preferredDays as $day) {
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

    /**
     * Update the timetable of a particular course
     *
     * @param string $code Course code
     * @param string $studentCount Number of student offering te course
     * @param array $departments Department parts offering the course
     * @param array $preferredDays Preferred days set by lecturer
     * @return Course
     */
    public function update(string $code, string $studentCount, array $departments, array $preferredDays): Course
    {
        // Retrieve te existing Course record and update it
        $course = Course::findOrFail($code);
        $course->student_count = $studentCount;
        $depts = array_map(fn ($dept) => Department::find($dept), $departments);
        $course->departments()->detach();
        $course->departments()->saveMany($depts);
        $course->periods()->update([
            'course_code' => null,
            'venue_code' => null,
        ]);

        // For each preferred day,
        // 1. Allocate a free period in that day to the course
        // 2. Allocate a lecture theatre spacious enough to accommodate the students
        foreach ($preferredDays as $day) {
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
