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

// Fetch all reports
$result = $conn->query("SELECT issue_id, issueType, description, status, images, solved_image, created_at FROM reports ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CiviQ - Reports</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="serv.css" rel="stylesheet">

    <style>
        /* Dashboard section */
        .dashboard-container {
            margin-top: 90px; /* Space below navbar */
        }

        h2 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        /* Table Styling */
        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .table th {
            background: var(--dark);
            color: #fff;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .issue-img {
            max-width: 120px;
            max-height: 120px;
            border-radius: 8px;
            object-fit: cover;
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
                    <a href="admin.php" class="nav-item nav-link">Dashboard</a>
                    <a href="reportsadmin.php" class="nav-item nav-link active">Reports</a>
                    <a href="user.php" class="nav-item nav-link">Users</a>
                </div>
                <a href="logout.php" class="btn btn-custom rounded-pill py-2 px-4">Logout</a>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <div class="container dashboard-container">
        <h2 class="mb-4">User Reports</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Update successful!</div>
        <?php endif; ?>

        <div class="table-container table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>Tracking ID</th>
                        <th>Issue Type</th>
                        <th>Description</th>
                        <th>User Image</th>
                        <th>Status</th>
                        <th>Solved Image</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['issue_id']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($row['issueType'])); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td>
                            <?php if (!empty($row['images'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($row['images']); ?>" class="issue-img">
                            <?php else: ?>
                                <span class="text-muted">No image</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge" style="background-color:#86B817; color:#fff;"><?php echo htmlspecialchars(ucfirst($row['status'])); ?></span></td>
                        <td>
                            <?php if (!empty($row['solved_image'])): ?>
                                <img src="uploads/solved/<?php echo htmlspecialchars($row['solved_image']); ?>" class="issue-img">
                            <?php else: ?>
                                <span class="text-muted">Not uploaded</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <form action="update.php" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-2">
                                <input type="hidden" name="issue_id" value="<?php echo htmlspecialchars($row['issue_id']); ?>">
                                <select name="status" class="form-select">
                                    <option value="pending" <?php if($row['status'] == "pending") echo "selected"; ?>>Pending</option>
                                    <option value="visited" <?php if($row['status'] == "visited") echo "selected"; ?>>Visited</option>
                                    <option value="in progress" <?php if($row['status'] == "in progress") echo "selected"; ?>>In Progress</option>
                                    <option value="solved" <?php if($row['status'] == "solved") echo "selected"; ?>>Solved</option>
                                </select>
                                <input type="file" name="solved_image" class="form-control">
                                <button type="submit" class="btn btn-custom">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back to Top -->
<a href="#" class="btn btn-lg btn-lg-square back-to-top" style="background-color: #86B817; color: white;">
    <i class="bi bi-arrow-up"></i>
</a>


    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
