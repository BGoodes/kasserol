<?php
ob_start();

include_once 'config/config.php';

// Initialize error variables
$emailErr = $passwordErr = "";

// Check if email and password keys exist in $_POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create an instance of the User class
    $user = new User($conn);

    // Continue with the login process
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";

    // Validate form fields and set error messages
    if (empty($email)) {
        $emailErr = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }

    if (empty($password)) {
        $passwordErr = "Password is required";
    }

    // Check if there are no errors before proceeding
    if (empty($emailErr) && empty($passwordErr)) {
        // Perform user login using the User class method
        $userId = $user->loginUser($email, $password);

        if ($userId) {
            // Start user session
            $user->startSession($userId);

            echo 'Login successful! User ID: ' . $userId;
            // Add further actions after successful login if needed
            header("Location: registered_user/profile.php");
            exit;
        } else {
            echo 'Invalid email or password. Please try again.';
        }
    }
}

ob_end_flush();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="assets"> <!-- Include your custom styles if needed -->

    <title>Kasserol - Login</title>
</head>
<body>
<?php include 'components/header.php'; ?>
    <!-- Include your header here -->

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3">
                <h2>Login</h2>
                <form method="POST" action="/login.php">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ""; ?>">
                        <span class="text-danger"><?php echo $emailErr; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password">
                        <span class="text-danger"><?php echo $passwordErr; ?></span>
                    </div>

                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>
