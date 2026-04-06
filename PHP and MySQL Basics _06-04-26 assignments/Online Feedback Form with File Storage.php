<?php
// ============================================================
// Assignment 2: Online Feedback Form with File Storage
// Author  : RISHAV RAJ  |  23MEI10002
// Covers  : Form Handling, File Handling, Functions
// ============================================================

$feedbackFile = "feedback.txt";
$message      = "";
$error        = "";

// ---------- 1. Validation Functions ----------
function validateName($name) {
    $name = trim($name);
    if (empty($name))             return "Name cannot be empty.";
    if (strlen($name) < 2)        return "Name must be at least 2 characters.";
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) return "Name must contain only letters.";
    return "";
}

function validateEmail($email) {
    $email = trim($email);
    if (empty($email))            return "Email cannot be empty.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Invalid email format.";
    return "";
}

function validateFeedback($feedback) {
    $feedback = trim($feedback);
    if (empty($feedback))         return "Feedback message cannot be empty.";
    if (strlen($feedback) < 10)   return "Feedback must be at least 10 characters.";
    return "";
}

function validateRating($rating) {
    if (!is_numeric($rating) || $rating < 1 || $rating > 5)
        return "Rating must be between 1 and 5.";
    return "";
}

// ---------- 2. Store Feedback to File ----------
function storeFeedback($file, $name, $email, $feedback, $rating) {
    $timestamp = date("d-m-Y H:i:s");
    $entry  = "--------------------------------------------\n";
    $entry .= "Date     : $timestamp\n";
    $entry .= "Name     : $name\n";
    $entry .= "Email    : $email\n";
    $entry .= "Rating   : $rating/5\n";
    $entry .= "Feedback : $feedback\n";
    $entry .= "--------------------------------------------\n\n";
    // Append mode — does not overwrite existing data
    file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
}

// ---------- 3. Display All Stored Feedback ----------
function displayFeedback($file) {
    if (!file_exists($file) || filesize($file) === 0) {
        echo "  No feedback records found.\n";
        return;
    }
    $contents = file_get_contents($file);
    echo $contents;
}

// ---------- 4. Simulate a Form Submission (CLI mode) ----------
// Simulated POST data
$formData = [
    ["name" => "Aarav Sharma", "email" => "aarav@email.com",  "feedback" => "The course was very well structured and informative.", "rating" => 5],
    ["name" => "Priya Mehta",  "email" => "priya@email.com",  "feedback" => "Good content but could use more examples.",            "rating" => 4],
    ["name" => "Rohit Kumar",  "email" => "rohit@email.com",  "feedback" => "Average experience, needs improvement in labs.",       "rating" => 3],
];

echo str_repeat("=", 60) . "\n";
echo "       ONLINE FEEDBACK FORM - SUBMISSION LOG\n";
echo "       Author: RISHAV RAJ | 23MEI10002\n";
echo str_repeat("=", 60) . "\n\n";

foreach ($formData as $data) {
    $nameErr     = validateName($data['name']);
    $emailErr    = validateEmail($data['email']);
    $feedbackErr = validateFeedback($data['feedback']);
    $ratingErr   = validateRating($data['rating']);

    if ($nameErr || $emailErr || $feedbackErr || $ratingErr) {
        echo "Validation Error for " . $data['name'] . ":\n";
        echo "  " . ($nameErr     ?: "") . "\n";
        echo "  " . ($emailErr    ?: "") . "\n";
        echo "  " . ($feedbackErr ?: "") . "\n";
        echo "  " . ($ratingErr   ?: "") . "\n";
    } else {
        storeFeedback($feedbackFile,
            $data['name'], $data['email'],
            $data['feedback'], $data['rating']);
        echo "  [OK] Feedback from " . $data['name'] . " saved successfully.\n";
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "           ALL STORED FEEDBACK RECORDS\n";
echo str_repeat("=", 60) . "\n\n";
displayFeedback($feedbackFile);

// Cleanup for repeated runs
// unlink($feedbackFile);
?>
