<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quick Resume - Verify Email</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: linear-gradient(to right, #0f0c29, #302b63, #24243e);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #ffffff;
    }

.container {
  background: rgba(255, 255, 255, 0.07);
  padding: 55px 45px;
  border-radius: 25px;
  backdrop-filter: blur(14px);
  box-shadow: 0 0 30px rgba(255, 105, 180, 0.25);
  width: 490px;
  height: 400px;
  max-width: 90%;
  text-align: center;
}

    .logo-section {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      margin-bottom: 25px;
    }

    .logo-section img {
      height: 48px;
      width: 48px;
    }

    .logo-section h1 {
      font-size: 1.8em;
      font-weight: 700;
      color: #ff69b4;
      line-height: 1.2;
    }

    .form-group {
      margin-bottom: 25px;
    }

    input[type="email"] {
      padding: 14px 18px;
      border-radius: 30px;
      border: none;
      font-size: 1em;
      width: 100%;
      outline: none;
      background-color: #f1f1f1;
      color: #333;
    }

    .verify-btn {
      padding: 12px;
      width: 100%;
      border-radius: 30px;
      border: none;
      background: linear-gradient(to right, #ff6ec4, #7873f5);
      color: white;
      font-weight: 600;
      font-size: 1.05em;
      cursor: pointer;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .verify-btn:hover {
      transform: scale(1.03);
      box-shadow: 0 0 15px rgba(255, 105, 180, 0.3);
    }

    .bottom-links {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
      font-size: 0.92em;
    }

    .bottom-links a {
      color: #ffb6f1;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s;
    }

    .bottom-links a:hover {
      color: #ffffff;
    }

    h6.error {
      margin-bottom: 10px;
      color: #ffb3b3;
      font-weight: 600;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo-section">
      <img src="logo1.png" alt="Logo" />
      <h1>QUICK<br>RESUME</h1>
    </div>
    
    <div class="form-group">
      <form action="code.php" method="post">
        <?php if (isset($_GET['msg'])): ?>
          <h6 class="error"><?php echo $_GET['msg']; ?></h6>
        <?php endif; ?>

        <input type="email" placeholder="Enter your email" name="email" required /><br><br>
        <button class="verify-btn" type="submit">Send Verification Code</button>
      </form>
    </div>

    <div class="bottom-links">
      <a href="reg.html">Register</a>
      <a href="log.html">Login</a>
    </div>
  </div>
</body>
</html>
