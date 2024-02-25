<?php
include_once 'config/database.php';
include_once 'assets/models/user.php';

// Initialize error variables
$firstNameErr = $lastNameErr = $emailErr = $passwordErr = $confirmPasswordErr = $phoneErr = "";

// Check if email, firstName, lastName, password, and phone keys exist in $_POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create an instance of the User class
    $user = new User($conn);

    // Continue with the registration process
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : "";
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : "";
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : "";
    $phone = isset($_POST['phone']) ? $_POST['phone'] : "";

    // Validate form fields and set error messages
    if (empty($firstName)) {
        $firstNameErr = "First Name is required";
    }

    if (empty($lastName)) {
        $lastNameErr = "Last Name is required";
    }

    if (empty($email)) {
        $emailErr = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }

    if (empty($password)) {
        $passwordErr = "Password is required";
    } elseif (strlen($password) < 6) {
        $passwordErr = "Password must be at least 6 characters long";
    }

    if (empty($confirmPassword)) {
        $confirmPasswordErr = "Confirm Password is required";
    } elseif ($password !== $confirmPassword) {
        $confirmPasswordErr = "Passwords do not match";
    }

    if (empty($phone)) {
        $phoneErr = "Phone number is required";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $phoneErr = "Invalid phone number format";
    }

    // Check if there are no errors before proceeding
    if (empty($firstNameErr) && empty($lastNameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr) && empty($phoneErr)) {
        // Perform user registration using the User class methods
        $registrationResult = $user->registerUser($firstName, $lastName, $email, $password, $phone);

        if ($registrationResult) {
            echo 'Registration successful!';
        } else {
            echo 'Registration failed. Please try again.';
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="assets">

    <title>Kasserol - Register</title>
</head>
<body>
    <?php include 'components/header.php'; ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3">
                <h2>Register</h2>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" class="form-control" name="firstName" value="<?php echo isset($firstName) ? $firstName : ''; ?>">
                        <span class="text-danger"><?php echo isset($firstNameErr) ? $firstNameErr : ''; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" class="form-control" name="lastName" value="<?php echo isset($lastName) ? $lastName : ''; ?>">
                        <span class="text-danger"><?php echo isset($lastNameErr) ? $lastNameErr : ''; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" class="form-control" name="phone" value="<?php echo isset($phone) ? $phone : ''; ?>">
                        <span class="text-danger"><?php echo isset($phoneErr) ? $phoneErr : ''; ?></span>
                    </div>


                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email : ''; ?>">
                        <span class="text-danger"><?php echo isset($emailErr) ? $emailErr : ''; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password">
                        <span class="text-danger"><?php echo $passwordErr; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password:</label>
                        <input type="password" class="form-control" name="confirmPassword">
                        <span class="text-danger"><?php echo $confirmPasswordErr; ?></span>
                    </div>

                    <button type="submit" class="btn btn-primary">Register</button>
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
