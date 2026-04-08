<?php
// ============================================================
// Lab Programme 4: File Handling System
// Author : Rishav Raj | 23MEI10002
// Subject: Internet and Web Programming (CSE4001)
// ============================================================

$file = "data.txt";

// ── 1. Create and Write ───────────────────────────────────
function writeFile($file, $data) {
    $handle = fopen($file, "w");   // 'w' = create/overwrite
    if (!$handle) return "[ERR] Cannot open file.";
    fwrite($handle, $data);
    fclose($handle);
    return "[OK]  File written successfully.";
}

// ── 2. Read File ─────────────────────────────────────────
function readFile($file) {
    if (!file_exists($file)) return "[ERR] File not found.";
    return file_get_contents($file);
}

// ── 3. Append to File ────────────────────────────────────
function appendFile($file, $data) {
    $handle = fopen($file, "a");   // 'a' = append mode
    if (!$handle) return "[ERR] Cannot open file.";
    fwrite($handle, $data);
    fclose($handle);
    return "[OK]  Data appended successfully.";
}

// ── 4. File Metadata ─────────────────────────────────────
function fileInfo($file) {
    if (!file_exists($file)) return;
    echo "  File Name  : " . basename($file) . "\n";
    echo "  File Size  : " . filesize($file) . " bytes\n";
    echo "  Last Modified: " . date("d-m-Y H:i:s", filemtime($file)) . "\n";
    echo "  Readable   : " . (is_readable($file) ? "Yes" : "No") . "\n";
    echo "  Writable   : " . (is_writable($file) ? "Yes" : "No") . "\n";
}

// ── Demo ──────────────────────────────────────────────────
echo str_repeat("=", 52) . "\n";
echo "  FILE HANDLING SYSTEM\n";
echo "  Rishav Raj | 23MEI10002\n";
echo str_repeat("=", 52) . "\n\n";

// Write
$initial = "Student: Rishav Raj\nRoll: 23MEI10002\nBranch: MEI\n";
echo writeFile($file, $initial) . "\n";

// Read
echo "\n-- File Contents After Write --\n";
echo readFile($file);

// Append
echo "\n" . appendFile($file, "CGPA: 8.7\nSubject: Internet & Web Programming\n") . "\n";
echo "\n-- File Contents After Append --\n";
echo readFile($file);

// File info
echo "\n-- File Metadata --\n";
fileInfo($file);

// Line-by-line read
echo "\n-- Line by Line Read --\n";
$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $i => $line) {
    printf("  Line %d: %s\n", $i+1, $line);
}

// Cleanup
if (file_exists($file)) unlink($file);
echo "\n[OK]  Temp file deleted.\n";
echo str_repeat("=", 52) . "\n";
echo "  Completed: " . date("d-m-Y H:i:s") . "\n";
echo str_repeat("=", 52) . "\n";
?>
