<?php
// ============================================================
// Assignment 2: Contact Form with Email and XML Storage
// Author  : RISHAV RAJ  |  23MEI10002
// Covers  : PHP E-mail, PHP & XML, Form Handling
// ============================================================

$xmlFile = "contacts.xml";

// ── Send Email Function ───────────────────────────────────
function sendContactEmail($to, $name, $email, $subject, $message) {
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";

    $body = "
    <html><body>
    <h3>New Contact Form Submission</h3>
    <p><b>Name:</b>    $name</p>
    <p><b>Email:</b>   $email</p>
    <p><b>Subject:</b> $subject</p>
    <p><b>Message:</b> $message</p>
    </body></html>";

    // mail() sends to the $to address
    $sent = mail($to, $subject, $body, $headers);
    return $sent
        ? ["status" => true,  "msg" => "Email sent to $to"]
        : ["status" => false, "msg" => "Email sending failed (check mail server)."];
}

// ── Store Contact in XML ──────────────────────────────────
function storeContactXML($file, $name, $email, $subject, $message) {
    // Load existing XML or create new structure
    if (file_exists($file)) {
        $xml = simplexml_load_file($file);
    } else {
        $xml = new SimpleXMLElement('<contacts></contacts>');
    }

    // Append new contact node
    $contact = $xml->addChild('contact');
    $contact->addChild('id',        count($xml->contact));
    $contact->addChild('name',      htmlspecialchars($name));
    $contact->addChild('email',     htmlspecialchars($email));
    $contact->addChild('subject',   htmlspecialchars($subject));
    $contact->addChild('message',   htmlspecialchars($message));
    $contact->addChild('timestamp', date('d-m-Y H:i:s'));

    // Format and save XML
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput       = true;
    $dom->loadXML($xml->asXML());
    $dom->save($file);

    return ["status" => true, "msg" => "Contact saved to XML."];
}

// ── Read & Display All Contacts from XML ──────────────────
function displayContactsXML($file) {
    if (!file_exists($file)) {
        echo "  No contacts found in XML.\n";
        return;
    }
    $xml = simplexml_load_file($file);
    if (count($xml->contact) === 0) {
        echo "  XML file is empty.\n";
        return;
    }
    printf("  %-4s %-16s %-22s %-20s\n", "ID", "Name", "Email", "Subject");
    echo "  " . str_repeat("-", 68) . "\n";
    foreach ($xml->contact as $c) {
        printf("  %-4s %-16s %-22s %-20s\n",
            $c->id, $c->name, $c->email, $c->subject);
    }
}

// ── Validate Inputs ───────────────────────────────────────
function validateContact($name, $email, $subject, $message) {
    if (empty(trim($name)))    return "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Invalid email.";
    if (empty(trim($subject))) return "Subject is required.";
    if (strlen(trim($message)) < 10) return "Message too short (min 10 chars).";
    return "";
}

// ── CLI Demo ──────────────────────────────────────────────
// Remove XML for fresh demo
if (file_exists($xmlFile)) unlink($xmlFile);

$submissions = [
    ["name" => "Aarav Sharma",  "email" => "aarav@example.com",
     "subject" => "Course Query", "message" => "I want to know about the upcoming PHP workshop."],
    ["name" => "Priya Mehta",   "email" => "priya@example.com",
     "subject" => "Feedback",    "message" => "The assignment structure is excellent and helpful."],
    ["name" => "Rohit Kumar",   "email" => "rohit@example.com",
     "subject" => "Support",     "message" => "Need help setting up the local PHP environment."],
];

echo str_repeat("=", 65) . "\n";
echo "     CONTACT FORM WITH EMAIL & XML STORAGE\n";
echo "     Author: RISHAV RAJ | 23MEI10002\n";
echo str_repeat("=", 65) . "\n\n";

foreach ($submissions as $sub) {
    $err = validateContact($sub['name'], $sub['email'], $sub['subject'], $sub['message']);
    if ($err) {
        echo "  [ERR] Validation: $err\n";
        continue;
    }
    // Store in XML
    $xml = storeContactXML($xmlFile, $sub['name'], $sub['email'], $sub['subject'], $sub['message']);
    echo "  [XML] " . $xml['msg'] . " — " . $sub['name'] . "\n";
    // Simulate email send (mail() needs a live mail server)
    echo "  [MAIL] Email triggered for: " . $sub['email'] . "\n";
}

echo "\n" . str_repeat("=", 65) . "\n";
echo "  ALL STORED CONTACTS FROM XML\n";
echo str_repeat("=", 65) . "\n";
displayContactsXML($xmlFile);

echo "\n  XML File Contents:\n";
echo str_repeat("-", 65) . "\n";
echo file_get_contents($xmlFile);
echo str_repeat("=", 65) . "\n";
echo "  Generated: " . date("d-m-Y H:i:s") . "\n";
echo str_repeat("=", 65) . "\n";
?>
