<?php
// update.php — handles form submission from admin.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['issue_id']) || !isset($_POST['status'])) {
        die("❌ Missing required data.");
    }

    $issueId   = $_POST['issue_id'];
    $newStatus = $_POST['status'];

    // Database connection
    $servername = "sql200.infinityfree.com";
    $username   = "if0_39853898";
    $password   = "miniteam8";
    $database   = "if0_39853898_team8";

    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("❌ Database connection failed: " . $conn->connect_error);
    }

    // Handle solved image upload if provided
    $solvedImageName = null;
    if (isset($_FILES['solved_image']) && $_FILES['solved_image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/solved/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['solved_image']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['solved_image']['tmp_name'], $targetFile)) {
            $solvedImageName = $fileName;
        }
    }

    // Build SQL dynamically (because solved_image may or may not be uploaded)
    if ($solvedImageName) {
        $stmt = $conn->prepare("UPDATE reports SET status = ?, solved_image = ? WHERE issue_id = ?");
        $stmt->bind_param("sss", $newStatus, $solvedImageName, $issueId);
    } else {
        $stmt = $conn->prepare("UPDATE reports SET status = ? WHERE issue_id = ?");
        $stmt->bind_param("ss", $newStatus, $issueId);
    }

if ($stmt->execute()) {
    header("Location: admin.php?success=1");
        exit();
    } else {
        echo "❌ Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "❌ Invalid request.";
}
?>
