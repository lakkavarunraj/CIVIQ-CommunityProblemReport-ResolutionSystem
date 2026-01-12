<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: log.php");
    exit();
}

$email = $_SESSION['email'];

// Database connection
$servername = "sql200.infinityfree.com";
$username   = "if0_39853898";
$password   = "miniteam8";
$database   = "if0_39853898_team8";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}

// Fetch citizen details
$userSql = "SELECT * FROM citizen WHERE email = ?";
$stmt = $conn->prepare($userSql);
$stmt->bind_param("s", $email);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();

// Fetch reports submitted by this user
$reportSql = "SELECT * FROM reports WHERE email = ? ORDER BY created_at DESC";
$stmt2 = $conn->prepare($reportSql);
$stmt2->bind_param("s", $email);
$stmt2->execute();
$reports = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Profile - CiviQ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- External CSS -->
    <link href="serv.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<div class="container-fluid position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0 sticky-top shadow bg-white">
        <a href="#" class="navbar-brand p-0">
            <img src="img/logo.png" alt="Logo">
        </a>


        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
              <a href="home.html" class="nav-item nav-link">Home</a>
              <a href="about.html" class="nav-item nav-link">About</a>
              <a href="report.html" class="nav-item nav-link">Report</a>
              <a href="track.php" class="nav-item nav-link">Tracking</a>
              <a href="contact.html" class="nav-item nav-link">Contact</a>
            </div>
            <a href="pro.php" class="btn btn-custom rounded-pill py-2 px-4">Profile</a>
        </div>
    </nav>
</div>

<!-- Profile Section -->
<div class="container py-5 mt-5">
    <h2 class="text-center mb-4">My Profile</h2>

    <!-- User Info -->
    <div class="card shadow mb-4 p-4">
        <h4 class="mb-3">User Details</h4>
        <?php if ($user): ?>
            <p><strong>Citizen ID:</strong> <?= htmlspecialchars($user['citizen_id']); ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['firstname'] . " " . $user['lastname']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p><strong>Contact:</strong> <?= htmlspecialchars($user['contact']); ?></p>
            <p><strong>Gender:</strong> <?= htmlspecialchars($user['gender']); ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($user['hno'] . ", Block " . $user['block'] . ", " . $user['street']); ?></p>
        <?php else: ?>
            <p class="text-danger">User details not found.</p>
        <?php endif; ?>
    </div>

    <!-- Reports History -->
    <div class="card shadow p-4">
        <h4 class="mb-3">My Reports</h4>
        <?php if ($reports && $reports->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Issue ID</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Image</th>
                            <th>Solved Image</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $reports->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['issue_id']); ?></td>
                            <td><?= htmlspecialchars($row['issueType']); ?></td>
                            <td><?= htmlspecialchars($row['description']); ?></td>
                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <?php if (!empty($row['images'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($row['images']); ?>" width="80" height="60" class="rounded">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($row['solved_image'])): ?>
                                    <img src="uploads/solved/<?= htmlspecialchars($row['solved_image']); ?>" width="80" height="60" class="rounded">
                                <?php else: ?>
                                    <span class="text-muted">Not available</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge 
                                    <?= ($row['status'] == 'pending') ? 'bg-warning' : 
                                       (($row['status'] == 'visited') ? 'bg-info' : 
                                       (($row['status'] == 'inprogress') ? 'bg-primary' : 'bg-success')); ?>">
                                    <?= ucfirst($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">You haven't submitted any reports yet.</p>
        <?php endif; ?>
    </div>
</div>

<a href="#" class="btn btn-lg btn-lg-square back-to-top" style="background-color: #86B817; color: white;">
    <i class="bi bi-arrow-up"></i>
</a>


<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
