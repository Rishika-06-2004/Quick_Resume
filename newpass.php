<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quick Resume - Change Password</title>
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
      color: white;
    }

    .container {
      background: rgba(255, 255, 255, 0.08);
      padding: 40px 30px;
      border-radius: 15px;
      backdrop-filter: blur(10px);
      box-shadow: 0px 5px 20px rgba(255, 255, 255, 0.05);
      width: 360px;
      text-align: center;
    }

    .logo-section {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 15px;
      margin-bottom: 25px;
    }

    .logo-section img {
      height: 50px;
      width: 50px;
    }

    .logo-section h1 {
      font-size: 1.7em;
      font-weight: 700;
      line-height: 1.2;
      color: #ff69b4;
    }

    .email-info {
      margin-bottom: 15px;
      font-size: 0.95em;
      color: #ddd;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-bottom: 20px;
    }

    input[type="password"] {
      padding: 12px;
      border-radius: 25px;
      border: none;
      font-size: 1em;
      width: 100%;
      outline: none;
      background-color: #f1f1f1;
      color: #333;
    }

    .verify-btn {
      padding: 12px;
      border-radius: 25px;
      border: none;
      background: linear-gradient(to right, #ff6ec4, #7873f5);
      color: white;
      font-weight: bold;
      font-size: 1.1em;
      cursor: pointer;
      transition: background 0.3s;
    }

    .verify-btn:hover {
      background: linear-gradient(to right, #e44f9e, #5a56d4);
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
  </style>
</head>
<body>
  <div class="container">
    <div class="logo-section">
      <img src="logo1.png" alt="Logo" />
      <h1>QUICK<br>RESUME</h1>
    </div>

    <div class="email-info">
      <?php echo isset($_SESSION['email']) ? "Email: <strong>{$_SESSION['email']}</strong>" : ""; ?>
    </div>

    <div class="form-group">
      <form action="newpassback.php" method="post">
        <input type="password" placeholder="Enter your new password" name="pass" required /><br><br>
        <button class="verify-btn" type="submit">Change Password</button>
      </form>
    </div>

    <div class="bottom-links">
      <a href="reg.html">Register</a>
      <a href="log.html">Login</a>
    </div>
  </div>
</body>
</html>
