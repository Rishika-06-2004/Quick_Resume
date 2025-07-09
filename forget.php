<?php
// Start session if needed
session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quick Resume</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: linear-gradient(to right, #6a11cb, #2575fc);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
    }

    .container {
      background: rgba(255, 255, 255, 0.15);
      padding: 40px 30px;
      border-radius: 15px;
      backdrop-filter: blur(10px);
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
      width: 350px;
      text-align: center;
    }

    .logo-section {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 15px;
      margin-bottom: 30px;
    }

    .logo-section img {
      height: 50px;
      width: 50px;
    }

    .logo-section h1 {
      font-size: 1.8em;
      font-weight: bold;
      line-height: 1.2;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-bottom: 20px;
    }

    input[type="email"] {
      padding: 12px;
      border-radius: 25px;
      border: none;
      font-size: 1em;
      width: 100%;
      outline: none;
    }

    .verify-btn {
      padding: 12px;
      border-radius: 25px;
      border: none;
      background-color: #ff4757;
      color: white;
      font-weight: bold;
      font-size: 1.1em;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .verify-btn:hover {
      background-color: #e84118;
    }

    .bottom-links {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
      font-size: 0.95em;
    }

    .bottom-links a {
      color: #ffffff;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s;
    }

    .bottom-links a:hover {
      color: #dcdde1;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo-section">
      <img src="logo1.png" alt="Logo" />
      <h1>QUICK <br> RESUME 🚀</h1>
    </div>
    <div class="form-group">
	<form action="code.php" method="post">
<?php
if (isset($_GET['msg']))
{?>
<h6 style="color:red;"> <?php echo $_GET['msg'];
}?> </h6>
      <input type="email" placeholder="Enter your email" name="email" required /><br>
      <button class="verify-btn">Send Verification Code</button>
	</form>
    </div>
    <div class="bottom-links">
      <a href="reg.html">Register</a>
      <a href="log.html">Login</a>
    </div>
  </div>
</body>
</html>
