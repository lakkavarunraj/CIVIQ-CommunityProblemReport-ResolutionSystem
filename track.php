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
    $tracking_id = $conn->real_escape_string($_POST['tracking_id']);
    $sql = "SELECT * FROM reports WHERE issue_id = '$tracking_id' LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $report = $result->fetch_assoc();
    } else {
        $error = "No report found with Tracking ID: " . htmlspecialchars($tracking_id);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CiviQ - Tracking</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="serv.css" rel="stylesheet">

    <style>
        /* Status tracker wrapper */
        .status-tracker {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 40px 0;
            position: relative;
            width: 100%;
        }

        /* Grey connector line */
        .status-tracker::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 60px;
            right: 60px;
            height: 5px;
            background: #ddd;
            transform: translateY(-50%);
            z-index: 1;
            border-radius: 5px;
        }

        /* Green progress line */
        .progress-line {
            position: absolute;
            top: 50%;
            left: 60px;
            height: 5px;
            background: #86B817;
            transform: translateY(-50%);
            z-index: 2;
            border-radius: 5px;
            transition: width 0.6s ease-in-out;
            width: 0;
        }

        /* Steps */
        .status-step {
            position: relative;
            z-index: 3;
            width: 160px;
            height: 50px;
            border-radius: 25px;
            background: #ddd;
            color: #555;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .status-step.active,
        .status-step.completed {
            background: #86B817;
            color: white;
        }

        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #86B817;
            border: none;
        }

        .issue-img {
            max-width: 450px;
            max-height: 450px;
            border-radius: 10px;
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
                    <a href="home.html" class="nav-item nav-link">Home</a>
                    <a href="about.html" class="nav-item nav-link">About</a>
                    <a href="report.html" class="nav-item nav-link">Report</a>
                    <a href="track.php" class="nav-item nav-link active">Tracking</a>
                    <a href="contact.html" class="nav-item nav-link">Contact</a>
                </div>
                <a href="pro.php" class="btn btn-custom rounded-pill py-2 px-4">Profile</a>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Tracking Section -->
    <div class="container-xxl pb-5" style="margin-top: 6rem;">
        <h2 class="mb-4 text-center">Track Your Report</h2>
        <!-- Tracking Form -->
        <form method="POST" class="d-flex justify-content-center mb-4">
            <input type="text" name="tracking_id" class="form-control w-50 me-2" placeholder="Enter Issue ID" required>
            <button type="submit" class="btn btn-custom">Track</button>
        </form>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($report): ?>
            <div class="card shadow p-4">
                <h4 class="mb-3">Report Details</h4>
                <p><strong>Tracking ID:</strong> <?php echo htmlspecialchars($report['issue_id']); ?></p>
                <p><strong>Issue Type:</strong> <?php echo htmlspecialchars($report['issueType']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($report['description']); ?></p>
                <p><strong>Date Reported:</strong> <?php echo htmlspecialchars($report['created_at']); ?></p>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h5>User Uploaded Image:</h5>
                        <?php if (!empty($report['images'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($report['images']); ?>" class="issue-img img-fluid">
                        <?php else: ?>
                            <p class="text-muted">No image uploaded</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h5>Solved Image:</h5>
                        <?php if (!empty($report['solved_image'])): ?>
                            <img src="uploads/solved/<?php echo htmlspecialchars($report['solved_image']); ?>" class="issue-img img-fluid">
                        <?php else: ?>
                            <p class="text-muted">Not available yet</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Status Tracker -->
                <div class="status-tracker mt-5" id="status-tracker">
                    <div class="progress-line" id="progress-line"></div>
                    <div class="status-step" id="step-pending">Pending</div>
                    <div class="status-step" id="step-visited">Field Agent Visited</div>
                    <div class="status-step" id="step-inprogress">In Progress</div>
                    <div class="status-step" id="step-solved">Issue Solved</div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const progressLine = document.getElementById("progress-line");
                    const steps = [
                        document.getElementById("step-pending"),
                        document.getElementById("step-visited"),
                        document.getElementById("step-inprogress"),
                        document.getElementById("step-solved")
                    ];

                    let status = "<?php echo strtolower(trim($report['status'])); ?>";
                    let statusIndex = 0;

                    switch (status) {
                        case "pending": statusIndex = 0; break;
                        case "visited": statusIndex = 1; break;
                        case "in progress": statusIndex = 2; break; // FIXED here
                        case "solved": statusIndex = 3; break;
                    }

                    // Mark steps
                    steps.forEach((step, index) => {
                        if (index < statusIndex) {
                            step.classList.add("completed");
                        } else if (index === statusIndex) {
                            step.classList.add("active");
                        }
                    });

                    // Progress line calculation
                    const tracker = document.getElementById("status-tracker");
                    const trackerWidth = tracker.offsetWidth - 120; // left+right margins
                    const stepGap = trackerWidth / (steps.length - 1);
                    progressLine.style.width = (statusIndex * stepGap) + "px";
                });
            </script>
        <?php endif; ?>
    </div>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
