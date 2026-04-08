<?php
// ============================================================
// Lab Programme 9: PHP with XML Processing
// Author : Rishav Raj | 23MEI10002
// Subject: Internet and Web Programming (CSE4001)
// ============================================================

$xmlFile = "students.xml";

// ── 1. Create / Update XML ───────────────────────────────
function addStudentXML($file, $name, $roll, $branch, $cgpa) {
    if (file_exists($file)) {
        $xml = simplexml_load_file($file);
    } else {
        $xml = new SimpleXMLElement('<students></students>');
    }
    $s = $xml->addChild('student');
    $s->addChild('name',   htmlspecialchars($name));
    $s->addChild('roll',   htmlspecialchars($roll));
    $s->addChild('branch', $branch);
    $s->addChild('cgpa',   $cgpa);
    $s->addChild('added',  date('d-m-Y H:i:s'));

    // Pretty-print using DOMDocument
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput       = true;
    $dom->loadXML($xml->asXML());
    $dom->save($file);
    echo "  [XML] Added: $name ($roll)\n";
}

// ── 2. Parse XML with SimpleXML ──────────────────────────
function displayXMLSimple($file) {
    if (!file_exists($file)) { echo "  File not found.\n"; return; }
    $xml = simplexml_load_file($file);
    printf("  %-18s %-14s %-8s %-5s\n","Name","Roll","Branch","CGPA");
    echo "  " . str_repeat("-",50) . "\n";
    foreach ($xml->student as $s) {
        printf("  %-18s %-14s %-8s %-5s\n",
            $s->name, $s->roll, $s->branch, $s->cgpa);
    }
}

// ── 3. Parse XML with DOM ────────────────────────────────
function displayXMLDOM($file) {
    if (!file_exists($file)) return;
    $dom  = new DOMDocument();
    $dom->load($file);
    $students = $dom->getElementsByTagName('student');
    echo "  Total students (DOM): " . $students->length . "\n";
    foreach ($students as $s) {
        $name = $s->getElementsByTagName('name')->item(0)->nodeValue;
        $roll = $s->getElementsByTagName('roll')->item(0)->nodeValue;
        $cgpa = $s->getElementsByTagName('cgpa')->item(0)->nodeValue;
        echo "  $name | $roll | CGPA: $cgpa\n";
    }
}

// ── Demo ──────────────────────────────────────────────────
if (file_exists($xmlFile)) unlink($xmlFile);

echo str_repeat("=", 52) . "\n";
echo "  PHP WITH XML PROCESSING\n";
echo "  Rishav Raj | 23MEI10002\n";
echo str_repeat("=", 52) . "\n\n";

echo "-- ADDING STUDENTS TO XML --\n";
addStudentXML($xmlFile,"Rishav Raj","23MEI10002","MEI",8.7);
addStudentXML($xmlFile,"Aarav Sharma","23MEI10003","MEI",9.1);
addStudentXML($xmlFile,"Priya Mehta","23MEI10004","CSE",8.3);
addStudentXML($xmlFile,"Rohit Kumar","23MEI10005","ECE",7.8);

echo "\n-- DISPLAY (SimpleXML) --\n";
displayXMLSimple($xmlFile);

echo "\n-- DISPLAY (DOMDocument) --\n";
displayXMLDOM($xmlFile);

echo "\n-- XML FILE CONTENTS --\n";
echo file_get_contents($xmlFile);

if (file_exists($xmlFile)) unlink($xmlFile);
echo "\n" . str_repeat("=", 52) . "\n";
echo "  Completed: " . date("d-m-Y H:i:s") . "\n";
echo str_repeat("=", 52) . "\n";
?>
