<?php

return [
    'year_levels'          => [3],
    'sections'             => ['BSIT 3-1', 'BSIT 3-2', 'BSIT 3-3', 'BSIT 3-4'],
    'section_regex'        => '/^BSIT 3-[1-4]$/',
    'student_number_regex' => '/^\d{4}-\d{5}-SR-\d$/',
    'statuses'             => ['present', 'late', 'absent', 'excused'],
];
