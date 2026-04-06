<?php
// ============================================================
// Assignment 1: User Registration & Login System
// Author  : RISHAV RAJ  |  23MEI10002
// Covers  : MySQL Basics, PHP-MySQL Connectivity,
//           Sessions & Cookies
// ============================================================

// ── DB Configuration ──────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'user_auth_db');

// ── Connect to MySQL ──────────────────────────────────────
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// ── Register User ─────────────────────────────────────────
function registerUser($name, $email, $password) {
    $conn     = getConnection();
    $name     = htmlspecialchars(trim($name));
    $email    = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    $passHash = hash('sha256', $password);

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $check->close(); $conn->close();
        return ["status" => false, "msg" => "Email already registered."];
    }
    $check->close();

    $stmt = $conn->prepare(
        "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $name, $email, $passHash);
    if ($stmt->execute()) {
        $stmt->close(); $conn->close();
        return ["status" => true, "msg" => "Registration successful!"];
    }
    $stmt->close(); $conn->close();
    return ["status" => false, "msg" => "Registration failed."];
}

// ── Login User ────────────────────────────────────────────
function loginUser($email, $password, $rememberMe = false) {
    session_start();
    $conn     = getConnection();
    $email    = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    $passHash = hash('sha256', $password);

    $stmt = $conn->prepare(
        "SELECT id, name, email FROM users WHERE email = ? AND password = ?"
    );
    $stmt->bind_param("ss", $email, $passHash);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['logged_in'] = true;
        if ($rememberMe) {
            setcookie('remember_email', $email, time() + (7 * 24 * 3600), '/');
        }
        $stmt->close(); $conn->close();
        return ["status" => true,  "msg" => "Login successful! Welcome, " . $user['name']];
    }
    $stmt->close(); $conn->close();
    return ["status" => false, "msg" => "Invalid email or password."];
}

// ── Dashboard Guard ───────────────────────────────────────
function checkSession() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        return ["status" => false, "msg" => "Access denied. Please login first."];
    }
    return ["status" => true,  "msg" => "Welcome to Dashboard, " . $_SESSION['user_name'] . "!"];
}

// ── Logout ────────────────────────────────────────────────
function logoutUser() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION = [];
    session_destroy();
    if (isset($_COOKIE['remember_email'])) {
        setcookie('remember_email', '', time() - 3600, '/');
    }
    return ["status" => true, "msg" => "Logged out successfully."];
}

// ── CLI Demo ──────────────────────────────────────────────
echo str_repeat("=", 60) . "\n";
echo "     USER REGISTRATION & LOGIN SYSTEM\n";
echo "     Author: RISHAV RAJ | 23MEI10002\n";
echo str_repeat("=", 60) . "\n\n";

echo "-- REGISTRATION --\n";
$r1 = registerUser("Sneha Patel", "sneha@example.com", "sneha@789");
echo "  " . ($r1['status'] ? "[OK] " : "[ERR]") . " " . $r1['msg'] . "\n";
$r2 = registerUser("Aarav Sharma", "aarav@example.com", "pass1234");
echo "  " . ($r2['status'] ? "[OK] " : "[ERR]") . " " . $r2['msg'] . "\n";

echo "\n-- LOGIN --\n";
$l1 = loginUser("sneha@example.com", "sneha@789", true);
echo "  " . ($l1['status'] ? "[OK] " : "[ERR]") . " " . $l1['msg'] . "\n";
$l2 = loginUser("sneha@example.com", "wrongpass");
echo "  " . ($l2['status'] ? "[OK] " : "[ERR]") . " " . $l2['msg'] . "\n";

echo "\n-- DASHBOARD ACCESS --\n";
$s = checkSession();
echo "  " . ($s['status'] ? "[OK] " : "[ERR]") . " " . $s['msg'] . "\n";

echo "\n-- LOGOUT --\n";
$lo = logoutUser();
echo "  " . ($lo['status'] ? "[OK] " : "[ERR]") . " " . $lo['msg'] . "\n";

echo "\n" . str_repeat("=", 60) . "\n";
echo "  Generated: " . date("d-m-Y H:i:s") . "\n";
echo str_repeat("=", 60) . "\n";
?>
