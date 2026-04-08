<?php
// ============================================================
// Lab Programme 5: Date and Time Application
// Author : Rishav Raj | 23MEI10002
// Subject: Internet and Web Programming (CSE4001)
// ============================================================

// ── 1. Current Date & Time ───────────────────────────────
function showCurrentDateTime() {
    echo "  Current Date     : " . date("d-m-Y")      . "\n";
    echo "  Current Time     : " . date("H:i:s")      . "\n";
    echo "  Full DateTime    : " . date("d-m-Y H:i:s"). "\n";
    echo "  Day of Week      : " . date("l")           . "\n";
    echo "  Unix Timestamp   : " . time()              . "\n";
}

// ── 2. Separate Day / Month / Year ───────────────────────
function showDateParts() {
    echo "  Day    : " . date("d")    . "  (numeric)\n";
    echo "  Month  : " . date("m")    . " => " . date("F") . "\n";
    echo "  Year   : " . date("Y")    . "\n";
    echo "  Hour   : " . date("H")    . " (24h)\n";
    echo "  Minute : " . date("i")    . "\n";
    echo "  Second : " . date("s")    . "\n";
    echo "  Week No: " . date("W")    . "\n";
}

// ── 3. Age Calculator ────────────────────────────────────
function calculateAge($dob) {
    $birth = new DateTime($dob);
    $today = new DateTime();
    $diff  = $today->diff($birth);
    return [
        "years"  => $diff->y,
        "months" => $diff->m,
        "days"   => $diff->d,
    ];
}

// ── 4. Days Between Two Dates ────────────────────────────
function daysBetween($date1, $date2) {
    $d1 = new DateTime($date1);
    $d2 = new DateTime($date2);
    return abs($d1->diff($d2)->days);
}

// ── 5. Future / Past Dates ───────────────────────────────
function dateOffset($days) {
    return date("d-m-Y", strtotime(($days > 0 ? "+$days" : "$days") . " days"));
}

// ── Demo ──────────────────────────────────────────────────
echo str_repeat("=", 52) . "\n";
echo "  DATE AND TIME APPLICATION\n";
echo "  Rishav Raj | 23MEI10002\n";
echo str_repeat("=", 52) . "\n\n";

echo "-- Current Date & Time --\n";
showCurrentDateTime();

echo "\n-- Date Components --\n";
showDateParts();

echo "\n-- Age Calculator --\n";
$dobs = ["2003-05-14", "2002-11-22", "2004-01-08"];
foreach ($dobs as $dob) {
    $age = calculateAge($dob);
    printf("  DOB %s => %d years, %d months, %d days old\n",
        $dob, $age['years'], $age['months'], $age['days']);
}

echo "\n-- Days Between Dates --\n";
echo "  01-01-2024 to today : " . daysBetween("2024-01-01", date("Y-m-d")) . " days\n";
echo "  Independence Day to today : " . daysBetween("2025-08-15", date("Y-m-d")) . " days\n";

echo "\n-- Date Offsets from Today --\n";
echo "  30 days from now : " . dateOffset(30)  . "\n";
echo "  60 days from now : " . dateOffset(60)  . "\n";
echo "  30 days ago      : " . dateOffset(-30) . "\n";

echo "\n" . str_repeat("=", 52) . "\n";
echo "  Completed: " . date("d-m-Y H:i:s") . "\n";
echo str_repeat("=", 52) . "\n";
?>
