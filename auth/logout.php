<?php
require_once '../includes/auth.php';
logoutUser();
header("Location: /fallos_itesco/auth/login.php");
exit();
?>
