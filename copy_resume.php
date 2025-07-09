<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Check session and input
if (!isset($_SESSION['email'])) die("Unauthorized access.");
if (!isset($_GET['id'])) die("No resume ID provided.");

$old_id = intval($_GET['id']);
$email = $_SESSION['email'];

// 1️⃣ Fetch original resume
$resume_sql = "SELECT * FROM resume WHERE resume_id = $old_id AND email_id = '$email'";
$resume_result = $conn->query($resume_sql);
if (!$resume_result || $resume_result->num_rows === 0) die("Resume not found.");
$original = $resume_result->fetch_assoc();

// 2️⃣ Copy main resume data (include template_no)
$fullname = $conn->real_escape_string($original['fullname']);
$address = $conn->real_escape_string($original['Address']);
$mobile = $conn->real_escape_string($original['mobile_no']);
$gender = $conn->real_escape_string($original['gender']);
$marital = $conn->real_escape_string($original['marital']);
$nationality = $conn->real_escape_string($original['nationality']);
$language = $conn->real_escape_string($original['language']);
$objective = $conn->real_escape_string($original['objective']);
$image = $conn->real_escape_string($original['image']);
$template_no = intval($original['template_no']);

$insert_resume = "INSERT INTO resume (email_id, fullname, Address, mobile_no, gender, marital, nationality, language, objective, updated_at, image, template_no)
                  VALUES ('$email', '$fullname', '$address', '$mobile', '$gender', '$marital', '$nationality', '$language', '$objective', NOW(), '$image', $template_no)";
$conn->query($insert_resume) or die("Resume copy failed: " . $conn->error);
$new_id = $conn->insert_id;

// 3️⃣ Copy skills and soft skills
$skill_sql = "SELECT * FROM skills WHERE resume_id = $old_id";
$skills = $conn->query($skill_sql);
while ($s = $skills->fetch_assoc()) {
    $skill = $conn->real_escape_string($s['skill']);
    $rating = is_null($s['rating']) ? "NULL" : intval($s['rating']);
    $conn->query("INSERT INTO skills (resume_id, skill, rating) VALUES ($new_id, '$skill', $rating)");
}

// 4️⃣ Copy work experience
$work_sql = "SELECT * FROM work WHERE resume_id = $old_id";
$work = $conn->query($work_sql);
while ($w = $work->fetch_assoc()) {
    $company = $conn->real_escape_string($w['company']);
    $started = $conn->real_escape_string($w['started']);
    $ended = $conn->real_escape_string($w['ended']);
    $desc = $conn->real_escape_string($w['description']);
    $conn->query("INSERT INTO work (resume_id, company, started, ended, description) VALUES ($new_id, '$company', '$started', '$ended', '$desc')");
}

// 5️⃣ Copy education
$edu_sql = "SELECT * FROM education WHERE resume_id = $old_id";
$edu = $conn->query($edu_sql);
while ($e = $edu->fetch_assoc()) {
    $board = $conn->real_escape_string($e['Board']);
    $institute = $conn->real_escape_string($e['institute_name']);
    $year = $conn->real_escape_string($e['end_year']);
    $percent = $conn->real_escape_string($e['percentage']);
    $conn->query("INSERT INTO education (resume_id, Board, institute_name, end_year, percentage) VALUES ($new_id, '$board', '$institute', '$year', '$percent')");
}

// ✅ Redirect to view cloned resume
header("Location: open_resume.php?id=$new_id");
exit();
?>
