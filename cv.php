<?php
$profilePicPath = '';
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) mkdir($targetDir);
    $profilePicPath = $targetDir . basename($_FILES["profile_pic"]["name"]);
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $profilePicPath);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CV</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
 
  <style>
    body {
      background-color: #ccc;
      padding: 20px;
      font-family: 'Segoe UI', sans-serif;
    }
.left-panel p,
.left-panel ul,
.left-panel h5,
.left-panel li,
.section-title,
.section-title-left {
  text-align: left;
}
    #downloadBtn {
      display: block;
      margin-left: auto;
      margin-bottom: 20px;
    }

    #cv {
      width: 210mm;
      min-height: 297mm;
      margin: auto;
      background-color: #fff;
      display: flex;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .left-panel {
      width: 40%;
      background-color: #6c4d2b;
      color: white;
      padding: 20px;
    }

    .right-panel {
      width: 60%;
      padding: 20px;
    }

    .profile-pic {
      width: 100%;
      max-width: 160px;
      border-radius: 50%;
      margin-bottom: 20px;
      margin-top: 80px;
    }

    h1 {
      font-size: 42px;
      font-weight: bold;
      margin-bottom: 0;
      margin-top: 150px;
    }

    h5.designation {
      font-size: 18px;
      margin-top: 5px;
      margin-bottom: 20px;
    }

    .section-title {
      margin-top: 25px;
      font-size: 28px;
      font-weight: bold;
      border-bottom: 1px solid #ccc;
      padding-bottom: 4px;
    }
.section-title-left {
      margin-top: 25px;
      font-size: 18px;
      font-weight: bold;
      border-bottom: 1px solid #ccc;
      padding-bottom: 4px;
    }

    .star {
      color: gold;
    }
  </style>
</head>
<body>

<!-- ✅ Download Button OUTSIDE the A4 page -->
<button id="downloadBtn" class="btn btn-success">Download as PDF</button>

<!-- 📄 CV START -->
<div id="cv">
  <!-- LEFT PANEL -->
  <div class="left-panel text-center">
    <?php if ($profilePicPath): ?>
      <img src="<?= $profilePicPath ?>" class="profile-pic" alt="Profile Picture">
    <?php endif; ?>
    <h5 class="section-title-left"> <i class="bi bi-person-circle"></i>  Personal Info</h5>     <p><strong><i class="bi bi-telephone-forward"></i> Phone:</strong> <?= $_POST['phone'] ?></p>
    <p><strong><i class="bi bi-envelope-at"></i>  Email:</strong> <?= $_POST['email'] ?></p>
    <p><strong><i class="bi bi-geo-alt-fill"></i> Location:</strong> <?= $_POST['location'] ?></p>
    <?php if ($_POST['linkedin_qs'] == 'yes'): ?>
      <p><strong>LinkedIn:</strong> <?= $_POST['linkedin'] ?></p>
    <?php endif; ?>

    <div class="section-title-left"> <i class="bi bi-laptop-fill"></i>  Skills</div>
    <?php if (!empty($_POST['skills'])): ?>
      <?php foreach ($_POST['skills'] as $index => $skill): ?>
        <p><?= $skill ?>:
          <?php
          $rating = $_POST['skill_ratings'][$index];
          for ($i = 0; $i < $rating; $i++) echo '<span class="star">&#9733;</span>';
          for ($i = $rating; $i < 5; $i++) echo '<span class="star text-white">&#9734;</span>';
          ?>
        </p>
      <?php endforeach; ?>
    <?php endif; ?>

    <div class="section-title-left"> <i class="bi bi-file-earmark-person-fill"></i>  Soft Skills</div>
    <?php if (!empty($_POST['soft_skills'])): ?>
      <ul class="list-unstyled">
        <?php foreach ($_POST['soft_skills'] as $soft): ?>
          <li><?= $soft ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <div class="section-title-left"> <i class="bi bi-chat-dots"></i>  Languages</div>
    <p>English, Hindi, Bengali</p>
  </div>

  <!-- RIGHT PANEL -->
  <div class="right-panel">
    <h1><?= $_POST['fullname'] ?></h1> <hr>
  <div class="section-title"> <i class="bi bi-file-earmark-person"></i>  Objective </div>
    <h5 class="designation"><?= $_POST['objective'] ?></h5>

    <div class="section-title"> <i class="bi bi-bag-check-fill"></i>  Work Experience</div>
   <?php if (!empty($_POST['company_name'])): ?> 
     <p> <?php foreach ($_POST['company_name'] as $index => $company): ?>
        <div>
            <h3><strong><?= $company ?></strong><br>   </h3>
         <p style="font-size:25px"> <small>
            <?= $_POST['joining_year'][$index] ?> -
            <?= isset($_POST["till_present_" . ($index + 1)]) ? "Present" : $_POST['resign_year'][$index] ?>
          </small><br></p>
          <ul>
            <?php
            $responsibilities = explode(',', $_POST['responsibilities'][$index]);
            foreach ($responsibilities as $r) {
              echo "<li>" . trim($r) . "</li>";
            }
            ?>
          </ul>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
</p>
    <div class="section-title"> <i class="bi bi-mortarboard-fill"></i>  Education</div>
    <?php if ($_POST['has_masters'] == 'yes'): ?>
      <p><strong>Master's:</strong>  <br>
        <?= $_POST['masters_university'] ?> (<?= $_POST['masters_year'] ?>) - <?= $_POST['masters_percentage'] ?>%
      </p>
    <?php endif; ?>

    <?php if ($_POST['has_bachelor'] == 'yes'): ?>
      <h4><strong>Bachelor's:</strong> <br> </h4>
      <p>  <?= $_POST['bachelor_university'] ?> (<?= $_POST['bachelor_year'] ?>) - <?= $_POST['bachelor_percentage'] ?>%
      </p>
    <?php endif; ?>

    <h4><strong>Higher Secondary:</strong><br> </h4>
      <p><?= $_POST['hs_school'] ?>, <?= $_POST['hs_board'] ?> (<?= $_POST['hs_year'] ?>) - <?= $_POST['hs_percentage'] ?>%
    </p>

    <h4><strong>10th Class:</strong><br></h4>
    <p>  <?= $_POST['tenth_school'] ?>, <?= $_POST['tenth_board'] ?> (<?= $_POST['tenth_year'] ?>) - <?= $_POST['tenth_percentage'] ?>%
    </p>
  </div>
</div>
<!-- 📄 CV END -->

<!-- ✅ PDF Download Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
  document.getElementById("downloadBtn").addEventListener("click", function () {
    var element = document.getElementById("cv");
    var opt = {
      margin: 0,
      filename: 'my_cv.pdf',
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save();
  });
</script>

</body>
</html>
