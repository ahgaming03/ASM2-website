<?php
// Connect to MySQL database
include("database.php");

// Initialize variables
$username = $password = "";
$username_err = $password_err = "";

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    $username = trim($_POST["username"]);

    // Validate password
    $password = $_POST["password"];

    // Check input errors before accessing database
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if ($stmt->num_rows() == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $id;
                            $_SESSION["username"] = $username;

                            // Redirect to Home page
                            header("location: index.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }
    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("layout_head.php") ?>
    <title>Login</title>
</head>

<body>

<?php include("header.php") ?>

    <div class="container">
        <div class="sign-box">
            <h1>LOGIN</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-floating mt-3 mb-3">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username"
                        required value="<?php echo $username; ?>">
                    <label for="username">Username</label>
                    <span id="error">
                        <?php echo $username_err; ?>
                    </span>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password" id="password"
                        placeholder="Password" required>
                    <label for="password">Password</label>
                    <span id="error">
                        <?php echo $password_err; ?>
                    </span>
                </div>

                <button type="submit" name="login" class="btn btn-success mb-5">Sign in</button>
            </form>
            <div class="text-center">Not registered yet? <a href="signup.php">Create an account</a></div>
        </div>
    </div>

</body>

</html>

