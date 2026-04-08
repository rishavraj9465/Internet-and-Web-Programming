<?php
// ============================================================
// Lab Programme 6: MySQL Database Creation and Connectivity
// Author : Rishav Raj | 23MEI10002
// Subject: Internet and Web Programming (CSE4001)
// ============================================================

// ── DB Config ────────────────────────────────────────────
$host = "localhost"; $user = "root"; $pass = ""; $dbName = "lab_db";

// ── Connect ──────────────────────────────────────────────
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ── Create Database ───────────────────────────────────────
$conn->query("CREATE DATABASE IF NOT EXISTS $dbName");
$conn->select_db($dbName);
echo "[OK]  Database '$dbName' ready.\n";

// ── Create Table ─────────────────────────────────────────
$conn->query("DROP TABLE IF EXISTS students");
$conn->query("CREATE TABLE students (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(100) NOT NULL,
    roll     VARCHAR(20)  NOT NULL UNIQUE,
    branch   VARCHAR(50)  NOT NULL,
    cgpa     DECIMAL(4,2) NOT NULL,
    created  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
echo "[OK]  Table 'students' created.\n";

// ── INSERT ────────────────────────────────────────────────
$records = [
    ["Rishav Raj",    "23MEI10002", "MEI",  8.7],
    ["Aarav Sharma",  "23MEI10003", "MEI",  9.1],
    ["Priya Mehta",   "23MEI10004", "CSE",  8.3],
    ["Rohit Kumar",   "23MEI10005", "ECE",  7.8],
    ["Sneha Patel",   "23MEI10006", "IT",   8.9],
];
$stmt = $conn->prepare("INSERT INTO students (name,roll,branch,cgpa) VALUES(?,?,?,?)");
$stmt->bind_param("sssd", $n, $r, $b, $c);
foreach ($records as [$n, $r, $b, $c]) { $stmt->execute(); }
echo "[OK]  " . count($records) . " records inserted.\n";

// ── UPDATE ────────────────────────────────────────────────
$conn->query("UPDATE students SET cgpa=9.2 WHERE roll='23MEI10002'");
echo "[OK]  Updated CGPA for Rishav Raj.\n";

// ── DELETE ────────────────────────────────────────────────
$conn->query("DELETE FROM students WHERE roll='23MEI10006'");
echo "[OK]  Deleted record: Sneha Patel.\n";

// ── SELECT & DISPLAY ──────────────────────────────────────
$result = $conn->query("SELECT id,name,roll,branch,cgpa FROM students ORDER BY id");
echo "\n-- STUDENT RECORDS --\n";
printf("%-4s %-18s %-14s %-8s %-5s\n","ID","Name","Roll","Branch","CGPA");
echo str_repeat("-",55) . "\n";
while ($row = $result->fetch_assoc()) {
    printf("%-4d %-18s %-14s %-8s %-5s\n",
        $row['id'], $row['name'], $row['roll'], $row['branch'], $row['cgpa']);
}
echo str_repeat("=",55) . "\n";
$conn->close();
echo "Completed: " . date("d-m-Y H:i:s") . "\n";
?>
