<?php
// ============================================================
// Assignment 1: Student Data Management System
// Author  : RISHAV RAJ  |  23MEI10002
// Covers  : Arrays, Functions, Date & Time
// ============================================================

// ---------- 1. Associative Array of Students ----------
$students = [
    ["name" => "Aarav Sharma",  "marks" => 92, "dob" => "2003-05-14"],
    ["name" => "Priya Mehta",   "marks" => 78, "dob" => "2002-11-22"],
    ["name" => "Rohit Kumar",   "marks" => 55, "dob" => "2004-01-08"],
    ["name" => "Sneha Patel",   "marks" => 85, "dob" => "2003-07-30"],
    ["name" => "Vikram Yadav",  "marks" => 40, "dob" => "2002-03-17"],
];

// ---------- 2. Function: Calculate Average Marks ----------
function calculateAverage($studentsArray) {
    $total = 0;
    foreach ($studentsArray as $student) {
        $total += $student['marks'];
    }
    return round($total / count($studentsArray), 2);
}

// ---------- 3. Function: Determine Grade ----------
function determineGrade($marks) {
    if      ($marks >= 90) return "A+";
    elseif  ($marks >= 80) return "A";
    elseif  ($marks >= 70) return "B";
    elseif  ($marks >= 60) return "C";
    elseif  ($marks >= 50) return "D";
    else                   return "F";
}

// ---------- 4. Function: Calculate Age from DOB ----------
function calculateAge($dob) {
    $birthDate = new DateTime($dob);
    $today     = new DateTime();
    $age       = $today->diff($birthDate);
    return $age->y;
}

// ---------- 5. Display Table ----------
echo str_repeat("=", 75) . "\n";
echo "              STUDENT DATA MANAGEMENT SYSTEM\n";
echo "              Author: RISHAV RAJ | 23MEI10002\n";
echo str_repeat("=", 75) . "\n";
printf("%-20s %-8s %-12s %-6s %-5s\n",
    "Name", "Marks", "DOB", "Age", "Grade");
echo str_repeat("-", 75) . "\n";

foreach ($students as $student) {
    $grade = determineGrade($student['marks']);
    $age   = calculateAge($student['dob']);
    printf("%-20s %-8d %-12s %-6d %-5s\n",
        $student['name'], $student['marks'],
        $student['dob'], $age, $grade);
}

echo str_repeat("=", 75) . "\n";
$avg = calculateAverage($students);
echo "  Class Average Marks : $avg\n";
echo "  Report Generated On : " . date("d-m-Y  H:i:s") . "\n";
echo str_repeat("=", 75) . "\n";
?>
