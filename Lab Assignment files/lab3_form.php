<?php
// ============================================================
// Lab Programme 3: Form Handling and Validation
// Author : Rishav Raj | 23MEI10002
// Subject: Internet and Web Programming (CSE4001)
// ============================================================

// ── Validation Functions ──────────────────────────────────
function validateName($name) {
    $name = trim($name);
    if (empty($name))                         return ["", "Name is required."];
    if (strlen($name) < 2)                    return ["", "Name must be >= 2 chars."];
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) return ["", "Name: letters only."];
    return [htmlspecialchars($name), ""];
}

function validateEmail($email) {
    $email = trim($email);
    if (empty($email))                              return ["", "Email is required."];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return ["", "Invalid email format."];
    return [htmlspecialchars(strtolower($email)), ""];
}

function validateMobile($mobile) {
    $mobile = trim($mobile);
    if (empty($mobile))                          return ["", "Mobile is required."];
    if (!preg_match("/^[6-9][0-9]{9}$/", $mobile)) return ["", "Mobile: 10 digits, start 6-9."];
    return [$mobile, ""];
}

// ── Process Submission ────────────────────────────────────
function processForm($name, $email, $mobile) {
    echo "\n  -- Processing Form --\n";
    [$cName,   $eN] = validateName($name);
    [$cEmail,  $eE] = validateEmail($email);
    [$cMobile, $eM] = validateMobile($mobile);

    $errors = array_filter([$eN, $eE, $eM]);
    if (!empty($errors)) {
        echo "  [ERRORS]\n";
        foreach ($errors as $err) echo "    ! $err\n";
        return;
    }
    echo "  [SUCCESS] Form data is valid.\n";
    echo "  Sanitized Name   : $cName\n";
    echo "  Sanitized Email  : $cEmail\n";
    echo "  Sanitized Mobile : $cMobile\n";
}

// ── CLI Simulation ────────────────────────────────────────
echo str_repeat("=", 55) . "\n";
echo "  FORM HANDLING AND VALIDATION\n";
echo "  Rishav Raj | 23MEI10002\n";
echo str_repeat("=", 55) . "\n";

echo "\nTest 1 — Valid data:";
processForm("Rishav Raj", "rishav@example.com", "9876543210");

echo "\nTest 2 — Empty name:";
processForm("", "rishav@example.com", "9876543210");

echo "\nTest 3 — Invalid email:";
processForm("Rishav Raj", "not-an-email", "9876543210");

echo "\nTest 4 — Invalid mobile:";
processForm("Rishav Raj", "rishav@example.com", "12345");

echo "\nTest 5 — All invalid:";
processForm("", "bad-email", "000");

echo "\n" . str_repeat("=", 55) . "\n";
echo "  Completed: " . date("d-m-Y H:i:s") . "\n";
echo str_repeat("=", 55) . "\n";
?>
