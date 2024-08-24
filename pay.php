<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Retrieve and sanitize form inputs
    $firstName = sanitizeInput($_POST["first-name"]);
    $middleName = sanitizeInput($_POST["middle-name"]);
    $lastName = sanitizeInput($_POST["last-name"]);
    $collegeName = sanitizeInput($_POST["collegename"]);
    $mobileNumber = sanitizeInput($_POST["mobileno"]);
    $gender = sanitizeInput($_POST["gender"]);
    $department = sanitizeInput($_POST["department"]);
    $event = sanitizeInput($_POST["event"]); // This will always be "Codnova"
    $upiID = sanitizeInput($_POST["upi-id"]); // Retrieve UPI ID

    // Validate inputs
    $errors = [];

    if (!preg_match("/^[A-Za-z]+$/", $firstName)) {
        $errors[] = "First name must only contain alphabets.";
    }

    if ($middleName && !preg_match("/^[A-Za-z]+$/", $middleName)) {
        $errors[] = "Middle name must only contain alphabets.";
    }

    if (!preg_match("/^[A-Za-z]+$/", $lastName)) {
        $errors[] = "Last name must only contain alphabets.";
    }

    if (!preg_match("/^[A-Za-z\s.]+$/", $collegeName)) {
        $errors[] = "College name must only contain alphabets, dots, and spaces.";
    }

    if (!preg_match("/^\d{10}$/", $mobileNumber)) {
        $errors[] = "Mobile number must be exactly 10 digits.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    } else {
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "payment";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO registrations (first_name, middle_name, last_name, college_name, mobile_number, gender, department, event, upi_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $firstName, $middleName, $lastName, $collegeName, $mobileNumber, $gender, $department, $event, $upiID);

        if ($stmt->execute()) {
            $uniqueNumber = "teccivilcadnumber" . $conn->insert_id;
            $fullName = $firstName . " " . $middleName . " " . $lastName;
            $eventDate = "2025-02-01";
            $eventDateTime = date("Y-m-d H:i:s");

            include(__DIR__ . '/qr_code/qrlib.php');

            $qrContent = "Unique Number: $uniqueNumber\nName: $fullName\nEvent: $event\nDate of Event: $eventDate\nRegistration Date & Time: $eventDateTime";

            $qrDir = __DIR__ . '/qr_codes/';
            if (!file_exists($qrDir)) {
                mkdir($qrDir, 0777, true);
            }
            $qrFile = 'qr_' . $uniqueNumber . '.png';
            $qrFilePath = $qrDir . $qrFile;

            if (QRcode::png($qrContent, $qrFilePath, 'L', 4, 2)) {
                $imageUrl = 'qr_codes/' . $qrFile;

                echo "<p>Form submitted successfully!</p>";
                echo "<p><img src='" . $imageUrl . "' alt='QR Code'></p>";
            } else {
                echo "<p style='color: red;'>Error generating QR code.</p>";
            }
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
        $conn->close();
    }
} else {
    echo "<p style='color: red;'>Invalid request method.</p>";
}
?>
