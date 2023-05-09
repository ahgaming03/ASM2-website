<?php
// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data using filter_input
    $firstname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
    $lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_SPECIAL_CHARS);
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $confirm_password = filter_input(INPUT_POST, "confirm_password", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

    // Check if the password and confirm password fields match
    if ($password !== $confirm_password) {
        echo "Error: Passwords do not match.";
    } else {
        // Include the database connection script
        include("database.php");
        // Hash the password using a secure algorithm like bcrypt
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user information into the database
        $sql = "INSERT INTO users (firstname, lastname, username, password, email, ) 
                VALUES ('$firstname', '$lastname', '$username', '$hashed_password', '$email')";

        try {
            $result = mysqli_query($conn, $sql);

            // Check if the query was successful
            if ($result) {
                echo "Sign up successful! You can log in now!";
                // Close the database connection
                mysqli_close($conn);

                // Delay the header for 5 seconds
                sleep(5);

                // Redirect the user to a new page
                header('Location: login.php');
                exit();
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } catch (mysqli_sql_exception) {
            echo "<script> alert('Username/Email is taken');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("layout_head.php") ?>
    <title>Sign up</title>
</head>

<body>
    <?php include("header.php") ?>

    <div class="container">
        <div class="sign-box">
            <h1>SIGN UP</h1>

            <hr>
            <form class="row g-2" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

                <div class="col-md-6">
                    <label for="firstname" class="form-label">First name</label>
                    <input type="text" minlength="3" class="form-control" id="firstname" name="firstname" required>
                </div>

                <div class="col-md-6">
                    <label for="lastname" class="form-label">Last name</label>
                    <input type="text" minlength="3" class="form-control" id="lastname" name="lastname" required>
                </div>

                <div class="col-12">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" minlength="5" class="form-control" id="username" name="username" required>
                </div>

                <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" minlength="6" class="form-control" id="email" name="email" required
                        placeholder="Example@email.com">
                </div>

                <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" minlength="6" class="form-control" id="password" name="password" required>
                </div>

                <div class="col-12">
                    <label for="confirm_password" class="form-label">Confirm password</label>
                    <input type="password" minlength="6" class="form-control" id="confirm_password"
                        name="confirm_password" required>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="showPass" onclick="showPassword()">
                    <label class="form-check-label" for="showPass">
                        Show password
                    </label>
                </div>

                <?php

                // Display an error message if the password and confirm password fields do not match
                if (isset($_POST['password']) && isset($_POST['confirm_password']) && $_POST['password'] !== $_POST['confirm_password']) {
                    echo "<p class='error'>Error: Passwords do not match.</p>";
                }

                ?>

                <div class="text-end mt-2">
                    <button type="submit" class="btn btn-success" name="signup">Sign up</button>
                </div>
            </form>
            <div class="text-center mt-4">Already have an account? <a href="login.php">Login now</a></div>

        </div>
    </div>

    <script src="js/script.js"></script>
</body>

</html>