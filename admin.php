<?php
session_start();

// Database connection
$servername = "sql200.infinityfree.com";
$username   = "if0_39853898";
$password   = "miniteam8";
$database   = "if0_39853898_team8";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: log.php");
    exit();
}
$adminName = $_SESSION['admin']; // Example: store admin username in session

// Initialize
$report = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tracking_id = $conn->real_escape_string($_POST['tracking_id']);
    $sql = "SELECT * FROM reports WHERE issue_id = '$tracking_id' LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $report = $result->fetch_assoc();
    } else {
        $error = "No report found with Tracking ID: " . htmlspecialchars($tracking_id);
    }
}

// Fetch counts for admin stats
$totalReports = $conn->query("SELECT COUNT(*) AS total FROM reports")->fetch_assoc()['total'];
$pending      = $conn->query("SELECT COUNT(*) AS total FROM reports WHERE status='pending'")->fetch_assoc()['total'];
$visited      = $conn->query("SELECT COUNT(*) AS total FROM reports WHERE status='visited'")->fetch_assoc()['total'];
$inprogress   = $conn->query("SELECT COUNT(*) AS total FROM reports WHERE status='inprogress'")->fetch_assoc()['total'];
$solved       = $conn->query("SELECT COUNT(*) AS total FROM reports WHERE status='solved'")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin Dashboard - CiviQ</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="serv.css" rel="stylesheet">
    <style>
        .stat-card {
            border-radius: 1rem;
            padding: 20px;
            text-align: center;
            color: #fff;
        }
        .bg-pending { background-color: #ffc107; }
        .bg-visited { background-color: #17a2b8; }
        .bg-inprogress { background-color: #007bff; }
        .bg-solved { background-color: #28a745; }
        .bg-total { background-color: #6f42c1; }
    </style>
</head>

<body>
    <!-- Navbar -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-light px-3 px-lg-4 py-1 sticky-top shadow bg-white">
            <a href="#" class="navbar-brand p-0">
                <img src="img/logo.png" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="admin.php" class="nav-item nav-link active">Dashboard</a>
                    <a href="reportsadmin.php" class="nav-item nav-link">Reports</a>
                    <a href="user.php" class="nav-item nav-link">Users</a>
                </div>
                <a href="logout.php" class="btn btn-custom rounded-pill py-2 px-4">Logout</a>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Admin Welcome Section -->
<div class="container" style="margin-top: 200px;">
        <div class="card shadow rounded-4 p-4 text-center">
            <h2 class="fw-bold">👋 Welcome, <?php echo htmlspecialchars($adminName); ?>!</h2>
            <p class="text-muted">Here you can manage reports, track progress, and monitor community issues.</p>
        </div>
    </div>

    <!-- Admin Stats Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Dashboard Overview</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 col-lg-2">
                <div class="stat-card bg-total shadow">
                    <h4>Total Reports</h4>
                    <h2><?php echo $totalReports; ?></h2>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="stat-card bg-pending shadow">
                    <h4>Pending</h4>
                    <h2><?php echo $pending; ?></h2>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="stat-card bg-visited shadow">
                    <h4>Visited</h4>
                    <h2><?php echo $visited; ?></h2>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="stat-card bg-inprogress shadow">
                    <h4>In Progress</h4>
                    <h2><?php echo $inprogress; ?></h2>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="stat-card bg-solved shadow">
                    <h4>Solved</h4>
                    <h2><?php echo $solved; ?></h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Admin Stats End -->

    <!-- Back to Top -->
<a href="#" class="btn btn-lg btn-lg-square back-to-top" style="background-color: #86B817; color: white;">
    <i class="bi bi-arrow-up"></i>
</a>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
