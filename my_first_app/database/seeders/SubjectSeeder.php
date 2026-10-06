<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['code' => 'COMP 015',  'name' => 'Fundamentals of Research'],
            ['code' => 'COMP 016',  'name' => 'Web Development'],
            ['code' => 'COMP 017',  'name' => 'Multimedia'],
            ['code' => 'COMP 018',  'name' => 'Database Administration'],
            ['code' => 'ELEC IT-E1', 'name' => 'IT Elective 1'],
            ['code' => 'GEED 006',  'name' => 'Art Appreciation / Pagpapahalaga sa Sining'],
            ['code' => 'INTE 301',  'name' => 'Systems Integration and Architecture 1'],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['code' => $subject['code']],
                ['name' => $subject['name'], 'is_active' => true]
            );
        }
    }
}
