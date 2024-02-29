<?php
include_once __DIR__ . '/../config/config.php';

// Create an instance of the User class
$user = new User($conn);

// Check if the user is logged in
$isUserLoggedIn = $user->isUserLoggedIn();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/index.php">Kasserol</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/index.php">Home</a>
            </li>

            <!-- Ajout des liens vers les pages publiques -->
            <?php if (!$isUserLoggedIn) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/login.php">Login</a>
                </li>
            <?php } else { ?>
                <!-- Additional links for logged-in users can be added here -->
                <!-- For example, a link to the user's profile or a logout option -->
                <li class="nav-item">
                    <a class="nav-link" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/registered_user/profile.php">
                        Profile (<?php echo $user->getUserName($_SESSION['user_id']); ?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/registered_user/logout.php">Logout</a>
                </li>
            <?php } ?>
                <li class="nav-item">
                    <a class="nav-link" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/list_assos.php">Associations</a>
                </li>
            <!-- Fin des liens vers les pages publiques -->

        </ul>
    </div>
</nav>
