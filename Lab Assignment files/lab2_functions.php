<?php
// ============================================================
// Lab Programme 2: PHP Functions Implementation
// Author : Rishav Raj | 23MEI10002
// Subject: Internet and Web Programming (CSE4001)
// ============================================================

// ── 1. Factorial ─────────────────────────────────────────
function factorial($n) {
    if ($n < 0)  return "Undefined";
    if ($n === 0 || $n === 1) return 1;
    return $n * factorial($n - 1);
}

// ── 2. Prime Number Check ────────────────────────────────
function isPrime($n) {
    if ($n < 2) return false;
    for ($i = 2; $i <= sqrt($n); $i++) {
        if ($n % $i === 0) return false;
    }
    return true;
}

// ── 3. String Reversal ───────────────────────────────────
function reverseString($str) {
    return strrev($str);  // built-in
}

// Manual reversal (without strrev)
function reverseStringManual($str) {
    $result = "";
    for ($i = strlen($str) - 1; $i >= 0; $i--) {
        $result .= $str[$i];
    }
    return $result;
}

// ── Output ───────────────────────────────────────────────
echo str_repeat("=", 50) . "\n";
echo "  PHP FUNCTIONS IMPLEMENTATION\n";
echo "  Rishav Raj | 23MEI10002\n";
echo str_repeat("=", 50) . "\n";

// Factorial
echo "\n-- FACTORIAL --\n";
foreach ([0, 1, 5, 7, 10] as $n) {
    printf("  factorial(%2d) = %d\n", $n, factorial($n));
}

// Prime Check
echo "\n-- PRIME NUMBER CHECK --\n";
$tests = [1, 2, 7, 13, 15, 17, 20, 29];
foreach ($tests as $n) {
    printf("  %2d is %s\n", $n, isPrime($n) ? "PRIME" : "NOT prime");
}

// List all primes up to 50
echo "\n  Primes up to 50: ";
$primes = array_filter(range(2, 50), 'isPrime');
echo implode(", ", $primes) . "\n";

// String Reversal
echo "\n-- STRING REVERSAL --\n";
$strings = ["Rishav Raj", "PHP", "Hello World", "23MEI10002", "ABCDE"];
foreach ($strings as $s) {
    printf("  %-15s => %s\n", "\"$s\"", reverseString($s));
}
echo "\n  Manual reverse of 'OpenAI': " . reverseStringManual("OpenAI") . "\n";

echo "\n" . str_repeat("=", 50) . "\n";
echo "  Completed: " . date("d-m-Y H:i:s") . "\n";
echo str_repeat("=", 50) . "\n";
?>
