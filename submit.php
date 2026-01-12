<?php
// ======================
// 1. Database Connection
// ======================
$servername = "sql200.infinityfree.com";   // change to your DB server
$username   = "if0_39853898";              // change to your DB username
$password   = "miniteam8";                 // change to your DB password
$database   = "if0_39853898_team8";        // change to your DB name

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// ======================
// 2. Generate Unique Issue ID
// ======================
$issueID = "CPRRS" . time() . rand(100, 999);

// ======================
// 3. Collect Form Data
// ======================
$issueType    = $_POST['issueType'];
$category     = $_POST['category'];
$issueDate    = $_POST['issueDate'];
$priorityFlag = isset($_POST['priorityFlag']) ? 1 : 0;
$description  = $_POST['description'];
$landmark     = $_POST['landmark'];
$location     = $_POST['location'];
$contact      = $_POST['contact'];   // mobile number
$email        = $_POST['email'];     // email address
$reporterName = $_POST['reporterName'];

// ======================
// 4. Handle Image Uploads
// ======================
$uploadedFiles = [];
if (!empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['name'] as $key => $name) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . "_" . basename($name);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $targetFilePath)) {
            $uploadedFiles[] = $fileName;
        }
    }
}
$images = implode(",", $uploadedFiles);

// ======================
// 5. Insert Into Database
// ======================
$stmt = $conn->prepare("INSERT INTO reports 
    (issue_id, issueType, category, issueDate, priorityFlag, description, landmark, location, contact, email, reporterName, images, created_at, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Pending')");

$stmt->bind_param("ssssisssssss", $issueID, $issueType, $category, $issueDate, $priorityFlag, $description, $landmark, $location, $contact, $email, $reporterName, $images);

if ($stmt->execute()) {
    // ======================
    // 6. Send Email Notification
    // ======================
    $subject = "CiviQ Report Submitted - Issue ID: $issueID";
    $message = "Dear $reporterName,\n\nYour issue has been successfully submitted.\n\nIssue ID: $issueID\n\nWe will notify you about updates.\n\n- CiviQ Team";
    $headers = "From: noreply@yourdomain.com";

    // NOTE: On InfinityFree, mail() may not work. Use PHPMailer + SMTP if it fails.
    @mail($email, $subject, $message, $headers);

    // ======================
    // 7. Send SMS Notification (Fast2SMS Example)
    // ======================
    $apiKey = "MqLe6DrKlncTASjfBoI1P3WGs9p8uv0FxJhRamQyd7zwbUZO42TbiADfkZGU6wJWIea9hRYg0ScPunNt"; // replace with your real API key
    $smsMessage = "Your issue has been submitted. Issue ID: $issueID - CiviQ";

    $data = [
        "sender_id" => "CIVIQ",
        "message"   => $smsMessage,
        "language"  => "english",
        "route"     => "v3",
        "numbers"   => $contact,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.fast2sms.com/dev/bulkV2");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "authorization: $apiKey",
        "content-type: application/json"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    // ======================
    // 8. Success Response
    // ======================
    echo "<script>
            alert('Report submitted successfully! Your Issue ID is: $issueID');
            window.location.href = 'track.php';
          </script>";
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
