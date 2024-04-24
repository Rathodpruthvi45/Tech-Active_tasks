<?php
session_start();

// Generate a random token
$token = bin2hex(random_bytes(16));

// Store the token in the session
$_SESSION['token'] = $token;

// Return the token as JSON response
echo json_encode(array("token" => $token));
?>