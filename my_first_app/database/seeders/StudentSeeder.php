<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $sections = ['BSIT 3-1', 'BSIT 3-2', 'BSIT 3-3', 'BSIT 3-4'];
        
        $sampleNames = [
            ['Juan', 'Dela Cruz'],
            ['Maria', 'Santos'],
            ['Mark', 'Reyes'],
            ['Angela', 'Castroverde'],
            ['Chazlene', 'Bacay'],
            ['Johnrey', 'Aborot'],
            ['Alexia Eunice', 'Patulot'],
            ['Christian', 'Bautista'],
            ['Princess', 'Aquino'],
            ['Gabriel', 'Mendoza'],
        ];

        $studentCounter = 100;

        foreach ($sections as $sectionIndex => $section) {
            foreach ($sampleNames as $nameIndex => $name) {
                $studentCounter++;
                $paddedCount = str_pad($studentCounter, 5, '0', STR_PAD_LEFT);
                $secDigit = $sectionIndex + 1;
                $studentNumber = "2024-{$paddedCount}-SR-{$secDigit}";

                Student::updateOrCreate(
                    ['student_number' => $studentNumber],
                    [
                        'first_name' => $name[0],
                        'last_name'  => $name[1],
                        'section'    => $section,
                    ]
                );
            }
        }
    }
}
