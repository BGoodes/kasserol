<?php
include_once 'config/config.php';

// Create an instance of the User class
$user = new User($conn);

// Check if the user is logged in
$isUserLoggedIn = $user->isUserLoggedIn();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Kasserol</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            
            <!-- Ajout des liens vers les pages publiques -->
            <?php if (!$isUserLoggedIn) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            <?php } else { ?>
                <!-- Additional links for logged-in users can be added here -->
                <!-- For example, a link to the user's profile or a logout option -->
                <li class="nav-item">
                    <a class="nav-link" href="registered_user/profile.php">
                        Profile (<?php echo $user->getUserName($_SESSION['user_id']); ?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="registered_user/logout.php">Logout</a>
                </li>
            <?php } ?>
            <!-- Fin des liens vers les pages publiques -->

        </ul>
    </div>
</nav>
