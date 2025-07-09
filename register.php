<?php
include('Connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input and trim spaces
    $id = trim($_REQUEST['id']);
    $email = trim($_REQUEST['email']);
    $password = trim($_REQUEST['pass']);

    // SQL Query
    $sql = "INSERT INTO user VALUES ('$id', '$email', '$password')";

    // Execute Query
    $rs = mysqli_query($con, $sql);

    // Close the database connection
    mysqli_close($con);

    // Success or failure message
    if ($rs) {
        $message = "Registration Successful!";
        $icon = "success";
        $redirect = "log.php"; // Redirect to login page
    } else {
        $message = "May be already have an account!";
        $icon = "error";
        $redirect = "log.php"; // Stay on register page
    }
}
?>

<!-- Load SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    Swal.fire({
        icon: '<?php echo $icon; ?>',
        title: '<?php echo $message; ?>',
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = '<?php echo $redirect; ?>';
        }
    });
</script>
