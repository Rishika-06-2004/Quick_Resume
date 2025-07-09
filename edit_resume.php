<?php
session_start();
$servername = "localhost";
$username =  "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['email'])) {
    die("Unauthorized access.");
}

if (!isset($_GET['id'])) {
    die("Resume ID not provided.");
}

$resume_id = intval($_GET['id']);
$email = $_SESSION['email'];

// Fetch resume
$resume_sql = "SELECT * FROM resume WHERE resume_id = $resume_id AND email_id = '$email'";
$resume_result = $conn->query($resume_sql);
$resume = $resume_result->fetch_assoc();
if (!$resume) die("Resume not found or access denied.");

// Fetch skills
$skills_result = $conn->query("SELECT * FROM skills WHERE resume_id = $resume_id AND rating IS NOT NULL");
$skills = [];
while ($row = $skills_result->fetch_assoc()) $skills[] = $row;

// Soft skills
$soft_skills_result = $conn->query("SELECT * FROM skills WHERE resume_id = $resume_id AND rating IS NULL");
$soft_skills = [];
while ($row = $soft_skills_result->fetch_assoc()) $soft_skills[] = $row;

// Work
$work_result = $conn->query("SELECT * FROM work WHERE resume_id = $resume_id");
$work_experiences = [];
while ($row = $work_result->fetch_assoc()) $work_experiences[] = $row;

// Education
$edu_result = $conn->query("SELECT * FROM education WHERE resume_id = $resume_id");
$education = ['Master\'s' => null, 'Bachelor\'s' => null, 'Higher Secondary' => null, '10th' => null];
while ($row = $edu_result->fetch_assoc()) {
    $education[$row['Board']] = $row;
}

function checked($val1, $val2) {
    return $val1 === $val2 ? 'checked' : '';
}
function selected($val1, $val2) {
    return $val1 === $val2 ? 'selected' : '';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Resume</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Resume</h2>
    <form action="editback.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="resume_id" value="<?php echo $resume_id; ?>">

        <!-- Personal Details -->
        <div class="form-group"><label>Full Name</label>
            <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($resume['fullname']); ?>">
        </div>
        <div class="form-group"><label>Email</label>
            <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($resume['email_id']); ?>">
        </div>
        <div class="form-group"><label>Address</label>
            <textarea name="Address" class="form-control"><?php echo htmlspecialchars($resume['Address']); ?></textarea>
        </div>
        <div class="form-group"><label>Mobile No</label>
            <input type="text" name="contact" class="form-control" value="<?php echo htmlspecialchars($resume['mobile_no']); ?>">
        </div>
        <div class="form-group"><label>Gender</label><br>
            <input type="radio" name="gender" value="Male" <?php echo checked($resume['gender'], 'Male'); ?>> Male
            <input type="radio" name="gender" value="Female" <?php echo checked($resume['gender'], 'Female'); ?>> Female
        </div>
        <div class="form-group"><label>Marital Status</label>
            <select name="marital_status" class="form-control">
                <option value="Single" <?php echo selected($resume['marital'], 'Single'); ?>>Single</option>
                <option value="Married" <?php echo selected($resume['marital'], 'Married'); ?>>Married</option>
            </select>
        </div>
        <div class="form-group"><label>Nationality</label>
            <input type="text" name="nationality" class="form-control" value="<?php echo htmlspecialchars($resume['nationality']); ?>">
        </div>
        <div class="form-group"><label>Languages Known</label>
            <input type="text" name="language" class="form-control" value="<?php echo htmlspecialchars($resume['language']); ?>">
        </div>
        <div class="form-group"><label>Objective</label>
            <textarea name="objective" class="form-control"><?php echo htmlspecialchars($resume['objective']); ?></textarea>
        </div>
        <div class="form-group"><label>Profile Picture</label><br>
            <img src="uploads/<?php echo $resume['image']; ?>" width="100"><br><br>
            <input type="file" name="profile_pic" class="form-control">
        </div>

        <!-- Skills With Rating -->
        <h4>Skills (with rating)</h4>
        <div id="skills_with_rating">
        <?php foreach ($skills as $s): ?>
            <div class="form-group skill-with-rating">
                <input type="text" name="skills[]" class="form-control mb-1" placeholder="Skill Name" value="<?php echo htmlspecialchars($s['skill']); ?>">
                <input type="number" name="skill_ratings[]" class="form-control mb-2" placeholder="Rating (1-5)" min="1" max="5" value="<?php echo $s['rating']; ?>">
            </div>
        <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-secondary mb-3" onclick="addSkillWithRating()">Add More Skills (with rating)</button>

        <!-- Soft Skills Without Rating -->
        <h4>Soft Skills (without rating)</h4>
        <div id="soft_skills">
        <?php foreach ($soft_skills as $ss): ?>
            <div class="form-group soft-skill">
                <input type="text" name="soft_skills[]" class="form-control mb-2" placeholder="Soft Skill" value="<?php echo htmlspecialchars($ss['skill']); ?>">
            </div>
        <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-secondary mb-3" onclick="addSoftSkill()">Add More Soft Skills</button>

        <!-- Work Experience -->
        <h4>Work Experience</h4>
        <div id="work_container">
            <?php foreach ($work_experiences as $w): ?>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Company Name</label>
                        <input type="text" name="company_name[]" class="form-control" value="<?php echo htmlspecialchars($w['company']); ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label>Joining Year</label>
                        <input type="text" name="joining_year[]" class="form-control" value="<?php echo $w['started']; ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label>Resign Year</label>
                        <input type="text" name="resign_year[]" class="form-control" value="<?php echo $w['ended']; ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Responsibilities</label>
                        <input type="text" name="responsibilities[]" class="form-control" value="<?php echo htmlspecialchars($w['description']); ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-secondary mb-3" onclick="addWorkExperience()">Add More Experience</button>

        <!-- Education -->
        <h4>Education</h4>
        <?php if ($education["Master's"]): ?>
            <h5>Master's</h5>
            <div class="form-group">
                <input type="text" name="master_institute" class="form-control" value="<?php echo htmlspecialchars($education["Master's"]['institute_name']); ?>">
                <input type="text" name="master_year" class="form-control" value="<?php echo $education["Master's"]['end_year']; ?>">
                <input type="text" name="master_percentage" class="form-control" value="<?php echo $education["Master's"]['percentage']; ?>">
            </div>
        <?php else: ?>
            <div class="form-group">
                <label>Do you have a Master's degree?</label><br>
                <input type="radio" name="has_masters" value="yes" onclick="toggleSection('master_section', true)"> Yes
                <input type="radio" name="has_masters" value="no" onclick="toggleSection('master_section', false)" checked> No
            </div>
            <div id="master_section" style="display:none;">
                <h5>Master's Details</h5>
                <input type="text" name="master_institute" class="form-control" placeholder="Institute Name">
                <input type="text" name="master_year" class="form-control" placeholder="Passing Year">
                <input type="text" name="master_percentage" class="form-control" placeholder="Percentage">
            </div>
        <?php endif; ?>

        <?php if ($education["Bachelor's"]): ?>
            <h5>Bachelor's</h5>
            <div class="form-group">
                <input type="text" name="bachelor_institute" class="form-control" value="<?php echo htmlspecialchars($education["Bachelor's"]['institute_name']); ?>">
                <input type="text" name="bachelor_year" class="form-control" value="<?php echo $education["Bachelor's"]['end_year']; ?>">
                <input type="text" name="bachelor_percentage" class="form-control" value="<?php echo $education["Bachelor's"]['percentage']; ?>">
            </div>
        <?php else: ?>
            <div class="form-group">
                <label>Do you have a Bachelor's degree?</label><br>
                <input type="radio" name="has_bachelor" value="yes" onclick="toggleSection('bachelor_section', true)"> Yes
                <input type="radio" name="has_bachelor" value="no" onclick="toggleSection('bachelor_section', false)" checked> No
            </div>
            <div id="bachelor_section" style="display:none;">
                <h5>Bachelor's Details</h5>
                <input type="text" name="bachelor_institute" class="form-control" placeholder="Institute Name">
                <input type="text" name="bachelor_year" class="form-control" placeholder="Passing Year">
                <input type="text" name="bachelor_percentage" class="form-control" placeholder="Percentage">
            </div>
        <?php endif; ?>

        <h5>Higher Secondary</h5>
        <div class="form-group">
            <input type="text" name="hs_school" class="form-control" value="<?php echo htmlspecialchars($education['Higher Secondary']['institute_name']); ?>">
            <input type="text" name="hs_year" class="form-control" value="<?php echo $education['Higher Secondary']['end_year']; ?>">
            <input type="text" name="hs_percentage" class="form-control" value="<?php echo $education['Higher Secondary']['percentage']; ?>">
        </div>
        <h5>10th</h5>
        <div class="form-group">
            <input type="text" name="tenth_school" class="form-control" value="<?php echo htmlspecialchars($education['10th']['institute_name']); ?>">
            <input type="text" name="tenth_year" class="form-control" value="<?php echo $education['10th']['end_year']; ?>">
            <input type="text" name="tenth_percentage" class="form-control" value="<?php echo $education['10th']['percentage']; ?>">
        </div>

        <button type="submit" class="btn btn-primary">Update Resume</button>
    </form>
</div>

<!-- JavaScript Section -->
<script>
function toggleSection(id, show) {
    document.getElementById(id).style.display = show ? 'block' : 'none';
}

function addWorkExperience() {
    const container = document.getElementById('work_container');
    const newRow = document.createElement('div');
    newRow.classList.add('form-row');
    newRow.innerHTML = `
        <div class="form-group col-md-4">
            <label>Company Name</label>
            <input type="text" name="company_name[]" class="form-control">
        </div>
        <div class="form-group col-md-2">
            <label>Joining Year</label>
            <input type="text" name="joining_year[]" class="form-control">
        </div>
        <div class="form-group col-md-2">
            <label>Resign Year</label>
            <input type="text" name="resign_year[]" class="form-control">
        </div>
        <div class="form-group col-md-4">
            <label>Responsibilities</label>
            <input type="text" name="responsibilities[]" class="form-control">
        </div>
    `;
    container.appendChild(newRow);
}

function addSkillWithRating() {
    const container = document.getElementById('skills_with_rating');
    const count = container.querySelectorAll('.skill-with-rating').length;
    if (count >= 5) {
        alert("You can add up to 5 skills with rating.");
        return;
    }
    const div = document.createElement('div');
    div.classList.add('form-group', 'skill-with-rating');
    div.innerHTML = `
        <input type="text" name="skills[]" class="form-control mb-1" placeholder="Skill Name">
        <input type="number" name="skill_ratings[]" class="form-control mb-2" placeholder="Rating (1-5)" min="1" max="5">
    `;
    container.appendChild(div);
}

function addSoftSkill() {
    const container = document.getElementById('soft_skills');
    const count = container.querySelectorAll('.soft-skill').length;
    if (count >= 5) {
        alert("You can add up to 5 soft skills.");
        return;
    }
    const div = document.createElement('div');
    div.classList.add('form-group', 'soft-skill');
    div.innerHTML = `
        <input type="text" name="soft_skills[]" class="form-control mb-2" placeholder="Soft Skill">
    `;
    container.appendChild(div);
}
</script>
</body>
</html>
