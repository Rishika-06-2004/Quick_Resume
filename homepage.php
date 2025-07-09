<?php
session_start();
if(!isset($_session['login'])||$_session['login']!=true)
{
	header("location:login.php");
	exit;	
}
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, black, pink);
        }

        .navbar {
            background-color: rgba(255, 105, 180, 0.9);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(4px);
        }
        .btn-container {
            display: flex;
            gap: 10px;
        }
        .navbar button {
            background-color: #fff;
            color: #FF66CC;
            padding: 10px 20px;
            border: 1px solid #FF00FF;
            border-radius: 5px;
            cursor: pointer;
        }
        .navbar button:hover {
            background-color: #FFC0CB;
            color: purple;
        }
        .quick-resume {
            display: flex;
            align-items: center;
        }
        .quick-resume img {
            width: 30px;
            height: 30px;
            margin-right: 5px;
        }
        .quick-resume span {
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        /* Dots icon */
        .dots {
            font-size: 24px;
            color: white;
            cursor: pointer;
            margin-left: 10px;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            right: -250px;
            width: 250px;
            height: 100%;
            background-color: rgba(255, 182, 193, 0.95);
            padding: 20px;
            transition: right 0.3s ease-in-out;
            z-index: 9999;
            box-shadow: -4px 0 8px rgba(0,0,0,0.2);
            backdrop-filter: blur(4px);
        }
        .sidebar.active {
            right: 0;
        }
        .sidebar h3 {
            color: #8B0000;
            margin-bottom: 20px;
        }
        .sidebar a {
            display: block;
            color: #333;
            text-decoration: none;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .sidebar a:hover {
            color: #FF1493;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 28px;
            font-weight: bold;
            color: #C71585;
            cursor: pointer;
        }

        .container {
            display: flex;
            padding: 20px;
            gap: 20px;
        }
.left-column {
    flex: 1;
    background-color: rgba(255, 201, 225, 1.0); /* Light pink */
    padding: 20px;
    border-radius: 10px;
    backdrop-filter: blur(4px);
}

.right-column {
    flex: 1;
    background-color: rgba(255, 201, 225, 1.0); /* Light pink */
    padding: 20px;
    border-radius: 10px;
    backdrop-filter: blur(4px);
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}


        .btn {
            background-color: #FF8C00;
            color: white;
            padding: 12px 25px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #FF8C00;
        }

        .steps-container {
            background-color: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(3px);
            padding: 60px 20px;
            text-align: center;
            color: white;
            border-radius: 10px;
        }
        .steps-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 40px;
            color: black;
            background-color: #FFDAB9;
            border-radius: 8px;
        }
        .steps-wrapper {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            max-width: 1100px;
            margin: 0 auto;
            flex-wrap: nowrap;
        }
        .step {
            width: 30%;
            background-color: rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .step:hover {
            transform: translateY(-5px);
        }
        .step-icon {
            width: 100px;
            height: 120px;
            margin-bottom: 15px;
        }
        .step h3 {
            font-size: 18px;
            color: indigo;
            margin-bottom: 10px;
        }
        .step p {
            font-size: 14px;
            color: black;
            line-height: 1.6;
        }

        .template-section {
            text-align: center;
            padding: 50px 20px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            backdrop-filter: blur(3px);
        }
        .template-title {
            font-size: 32px;
            margin-bottom: 10px;
            color:#C71585;
        }
        .bold-text {
            font-weight: bold;
        }
        .template-subtitle {
            font-size: 14px;
            color: purple;
            font-style: italic;
            margin-bottom: 40px;
        }
        .template-wrapper {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .template-card {
            width: 550px;
            height: 240px;
            background-color: rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            backdrop-filter: blur(2px);
        }
        .template-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .template-card:hover {
            transform: translateY(-5px);
        }

        .footer-container {
            background-color: rgba(255, 182, 193, 0.5);
            padding: 40px 20px;
            color: #333;
            border-radius: 10px;
            backdrop-filter: blur(2px);
        }
        .footer-content {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }
        .footer-column {
            text-align: center;
            margin-bottom: 20px;
        }
        .footer-column h3 {
            font-size: 18px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .social-icons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .social-icons img {
            width: 24px;
            height: 24px;
            vertical-align: middle;
            margin-right: 8px;
        }
        .footer-bottom {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
        .footer-bottom .disclaimer {
            margin-top: 10px;
            font-style: italic;
            color: #888;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="quick-resume">
            <img src="logo1.png" alt="Resume Logo">
            <span>Quick Resume</span>
        </div>
        <div style="display: flex; align-items: center;">
            <div class="btn-container">  
                <button>Edit</button>
                <button>Delete</button>
                <button>Clone</button>
                <button>Copy</button>
            </div>
            <div class="dots" onclick="toggleSidebar()">&#x22EE;</div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <span class="close-btn" onclick="toggleSidebar()">&times;</span>
        <h3>Menu</h3>
        <a href="#">About Us</a>
        <a href="#">Contact Us</a>
        <a href="#">Help</a>
        <a href="#">Privacy</a>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="left-column"><br><br>
            <h1 style="color: tomato"><b><i>IMPRESSIVE RESUMES</i></b></h1>
            <h2 style="color: maroon">ONLINE BUILDER</h2><br><br><br>
            <ul>
               <li>Professional out-of-box resumes, instantly generated by the most advanced resume builder technology available.</li><br>
               <li>Efforts crafting. Real-time preview & pre-written resume examples. Dozens of HR-approved resume templates.</li><br>
               <li>Land your dream job with the perfect resume employers are looking for!</li><br><br><br>
            </ul>
        </div>
        <div class="right-column" style="padding-left:100px">
            <img src="laptop.jpg" alt="laptop" style="width: 630px;">
        </div>
    </div>
    <button class="btn" style="margin-top: 30px; margin-left: 50px;">BUILD MY RESUME</button>
    <br><br><br>

    <!-- Steps Section -->
    <div class="steps-container">
        <h2 class="steps-title">3 EASY STEPS TO CREATE YOUR PERFECT RESUME</h2>
        <div class="steps-wrapper">
            <div class="step">
                <img src="res.jpg" alt="Template Icon" class="step-icon">
                <h3><b>CHOOSE YOUR<br>RESUME TEMPLATE</b></h3>
                <p>Our professional resume templates are designed strictly following all industry guidelines and best practices that employers look for.</p>
            </div>
            <div class="step">
                <img src="pen.jpg" alt="Showcase Icon" class="step-icon">
                <h3><b>SHOW WHAT<br>YOU'RE MADE OF</b></h3>
                <p>We’ve added thousands of pre-written examples and resume samples. As easy as clicking.</p>
            </div>
            <div class="step">
                <img src="res_down.png" alt="Download Icon" class="step-icon">
                <h3><b>DOWNLOAD<br>YOUR RESUME</b></h3>
                <p>Start impressing employers. Download your awesome resume and land the job you are looking for, effortlessly.</p>
            </div>
        </div>
    </div>

    <!-- Template Section -->
    <div class="template-section">
        <h2 class="template-title">
            <span class="bold-text">PROFESSIONAL</span> RESUME TEMPLATES
        </h2>
        <p class="template-subtitle">
            CHOOSE FROM 5+ TAILORED-BUILT TEMPLATES THAT HAVE LANDED THOUSANDS OF PEOPLE LIKE YOU THE JOBS THEY WERE DREAMING OF.
        </p>

        <div class="template-wrapper">
            <div class="template-card">
                <img src="temp1.jpg" alt="Resume Template 1">
            </div>
            <div class="template-card">
                <img src="temp2.jpg" alt="Resume Template 2">
            </div>
            <div class="template-card">
                <img src="" alt="Resume Template 3">
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer-container">
        <div class="footer-content">
            <div class="footer-column">
                <h3 style="color: brown">STAY CONNECTED</h3>
                <div class="social-icons">
                    <div><img src="phone.jpg" alt="Phone">+91-9876543210</div>
                    <div><img src="facebook.png" alt="Facebook">Gouri Ghosh</div>
                    <div><img src="instagram.webp" alt="Instagram">It_s_gouri</div>
                    <div><img src="gmail.png" alt="Gmail">gourigghosh@gmail.com</div>
                </div>        
            </div>
        </div>
        <div class="footer-bottom">
            <p>Copyright © 2025. All rights reserved.</p>
            <p class="disclaimer">The information on this site is provided as a courtesy. First Timer Resume is not a career or legal advisor and does not guarantee job interviews or offers.</p>
        </div>
    </footer>

    <!-- Sidebar toggle script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("active");
        }
    </script>

</body>
</html>
