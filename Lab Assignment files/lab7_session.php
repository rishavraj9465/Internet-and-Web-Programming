<?php
// ============================================================
// Lab Programme 7: Session-Based Login System
// Author : Rishav Raj | 23MEI10002
// Subject: Internet and Web Programming (CSE4001)
// ============================================================
session_start();
$host="localhost"; $user="root"; $pass=""; $db="lab_db";

function getConn($host,$user,$pass,$db) {
    $c = new mysqli($host,$user,$pass,$db);
    if ($c->connect_error) die("DB Error: ".$c->connect_error);
    return $c;
}

function loginUser($email, $password) {
    global $host,$user,$pass,$db;
    $conn  = getConn($host,$user,$pass,$db);
    $hash  = hash('sha256', $password);
    $stmt  = $conn->prepare("SELECT id,name FROM users WHERE email=? AND password=?");
    $stmt->bind_param("ss",$email,$hash);
    $stmt->execute();
    $res   = $stmt->get_result();
    if ($res->num_rows === 1) {
        $u = $res->fetch_assoc();
        $_SESSION['uid']   = $u['id'];
        $_SESSION['uname'] = $u['name'];
        $_SESSION['login'] = true;
        $conn->close();
        return ["ok"=>true,"msg"=>"Login successful. Welcome, ".$u['name']];
    }
    $conn->close();
    return ["ok"=>false,"msg"=>"Invalid email or password."];
}

function checkAccess() {
    if (!isset($_SESSION['login']) || !$_SESSION['login'])
        return ["ok"=>false,"msg"=>"Access denied. Please login first."];
    return ["ok"=>true,"msg"=>"Welcome to Dashboard, ".$_SESSION['uname']."!"];
}

function logoutUser() {
    $_SESSION = []; session_destroy();
    return ["ok"=>true,"msg"=>"Logged out. Session destroyed."];
}

// ── Setup users table ─────────────────────────────────────
$conn = getConn($host,$user,$pass,$db);
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100), email VARCHAR(150) UNIQUE,
    password VARCHAR(255))");
$conn->query("INSERT IGNORE INTO users(name,email,password) VALUES
    ('Rishav Raj','rishav@lab.com',SHA2('pass123',256)),
    ('Aarav Sharma','aarav@lab.com',SHA2('aarav456',256))");
$conn->close();

// ── Demo ──────────────────────────────────────────────────
echo str_repeat("=",55)."\n";
echo "  SESSION-BASED LOGIN SYSTEM\n";
echo "  Rishav Raj | 23MEI10002\n";
echo str_repeat("=",55)."\n\n";

echo "-- LOGIN TESTS --\n";
$r1 = loginUser("rishav@lab.com","pass123");
echo "  ".($r1['ok']?"[OK] ":"[ERR]")." ".$r1['msg']."\n";

$r2 = loginUser("rishav@lab.com","wrongpass");
echo "  ".($r2['ok']?"[OK] ":"[ERR]")." ".$r2['msg']."\n";

echo "\n-- DASHBOARD ACCESS --\n";
$d = checkAccess();
echo "  ".($d['ok']?"[OK] ":"[ERR]")." ".$d['msg']."\n";

echo "\n-- LOGOUT --\n";
$lo = logoutUser();
echo "  ".($lo['ok']?"[OK] ":"[ERR]")." ".$lo['msg']."\n";

$d2 = checkAccess();
echo "  ".($d2['ok']?"[OK] ":"[ERR]")." ".$d2['msg']."\n";

echo "\n".str_repeat("=",55)."\n";
echo "  Completed: ".date("d-m-Y H:i:s")."\n";
echo str_repeat("=",55)."\n";
?>
