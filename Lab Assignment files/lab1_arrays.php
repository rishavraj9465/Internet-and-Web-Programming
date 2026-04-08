<?php
// ============================================================
// Lab Programme 1: PHP Basics - Array Operations
// Author : Rishav Raj | 23MEI10002
// Subject: Internet and Web Programming (CSE4001)
// ============================================================

// ── 1. Indexed Array ─────────────────────────────────────
$fruits = ["Apple", "Banana", "Cherry", "Date", "Elderberry"];
echo "Indexed Array:\n";
foreach ($fruits as $i => $val) echo "  [$i] => $val\n";

// ── 2. Associative Array ─────────────────────────────────
$student = ["name" => "Rishav Raj", "roll" => "23MEI10002",
            "branch" => "MEI",       "cgpa" => 8.7];
echo "\nAssociative Array:\n";
foreach ($student as $key => $val) echo "  $key => $val\n";

// ── 3. Insertion ─────────────────────────────────────────
array_push($fruits, "Fig");           // append
array_unshift($fruits, "Avocado");    // prepend
echo "\nAfter Insertion:\n  " . implode(", ", $fruits) . "\n";

// ── 4. Deletion ──────────────────────────────────────────
unset($fruits[2]);                   // remove Cherry
$fruits = array_values($fruits);     // re-index
echo "\nAfter Deletion (Cherry removed):\n  " . implode(", ", $fruits) . "\n";

// ── 5. Sorting ───────────────────────────────────────────
sort($fruits);
echo "\nAfter Ascending Sort:\n  " . implode(", ", $fruits) . "\n";

rsort($fruits);
echo "\nAfter Descending Sort:\n  " . implode(", ", $fruits) . "\n";

// ── 6. Array Functions ───────────────────────────────────
echo "\nArray Info:\n";
echo "  Count   : " . count($fruits) . "\n";
echo "  Max(cgpa lookup): N/A for strings\n";
$nums = [5, 3, 9, 1, 7, 2, 8];
echo "  Numeric array max : " . max($nums) . "\n";
echo "  Numeric array min : " . min($nums) . "\n";
echo "  Sum               : " . array_sum($nums) . "\n";
echo "  Search 'Banana'   : " . (in_array("Banana", $fruits) ? "Found" : "Not found") . "\n";
echo "\nDone. " . date("d-m-Y H:i:s") . "\n";
?>
