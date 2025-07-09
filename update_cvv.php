<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];
$contact = $_POST['contact'];
$gender = $_POST['gender'];
$marital_status = $_POST['marital_status'];
$nationality = $_POST['nationality'];
$language = $_POST['language'];
$objective = $_POST['objective'];
$profile_pic = $_FILES['profile_pic']['name'];

// Handle file upload
$target_dir = "uploads/";
$target_file = $target_dir . basename($profile_pic);
move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);

$template_no=1;
$sql = "INSERT INTO resume (email_id, fullname, address, mobile_no, gender, marital, nationality, language, objective, updated_at, image, template_no) 
        VALUES ('$email', '$name', '$address', '$contact', '$gender', '$marital_status', '$nationality', '$language', '$objective', NOW(), '$profile_pic' , '$template_no')";

if ($conn->query($sql) === TRUE) {
    $resume_id = $conn->insert_id;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    exit();
}

// Insert Skills
if (isset($_POST['skills'])) {
    $skills = $_POST['skills'];
    $ratings = $_POST['skill_ratings'];
    
    foreach ($skills as $index => $skill) {
        $rating = $ratings[$index];
        $sql = "INSERT INTO skills (resume_id, skill, rating) 
                VALUES ('$resume_id', '$skill', '$rating')";
        $conn->query($sql);
    }
}

// Insert Soft Skills
if (isset($_POST['soft_skills'])) {
    $soft_skills = $_POST['soft_skills'];
    
    foreach ($soft_skills as $soft_skill) {
        $sql = "INSERT INTO skills (resume_id, skill, rating) 
                VALUES ('$resume_id', '$soft_skill', NULL)";
        $conn->query($sql);
    }
}

// Insert Work Experience
if (isset($_POST['company_name'])) {
    $companies = $_POST['company_name'];
    $joining_years = $_POST['joining_year'];
    $resign_years = $_POST['resign_year'];
    $responsibilities = $_POST['responsibilities'];

    foreach ($companies as $index => $company) {
        $joining_year = $joining_years[$index];
        $resign_year = $resign_years[$index];
        $responsibility = $responsibilities[$index];

        $till_present_key = "till_present_" . $index;
        $till_present = isset($_POST[$till_present_key]) ? 1 : 0;

        $sql = "INSERT INTO work (resume_id, job_role, company, description, started, ended) 
                VALUES ('$resume_id', '', '$company', '$responsibility', '$joining_year', " . 
                ($till_present ? "NULL" : "'$resign_year'") . ")";
        $conn->query($sql);
    }
}

// Insert Education

// Master's Degree
if (isset($_POST['has_master']) && $_POST['has_master'] == 'yes') {
    $master_institute = $_POST['master_institute'];
    $master_university = $_POST['master_university'];
    $master_year = $_POST['master_year'];
    $master_percentage = $_POST['master_percentage'];

    $sql = "INSERT INTO education (resume_id, Board, start_year, end_year, percentage, institute_name) 
            VALUES ('$resume_id', 'Master\'s', '$master_year', '$master_year', '$master_percentage', '$master_institute')";
    $conn->query($sql);
}

// Bachelor's Degree
if (isset($_POST['has_bachelor']) && $_POST['has_bachelor'] == 'yes') {
    $bachelor_institute = $_POST['bachelor_institute'];
    $bachelor_university = $_POST['bachelor_university'];
    $bachelor_year = $_POST['bachelor_year'];
    $bachelor_percentage = $_POST['bachelor_percentage'];

    $sql = "INSERT INTO education (resume_id, Board, start_year, end_year, percentage, institute_name) 
            VALUES ('$resume_id', 'Bachelor\'s', '$bachelor_year', '$bachelor_year', '$bachelor_percentage', '$bachelor_institute')";
    $conn->query($sql);
}

// Higher Secondary (12th)
$hs_school = $_POST['hs_school'];
$hs_board = $_POST['hs_board'];
$hs_year = $_POST['hs_year'];
$hs_percentage = $_POST['hs_percentage'];

$sql = "INSERT INTO education (resume_id, Board, start_year, end_year, percentage, institute_name) 
        VALUES ('$resume_id', 'Higher Secondary', '$hs_year', '$hs_year', '$hs_percentage', '$hs_school')";
$conn->query($sql);

// 10th
$tenth_school = $_POST['tenth_school'];
$tenth_board = $_POST['tenth_board'];
$tenth_year = $_POST['tenth_year'];
$tenth_percentage = $_POST['tenth_percentage'];

$sql = "INSERT INTO education (resume_id, Board, end_year, percentage, institute_name) 
        VALUES ('$resume_id', '10th', '$tenth_year', '$tenth_percentage', '$tenth_school')";
$conn->query($sql);

// Redirect to CV display page
header("Location: clone2.php");
$conn->close();
?>
