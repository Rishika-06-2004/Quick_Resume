<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_POST['resume_id']) || !isset($_SESSION['email'])) {
    die("Invalid request.");
}

$resume_id = intval($_POST['resume_id']);
$email = $_SESSION['email'];

// ----------------------- Personal Details Update -----------------------
$name = $_POST['name'];
$address = $_POST['Address'];
$contact = $_POST['contact'];
$gender = $_POST['gender'] ?? '';
$marital = $_POST['marital_status'] ?? '';
$nationality = $_POST['nationality'];
$language = $_POST['language'];
$objective = $_POST['objective'];

// Profile picture upload
if (!empty($_FILES['profile_pic']['name'])) {
    $img_name = time() . '_' . basename($_FILES['profile_pic']['name']);
    $target = "uploads/" . $img_name;
    move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target);
    $image_sql = ", image='$img_name'";
} else {
    $image_sql = "";
}

$update_resume = "UPDATE resume SET fullname='$name', Address='$address', mobile_no='$contact',
                  gender='$gender', marital='$marital', nationality='$nationality', language='$language',
                  objective='$objective' $image_sql WHERE resume_id=$resume_id AND email_id='$email'";
$conn->query($update_resume);

// ----------------------- Skills Update -----------------------
$conn->query("DELETE FROM skills WHERE resume_id=$resume_id");

// Skills with ratings
if (!empty($_POST['skills'])) {
    foreach ($_POST['skills'] as $i => $skill_name) {
        $rating = intval($_POST['skill_ratings'][$i]);
        $skill_name = $conn->real_escape_string($skill_name);
        if (!empty($skill_name) && $rating > 0) {
            $conn->query("INSERT INTO skills (resume_id, skill, rating) VALUES ($resume_id, '$skill_name', $rating)");
        }
    }
}

// Soft skills (without rating)
if (!empty($_POST['soft_skills'])) {
    foreach ($_POST['soft_skills'] as $soft) {
        $soft = $conn->real_escape_string($soft);
        if (!empty($soft)) {
            $conn->query("INSERT INTO skills (resume_id, skill, rating) VALUES ($resume_id, '$soft', NULL)");
        }
    }
}

// ----------------------- Work Experience Update -----------------------
$existing_works = [];
$existing_query = $conn->query("SELECT work_id FROM work WHERE resume_id = $resume_id");
while ($row = $existing_query->fetch_assoc()) {
    $existing_works[] = $row['work_id'];
}

$company_names = $_POST['company_name'] ?? [];
$joining_years = $_POST['joining_year'] ?? [];
$resign_years = $_POST['resign_year'] ?? [];
$responsibilities = $_POST['responsibilities'] ?? [];

foreach ($company_names as $i => $company) {
    $company = $conn->real_escape_string($company);
    $start = $conn->real_escape_string($joining_years[$i]);
    $end = $conn->real_escape_string($resign_years[$i]);
    $desc = $conn->real_escape_string($responsibilities[$i]);

    if (!empty($company)) {
        if (isset($existing_works[$i])) {
            $work_id = $existing_works[$i];
            $sql = "UPDATE work SET company='$company', started='$start', ended='$end', description='$desc'
                    WHERE work_id=$work_id AND resume_id=$resume_id";
        } else {
            $sql = "INSERT INTO work (resume_id, company, started, ended, description)
                    VALUES ($resume_id, '$company', '$start', '$end', '$desc')";
        }
        $conn->query($sql) or die("Work experience update error: " . $conn->error);
    }
}

// ----------------------- Education Update -----------------------
$education_data = [];

// Add Master's if selected
if (!empty($_POST['master_institute']) && ($_POST['has_masters'] ?? '') === 'yes') {
    $education_data["Master's"] = [
        $_POST['master_institute'], 
        $_POST['master_year'], 
        $_POST['master_percentage']
    ];
}

// Add Bachelor's if selected
if (!empty($_POST['bachelor_institute']) && ($_POST['has_bachelor'] ?? '') === 'yes') {
    $education_data["Bachelor's"] = [
        $_POST['bachelor_institute'], 
        $_POST['bachelor_year'], 
        $_POST['bachelor_percentage']
    ];
}

// Always required
$education_data["Higher Secondary"] = [
    $_POST['hs_school'], 
    $_POST['hs_year'], 
    $_POST['hs_percentage']
];
$education_data["10th"] = [
    $_POST['tenth_school'], 
    $_POST['tenth_year'], 
    $_POST['tenth_percentage']
];

foreach ($education_data as $board => $data) {
    $board_escaped = $conn->real_escape_string($board); // FIXED LINE
    [$institute, $year, $perc] = array_map([$conn, 'real_escape_string'], $data);

    if (!empty($institute)) {
        $check = $conn->query("SELECT * FROM education WHERE resume_id=$resume_id AND Board='$board_escaped'");
        if ($check && $check->num_rows > 0) {
            $sql = "UPDATE education 
                    SET institute_name='$institute', end_year='$year', percentage='$perc' 
                    WHERE resume_id=$resume_id AND Board='$board_escaped'";
        } else {
            $sql = "INSERT INTO education (resume_id, Board, institute_name, end_year, percentage) 
                    VALUES ($resume_id, '$board_escaped', '$institute', '$year', '$perc')";
        }
        $conn->query($sql) or die("Education update error: " . $conn->error);
    }
}

// ----------------------- Redirect After Update -----------------------
header("Location: clone2.php?id=$resume_id");
exit();
?>
