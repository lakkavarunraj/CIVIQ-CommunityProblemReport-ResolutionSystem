<?php
// Database connection
$servername = "sql200.infinityfree.com";
$username   = "if0_39853898";
$password   = "miniteam8";
$database   = "if0_39853898_team8";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}

// Initialize
$report = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $issue_id = $conn->real_escape_string($_POST['issue_id']);
    $sql = "SELECT * FROM reports WHERE issue_id = '$issue_id' LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $report = $result->fetch_assoc();
    } else {
        $error = "No report found with Issue ID: " . htmlspecialchars($issue_id);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard - CiviQ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #86B817; /* Green Theme */
        }

        /* Status Tracker */
        .status-tracker {
            display: flex;
            justify-content: space-between;
            margin: 50px 0;
            position: relative;
        }
        .status-tracker::before {
            content: "";
            position: absolute;
            top: 22px;
            left: 0;
            width: 100%;
            height: 5px;
            background: #ddd;
            z-index: 1;
            border-radius: 5px;
        }
        .status-step {
            position: relative;
            text-align: center;
            flex: 1;
            font-weight: 600;
            color: #555;
            z-index: 2;
        }
        .status-step::before {
            content: "";
            display: block;
            margin: 0 auto 10px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ddd;
            border: 4px solid #ddd;
            z-index: 2;
            position: relative;
        }
        .status-step.active::before {
            background: var(--primary);
            border-color: var(--primary);
        }
        .status-step.active {
            color: var(--primary);
        }

        /* Progress Line */
        .progress-line {
            content: "";
            position: absolute;
            top: 22px;
            left: 0;
            height: 5px;
            background: var(--primary);
            z-index: 1;
            border-radius: 5px;
            transition: width 0.7s ease-in-out;
        }

        /* Issue Image */
        .issue-img {
            max-width: 500px;
            max-height: 500px;
            border-radius: 10px;
            object-fit: cover;
        }

        /* Back To Top */
        .back-to-top {
            background: var(--primary);
            border: none;
        }
        .back-to-top:hover {
            background: #6ca212;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="container-fluid position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0 sticky-top shadow bg-white">
        <a href="#" class="navbar-brand p-0">
            <img src="img/logo.png" alt="Logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
              <a href="home.html" class="nav-item nav-link">Home</a>
              <a href="about.html" class="nav-item nav-link">About</a>
              <a href="report.html" class="nav-item nav-link active">Report</a>
              <a href="tracking.php" class="nav-item nav-link">Tracking</a>
              <a href="contact.html" class="nav-item nav-link">Contact</a>
            </div>
            <a href="profile.php" class="btn btn-custom rounded-pill py-2 px-4">Profile</a>
        </div>
    </nav>
</div>

<!-- Tracking Section -->
<div class="container py-5">
    <h2 class="mb-4 text-center">Track Your Report</h2>

    <!-- Tracking Form -->
    <form method="POST" class="d-flex justify-content-center mb-4">
        <input type="text" name="issue_id" class="form-control w-50 me-2" placeholder="Enter Issue ID" required>
        <button type="submit" class="btn btn-custom" style="background: var(--primary); color:white;">Track</button>
    </form>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger text-center">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if ($report): ?>
        <div class="card shadow p-4">
            <h4 class="mb-3">Report Details</h4>
            <p><strong>Issue ID:</strong> <?php echo htmlspecialchars($report['issue_id']); ?></p>
            <p><strong>Issue Type:</strong> <?php echo htmlspecialchars($report['issueType']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($report['description']); ?></p>
            <p><strong>Date Reported:</strong> <?php echo htmlspecialchars($report['created_at']); ?></p>

            <div class="row mt-3">
                <div class="col-md-6">
                    <h5>User Uploaded Image:</h5>
                    <?php if (!empty($report['images'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($report['images']); ?>" class="issue-img">
                    <?php else: ?>
                        <p class="text-muted">No image uploaded</p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <h5>Solved Image:</h5>
                    <?php if (!empty($report['solved_image'])): ?>
                        <img src="uploads/solved/<?php echo htmlspecialchars($report['solved_image']); ?>" class="issue-img">
                    <?php else: ?>
                        <p class="text-muted">Not available yet</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Horizontal Status Tracker -->
            <div class="status-tracker mt-4">
                <?php
                    $statuses = ["pending", "visited", "inprogress", "solved"];
                    $currentStatus = array_search($report['status'], $statuses);
                    if ($currentStatus === false) $currentStatus = 0;
                    $progressWidth = ($currentStatus / (count($statuses) - 1)) * 100;
                ?>
                <div class="progress-line" style="width: <?php echo $progressWidth; ?>%;"></div>
                <div class="status-step <?php echo ($currentStatus >= 0) ? 'active' : ''; ?>">Pending</div>
                <div class="status-step <?php echo ($currentStatus >= 1) ? 'active' : ''; ?>">Visited</div>
                <div class="status-step <?php echo ($currentStatus >= 2) ? 'active' : ''; ?>">In Progress</div>
                <div class="status-step <?php echo ($currentStatus >= 3) ? 'active' : ''; ?>">Solved</div>
            </div>
        </div>
    <?php endif; ?>
</div>

<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
    <i class="bi bi-arrow-up"></i>
</a>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
