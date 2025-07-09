<?php
// Start the session to access the session variables
session_start();

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    die("Please log in to view your resumes.");
}

// Get the logged-in user's email
$email_id = $_SESSION['email'];

// SQL Query to get resume details for the logged-in user
$sql = "SELECT resume_id, fullname, template_no,updated_at FROM resume WHERE email_id = '$email_id'";

$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Error in query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUICK RESUME</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
body {
    background: linear-gradient(135deg, #dbeeff 0%, #f1f9ff 100%);
    font-family: Arial, sans-serif;
    min-height: 100vh;
    padding-bottom: 50px;
}
        .navbar {
            background-color: #007bff;
        }
        .navbar .navbar-brand, .navbar .nav-link {
            color: white !important;
        }
        .resume-list {
            margin-top: 30px;
        }
        .resume-item {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .resume-item h3 {
            font-size: 1.5rem;
            color: #007bff;
        }
        .resume-item p {
            font-size: 1rem;
            color: #555;
        }
        .container {
            margin-top: 50px;
        }
	
.no-resume-box {
    background: rgba(255, 255, 255, 0.6);
    border: 1px solid #007bff;
    border-radius: 10px;
    padding: 30px;
    text-align: center;
    color: #004080;
    font-size: 1.2rem;
    backdrop-filter: blur(5px);
    box-shadow: 0 0 10px rgba(0,123,255,0.1);
}

    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    &nbsp;&nbsp;&nbsp;<img src="logo1.png" height="50px" width="50px">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">QUICK RESUME</a>
        <div class="d-flex ml-auto">
             <a href="logout.php" class="nav-link"> <i class="bi bi-box-arrow-right"></i>  Logout</a>
        </div>
    </div>
</nav>

<!-- Create New Resume Button -->
<div class="container">
    <div class="d-flex justify-content-end mt-3">
        <a href="formlist.html" class="btn btn-success">+ New</a>
    </div>
</div>

<!-- Resume List Section -->
<div class="container">
    <?php if ($result->num_rows > 0) { ?>
        <div class="resume-list">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="resume-item">
                    <h3>Resume ID: <?php echo $row['resume_id']; ?></h3>
                    <p>Name: <?php echo $row['fullname']; ?></p>
 <p >🕒 <?php echo date("d M Y, h:i A", strtotime($row['updated_at'])); ?></p>

                    <a href="open_resume.php?id=<?php echo $row['resume_id']; ?>" class="btn btn-primary btn-sm">Open</a>
                    <a href="edit_resume.php?id=<?php echo $row['resume_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_resume.php?id=<?php echo $row['resume_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    <a href="copy_resume.php?id=<?php echo $row['resume_id']; ?>" class="btn btn-secondary btn-sm">Copy</a>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="no-resume-box">
    <p><strong>📝 No resumes found.</strong><br>Click <span style="color:#28a745;">"+ New"</span> to create your first resume.</p>
</div>

    <?php } ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
