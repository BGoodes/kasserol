<?php
include_once 'config/config.php';

// Initialize error variables
$firstNameErr = $lastNameErr = $emailErr = $passwordErr = $confirmPasswordErr = $phoneErr = "";

// Check if email, firstName, lastName, password, and phone keys exist in $_POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create an instance of the asso class
    $user = new Material($conn);

    // Continue with the registration process
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : "";
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : "";
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : "";
    $phone = isset($_POST['phone']) ? $_POST['phone'] : "";

}
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
        <!-- Header -->
        <?php include 'components/header.php';?>




        <!-- Footer -->
        <?php include 'components/footer.php'; ?>

        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>
