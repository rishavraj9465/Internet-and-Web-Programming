<?php
// ============================================================
// Assignment 3: Daily Expense Tracker
// Author  : RISHAV RAJ  |  23MEI10002
// Covers  : Arrays, File Handling, Date & Time, Functions
// ============================================================

$expenseFile = "expenses.txt";

// ---------- 1. Store Expense to File ----------
function storeExpense($file, $date, $category, $amount) {
    $entry = "$date|$category|$amount\n";
    file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
}

// ---------- 2. Load All Expenses into Array ----------
function loadExpenses($file) {
    $expenses = [];
    if (!file_exists($file)) return $expenses;
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode("|", $line);
        if (count($parts) === 3) {
            $expenses[] = [
                "date"     => $parts[0],
                "category" => $parts[1],
                "amount"   => (float)$parts[2],
            ];
        }
    }
    return $expenses;
}

// ---------- 3. Calculate Total for a Given Date ----------
function totalForDate($expenses, $targetDate) {
    $total = 0;
    foreach ($expenses as $exp) {
        if ($exp['date'] === $targetDate) {
            $total += $exp['amount'];
        }
    }
    return $total;
}

// ---------- 4. Display Expenses for a Given Date ----------
function displayByDate($expenses, $targetDate) {
    $found = false;
    foreach ($expenses as $exp) {
        if ($exp['date'] === $targetDate) {
            printf("  %-14s %-18s Rs. %8.2f\n",
                $exp['date'], $exp['category'], $exp['amount']);
            $found = true;
        }
    }
    if (!$found) echo "  No expenses found for $targetDate.\n";
}

// ---------- 5. Simulate Data Entry (CLI mode) ----------
$entries = [
    ["date" => "2026-04-06", "category" => "Food",       "amount" => 250.00],
    ["date" => "2026-04-06", "category" => "Transport",  "amount" => 80.00 ],
    ["date" => "2026-04-06", "category" => "Stationery", "amount" => 150.00],
    ["date" => "2026-04-05", "category" => "Food",       "amount" => 200.00],
    ["date" => "2026-04-05", "category" => "Internet",   "amount" => 499.00],
    ["date" => "2026-04-04", "category" => "Books",      "amount" => 600.00],
];

// Clear file for demo
if (file_exists($expenseFile)) unlink($expenseFile);

echo str_repeat("=", 60) . "\n";
echo "          DAILY EXPENSE TRACKER\n";
echo "          Author: RISHAV RAJ | 23MEI10002\n";
echo str_repeat("=", 60) . "\n\n";

echo "  [+] Storing Expense Entries...\n";
foreach ($entries as $e) {
    storeExpense($expenseFile, $e['date'], $e['category'], $e['amount']);
    printf("  Saved: %-12s %-15s Rs. %.2f\n", $e['date'], $e['category'], $e['amount']);
}

// Load all expenses
$allExpenses = loadExpenses($expenseFile);
$today = date("Y-m-d");  // Current date

echo "\n" . str_repeat("=", 60) . "\n";
echo "  ALL STORED EXPENSES\n";
echo str_repeat("-", 60) . "\n";
printf("  %-14s %-18s %s\n", "Date", "Category", "Amount (Rs.)");
echo str_repeat("-", 60) . "\n";
foreach ($allExpenses as $exp) {
    printf("  %-14s %-18s Rs. %8.2f\n",
        $exp['date'], $exp['category'], $exp['amount']);
}
echo str_repeat("=", 60) . "\n";

// Summary per date
$dates = array_unique(array_column($allExpenses, 'date'));
echo "\n  EXPENSE TOTALS BY DATE\n";
echo str_repeat("-", 40) . "\n";
foreach ($dates as $d) {
    $total = totalForDate($allExpenses, $d);
    printf("  %-14s  Total: Rs. %.2f\n", $d, $total);
}
echo str_repeat("=", 60) . "\n";

// Today's expenses
echo "\n  TODAY'S EXPENSES (" . date("d-m-Y") . ")\n";
echo str_repeat("-", 60) . "\n";
printf("  %-14s %-18s %s\n", "Date", "Category", "Amount (Rs.)");
echo str_repeat("-", 60) . "\n";
displayByDate($allExpenses, $today);
$todayTotal = totalForDate($allExpenses, $today);
echo str_repeat("-", 60) . "\n";
printf("  %-32s Rs. %.2f\n", "Total for Today:", $todayTotal);
echo str_repeat("=", 60) . "\n";
echo "  Report Generated: " . date("d-m-Y H:i:s") . "\n";
echo str_repeat("=", 60) . "\n";
?>
