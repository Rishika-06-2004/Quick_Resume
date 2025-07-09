<?php
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

// Get resume ID
if (isset($_GET['id'])) {
    $resume_id = $_GET['id'];
    $sql = "SELECT * FROM resume WHERE resume_id = $resume_id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $resume = $result->fetch_assoc();
    } else {
        die("Resume not found.");
    }
} else {
    die("No resume ID provided.");
}

// Education
$sql_education = "SELECT institute_name, end_year, percentage, Board 
                  FROM education WHERE resume_id = $resume_id";
$result_education = $conn->query($sql_education);
$education_details = ($result_education && $result_education->num_rows > 0) ? $result_education->fetch_all(MYSQLI_ASSOC) : [];

// Skills
$sql_skills = "SELECT skill, rating FROM skills WHERE resume_id = $resume_id";
$result_skills = $conn->query($sql_skills);
$skills_details = ($result_skills && $result_skills->num_rows > 0) ? $result_skills->fetch_all(MYSQLI_ASSOC) : [];

// Work
$sql_work = "SELECT job_role, company, description, started, ended FROM work WHERE resume_id = $resume_id";
$result_work = $conn->query($sql_work);
$work_details = ($result_work && $result_work->num_rows > 0) ? $result_work->fetch_all(MYSQLI_ASSOC) : [];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Resume</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .top-bar {
      text-align: right;
      padding: 20px;
      background: #fff;
    }
    .resume-container {
      max-width: 1000px;
      margin: 0 auto 30px;
      display: flex;
      background: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .left {
      width: 30%;
      background: #5c4033;
      color: #fff;
      padding: 20px;
      box-sizing: border-box;
    }
    .left img {
      width: 100%;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    .right {
      width: 70%;
      padding: 30px;
    }
    h2 {
      margin-top: 0;
    }
    .left h3 {
      color: white;
      border-bottom: 2px solid #ccc;
      padding-bottom: 5px;
      margin-top: 30px;
    }
    .right h3 {
      color: #5c4033;
      border-bottom: 2px solid #ccc;
      padding-bottom: 5px;
      margin-top: 30px;
    }
    .star {
      color: gold;
      font-size: 18px;
    }
    .resume-section p {
      margin: 5px 0;
    }
    .education-item, .work-item {
      margin-bottom: 15px;
    }
    .btn {
      background-color: #007bff;
      border: none;
      color: white;
      padding: 8px 15px;
      text-align: center;
      font-size: 14px;
      border-radius: 5px;
      cursor: pointer;
      margin-left:5px;
    }
    .btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

<div class="top-bar">
  <button class="btn" onclick="downloadPDF()">Download PDF</button>
  <button class="btn" onclick="sharePDF()">Share</button>
</div>

<div class="resume-container" id="resumeContent">
  <div class="left">
    <?php if (!empty($resume['image'])): ?>
      <img src="uploads/<?php echo htmlspecialchars($resume['image']); ?>" alt="Profile Picture">
    <?php endif; ?>

    <h3>Personal Details</h3>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($resume['fullname']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($resume['email_id']); ?></p>
    <p><strong>Mobile:</strong> <?php echo htmlspecialchars($resume['mobile_no']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($resume['Address']); ?></p>
    <p><strong>Marital Status:</strong> <?php echo htmlspecialchars($resume['marital']); ?></p>
    <p><strong>Nationality:</strong> <?php echo htmlspecialchars($resume['nationality']); ?></p>
    <p><strong>Languages:</strong> <?php echo htmlspecialchars($resume['language']); ?></p>

    <?php
      $rated_skills = [];
      $soft_skills = [];

      foreach ($skills_details as $skill) {
          if (is_numeric($skill['rating']) && $skill['rating'] > 0) {
              $rated_skills[] = $skill;
          } else {
              $soft_skills[] = $skill;
          }
      }

      if (!empty($soft_skills)) {
          echo "<h3>Soft Skills</h3>";
          foreach ($soft_skills as $soft) {
              echo "<p>" . htmlspecialchars($soft['skill']) . "</p>";
          }
      }

      if (!empty($rated_skills)) {
          echo "<h3>Skills</h3>";
          foreach ($rated_skills as $rated) {
              echo "<p><strong>" . htmlspecialchars($rated['skill']) . ":</strong> ";
              for ($i = 0; $i < $rated['rating']; $i++) {
                  echo "<span class='star'>★</span>";
              }
              echo "</p>";
          }
      }
    ?>
  </div>

  <div class="right">
    <h2><?php echo htmlspecialchars($resume['fullname']); ?></h2>

    <div class="resume-section">
      <h3>Objective</h3>
      <p><?php echo nl2br(htmlspecialchars($resume['objective'])); ?></p>
    </div>

    <div class="resume-section">
      <h3>Work Experience</h3>
      <?php
      if (!empty($work_details)) {
          foreach ($work_details as $work) {
              $ended = $work['ended'] ? htmlspecialchars($work['ended']) : "Present";
              echo "<div class='work-item'>";
              echo "<p><strong>Role:</strong> " . htmlspecialchars($work['job_role']) . "</p>";
              echo "<p><strong>Company:</strong> " . htmlspecialchars($work['company']) . "</p>";
              echo "<p><strong>Duration:</strong> " . htmlspecialchars($work['started']) . " - " . $ended . "</p>";
              echo "<p><strong>Description:</strong> " . nl2br(htmlspecialchars($work['description'])) . "</p>";
              echo "</div>";
          }
      } else {
          echo "<p>No work experience listed.</p>";
      }
      ?>
    </div>

    <div class="resume-section">
      <h3>Education</h3>
      <?php
      if (!empty($education_details)) {
          foreach ($education_details as $edu) {
              echo "<div class='education-item'>";
              echo "<p><strong>Board:</strong> " . htmlspecialchars($edu['Board']) . "</p>";
              echo "<p><strong>Institute:</strong> " . htmlspecialchars($edu['institute_name']) . "</p>";
              echo "<p><strong>Passing year:</strong> " . htmlspecialchars($edu['end_year']) . "</p>";
              echo "<p><strong>Percentage:</strong> " . htmlspecialchars($edu['percentage']) . "%</p>";
              echo "</div>";
          }
      } else {
          echo "<p>No education details listed.</p>";
      }
      ?>
    </div>
  </div>
</div>

<!-- PDF Export Script -->
<script>
function downloadPDF() {
  const element = document.getElementById('resumeContent');
  html2pdf().from(element).set({
    margin: 0.5,
    filename: 'resume_<?php echo $resume_id; ?>.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
  }).save();
}

function sharePDF() {
  html2pdf().from(document.getElementById('resumeContent')).outputPdf('blob').then(function(pdfBlob) {
    const file = new File([pdfBlob], 'resume_<?php echo $resume_id; ?>.pdf', { type: 'application/pdf' });
    if (navigator.canShare && navigator.canShare({ files: [file] })) {
      navigator.share({
        title: 'My Resume',
        text: 'Please find my resume attached',
        files: [file]
      }).catch((err) => alert('Share failed: ' + err));
    } else {
      alert("Sharing not supported on this device, please download instead.");
    }
  });
}
</script>

</body>
</html>
