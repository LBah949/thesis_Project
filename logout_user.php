<?php
session_start();
session_unset();
session_destroy();
header("Location: login_combined.php");
exit;
// This file is used to log out the user and redirect them to the login page.
// It ensures that the session is cleared and the user is redirected properly.