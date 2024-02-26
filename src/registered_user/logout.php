<?php
include_once '../config/config.php';

//DISCONNECT USER
$user = new User($conn);
$user->endSession();
echo '<script>window.location.href = "../index.php";</script>';
exit; 
?>
