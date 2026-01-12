<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

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
$result = $conn->query("SELECT citizen_id, firstname, lastname, email, gender, contact, hno, block, street FROM citizen ORDER BY citizen_id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Users - CiviQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
        <link href="serv.css" rel="stylesheet">

    <style>
        .table-container {
            margin-top: 100px;
        }
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
                    <a href="admin.php" class="nav-item nav-link ">Dashboard</a>
                    <a href="reportsadmin.php" class="nav-item nav-link">Reports</a>
                    <a href="user.php" class="nav-item nav-link active">Users</a>
                </div>
                <a href="logout.php" class="btn btn-custom rounded-pill py-2 px-4">Logout</a>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Registered Citizens Section -->
    <div class="container table-container">
        <h2 class="mb-4 text-center">Registered Citizens</h2>
        <div class="d-flex justify-content-between mb-3">
            <a href="admin.php" class="btn btn-primary" style="background-color: #86B817; color: white;">⬅ Back to Dashboard</a>
        </div>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Citizen ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Contact</th>
                        <th>House No</th>
                        <th>Block</th>
                        <th>Street</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['citizen_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                            <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact']); ?></td>
                            <td><?php echo htmlspecialchars($row['hno']); ?></td>
                            <td><?php echo htmlspecialchars($row['block']); ?></td>
                            <td><?php echo htmlspecialchars($row['street']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">No users found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Citizens End -->

    <!-- Back to Top -->
<a href="#" class="btn btn-lg btn-lg-square back-to-top" style="background-color: #86B817; color: white;">
    <i class="bi bi-arrow-up"></i>
</a>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
