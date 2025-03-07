<?php
// Vulnerable PHP Code Injection Lab

// Simulating a simple GET parameter execution vulnerability
if (isset($_GET['cmd'])) {
    $cmd = $_GET['cmd']; // Get user input
    eval($cmd); // Execute input as PHP code (VULNERABLE)
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Code Injection Lab</title>
</head>
<body>
    <h1>PHP Code Injection Lab</h1>
    <p>Enter PHP code in the URL parameter <code>?cmd=</code> to execute.</p>
</body>
</html>
