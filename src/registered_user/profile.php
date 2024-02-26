<?php
// Include the header
include '../components/header.php';

// Check if the user is logged in
if ($user->isUserLoggedIn()) {
    // Get user ID from session
    $userId = $_SESSION['user_id'];

    // Get user information
    $userData = $user->getUserData($userId);

    // Check if the form is submitted for updating user information
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate and update user information
        $updatedFirstName = $user->filterStringPolyfill(htmlspecialchars($_POST['firstName']));
        $updatedLastName = $user->filterStringPolyfill(htmlspecialchars($_POST['lastName']));
        $updatedEmail = $user->filterEmailPolyfill(htmlspecialchars($_POST['email']));
        $updatedPhone = $user->filterStringPolyfill(htmlspecialchars($_POST['phone']));

        // Additional validation can be added here

        // Update user information
        $user->updateUserInfo($userId, $updatedFirstName, $updatedLastName, $updatedEmail, $updatedPhone);

        // Refresh user data after updating
        $userData = $user->getUserData($userId);
    }

    // Include the HTML skeleton
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <!-- Custom CSS -->
        <link rel="stylesheet" href="assets/style.css"> <!-- Replace 'style.css' with your actual custom styles file -->

        <title>Kasserol</title>  
    </head>
    <body>

        <div class="container mt-5">
            <h2>Profile</h2>
            <p>Welcome, <?php echo $userData['firstName']; ?>!</p>

            <!-- Editable form for user information -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $userData['firstName']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $userData['lastName']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $userData['email']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $userData['phone']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>

        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    </body>
    </html>
    <?php
} else {
    // Redirect to the login page if not logged in
    header("Location: ../login.php");
    exit();
}

// Include the footer
include '../components/footer.php';
?>
