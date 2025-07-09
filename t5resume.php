<?php
// Database setup
session_start();
if (!isset($_GET['id'])) die("No resume ID provided.");

$conn = new mysqli("localhost","root","","project");
if ($conn->connect_error) die("DB connection failed.");

$resume_id = intval($_GET['id']);

// Fetch main resume data
$stmt = $conn->prepare("SELECT * FROM resume WHERE resume_id = ?");
$stmt->bind_param("i", $resume_id);
$stmt->execute();
$res = $stmt->get_result();
$resume = $res->fetch_assoc() ?: die("Resume not found.");
$stmt->close();

// Fetch education entries
$edures = $conn->query("SELECT * FROM education WHERE resume_id = $resume_id");
$education = $edures ? $edures->fetch_all(MYSQLI_ASSOC) : [];

// Fetch work entries
$workres = $conn->query("SELECT * FROM work WHERE resume_id = $resume_id");
$work = $workres ? $workres->fetch_all(MYSQLI_ASSOC) : [];

// Fetch skills
$skillsres = $conn->query("SELECT * FROM skills WHERE resume_id = $resume_id");
$skills = $skillsres ? $skillsres->fetch_all(MYSQLI_ASSOC) : [];

$conn->close();

// Classify skills
$rated = $soft = [];
foreach ($skills as $s) {
  if (is_numeric($s['rating']) && $s['rating']>0) $rated[] = $s;
  else $soft[] = $s;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($resume['fullname']); ?> – Resume</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    /* Your provided CSS, unchanged */
    body {font-family:Arial;margin:0;padding:20px;display:flex;justify-content:center;background:#f0f0f0;}
    .cv-container{display:flex;width:80%;padding:15px;border-radius:10px;position:relative;}
    .plus-icon{position:absolute;top:-15px;left:-15px;background:brown;color:white;font-size:140px;width:110px;height:110px;display:flex;align-items:center;justify-content:center;border-radius:50%;box-shadow:2px 2px 5px rgba(0,0,0,0.3);}
    .left-column{width:30%;padding:15px;background:rgba(139,69,19,0.8);color:white;}
    .right-column{width:70%;padding:15px;background:rgba(255,255,255,0.8);}
    .section{margin-bottom:10px;padding:8px;border-radius:5px;background:transparent;}
    .profile-pic{width:100%;height:auto;border-radius:10px;margin-bottom:5px;}
    .header{display:flex;justify-content:space-between;align-items:center;border-bottom:3px solid black;}
    .header h1{margin:0;font-size:26px;color:#333;}
    .header p{margin:3px 0;font-size:15px;color:#444;}
    .skills .skill-bar{width:100%;background:rgba(0,0,0,0.1);border-radius:5px;margin:4px 0;}
    .skills .skill-fill{height:10px;background:#4CAF50;border-radius:5px;}
    .languages .stars{color:gold;}
    ul{padding-left:15px;} ul li{margin-bottom:3px;}
  </style>
</head>
<body>
  <div class="cv-container">
    <div class="plus-icon">+</div>

    <!-- Left Side -->
    <div class="left-column">
      <div class="section">
        <img src="uploads/<?php echo htmlspecialchars($resume['image']); ?>" class="profile-pic" alt="">
      </div>

      <div class="section">
        <h2><i class="bi bi-person-circle"></i> About Me</h2>
        <p><?php echo nl2br(htmlspecialchars($resume['objective'])); ?></p>
      </div>

      <?php if ($soft): ?>
      <div class="section">
        <h2><i class="bi bi-person-check"></i> Soft Skills</h2>
        <ul><?php foreach ($soft as $s): ?>
          <li><?php echo htmlspecialchars($s['skill']); ?></li>
        <?php endforeach; ?></ul>
      </div>
      <?php endif; ?>

      <?php if (!empty($resume['language'])): ?>
      <div class="section languages">
        <h2><i class="bi bi-translate"></i> Languages</h2>
        <?php foreach (explode(',', $resume['language']) as $lang): ?>
          <p><?php echo htmlspecialchars($lang); ?> <span class="stars">★★★★★</span></p>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Right Side -->
    <div class="right-column">
      <div class="section header">
        <div>
          <h1><?php echo htmlspecialchars($resume['fullname']); ?></h1>
          <p><i class="bi bi-heart-pulse"></i> <?php echo htmlspecialchars($resume['specialization'] ?? ''); ?></p>
        </div>
        <div class="details">
          <p><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($resume['Address']); ?></p>
          <p><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($resume['mobile_no']); ?></p>
          <p><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($resume['email_id']); ?></p>
        </div>
      </div>

      <?php if ($education): ?>
      <div class="section">
        <h2><i class="bi bi-book"></i> Education</h2>
        <?php foreach ($education as $edu): ?>
          <p><strong><?php echo htmlspecialchars($edu['start_year'].'–'.$edu['end_year']); ?></strong><br>
          <?php echo htmlspecialchars($edu['institute_name']); ?> – <b><?php echo htmlspecialchars($edu['percentage']); ?>%</b></p>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ($work): ?>
      <div class="section">
        <h2><i class="bi bi-briefcase-fill"></i> Experience</h2>
        <?php foreach ($work as $w): ?>
          <p><strong><?php echo htmlspecialchars($w['job_role']); ?></strong> – <?php echo htmlspecialchars($w['company']); ?> (<?php echo htmlspecialchars($w['started']).'–'.($w['ended'] ?: 'Present'); ?>)</p>
          <ul><li><?php echo nl2br(htmlspecialchars($w['description'])); ?></li></ul>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ($rated): ?>
      <div class="section skills">
        <h2><i class="bi bi-clipboard2-pulse"></i> Skills</h2>
        <?php foreach ($rated as $s): ?>
          <p><?php echo htmlspecialchars($s['skill']); ?></p>
          <div class="skill-bar"><div class="skill-fill" style="width:<?php echo ($s['rating']*20); ?>%;"></div></div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
