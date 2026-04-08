<?php
// ============================================================
// Lab Programme 8: Cookies Implementation
// Author : Rishav Raj | 23MEI10002
// Subject: Internet and Web Programming (CSE4001)
// ============================================================

// ── 1. Set Cookies ───────────────────────────────────────
function setCookies() {
    $expiry = time() + (7 * 24 * 3600); // 7 days
    setcookie("username",   "Rishav Raj",    $expiry, "/");
    setcookie("theme",      "dark",          $expiry, "/");
    setcookie("language",   "en",            $expiry, "/");
    setcookie("last_visit", date("d-m-Y"),   $expiry, "/");
    echo "  [SET] Cookies set (expire in 7 days).\n";
}

// ── 2. Read Cookies ──────────────────────────────────────
function readCookies() {
    $keys = ["username", "theme", "language", "last_visit"];
    foreach ($keys as $k) {
        $val = isset($_COOKIE[$k]) ? $_COOKIE[$k] : "(not set)";
        printf("  %-14s => %s\n", $k, $val);
    }
}

// ── 3. Update Cookie ─────────────────────────────────────
function updateCookie($key, $value) {
    $expiry = time() + (7 * 24 * 3600);
    setcookie($key, $value, $expiry, "/");
    echo "  [UPDATE] Cookie '$key' updated to '$value'.\n";
}

// ── 4. Delete Cookie ─────────────────────────────────────
function deleteCookie($key) {
    setcookie($key, "", time() - 3600, "/");
    unset($_COOKIE[$key]);
    echo "  [DEL] Cookie '$key' deleted.\n";
}

// ── Demo (CLI Simulation) ─────────────────────────────────
echo str_repeat("=", 52) . "\n";
echo "  COOKIES IMPLEMENTATION\n";
echo "  Rishav Raj | 23MEI10002\n";
echo str_repeat("=", 52) . "\n\n";

echo "-- SETTING COOKIES --\n";
setCookies();
// Simulate cookies being present (CLI doesn't have $_COOKIE)
$_COOKIE = [
    "username"   => "Rishav Raj",
    "theme"      => "dark",
    "language"   => "en",
    "last_visit" => date("d-m-Y"),
];

echo "\n-- READING COOKIES --\n";
readCookies();

echo "\n-- UPDATING COOKIE --\n";
updateCookie("theme", "light");
$_COOKIE["theme"] = "light";

echo "\n-- COOKIES AFTER UPDATE --\n";
readCookies();

echo "\n-- DELETING COOKIE --\n";
deleteCookie("language");

echo "\n-- COOKIES AFTER DELETE --\n";
readCookies();

echo "\n-- COOKIE EXPIRY INFO --\n";
echo "  Standard expiry : 7 days = " . (7 * 24 * 3600) . " seconds\n";
echo "  Session cookie  : expires when browser closes (expiry=0)\n";
echo "  Delete trick    : set expiry to past time: time()-3600\n";

echo "\n" . str_repeat("=", 52) . "\n";
echo "  Completed: " . date("d-m-Y H:i:s") . "\n";
echo str_repeat("=", 52) . "\n";
?>
