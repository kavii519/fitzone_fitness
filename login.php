<?php
session_start();

$host = 'localhost';
$dbname = 'fitzone_fitness';  // Your database name
$db_username = 'root';  // Your MySQL username
$db_password = '';  // Your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check for admin login
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['username'] = 'admin';  // Set username for admin
        $_SESSION['role'] = 'admin';       // Set role for admin
        echo "<script>alert('Login successful!'); window.location.href = 'admin_dashboard.php';</script>";
        exit();
    }

    // Check for staff login
    if ($username === 'staff' && $password === 'staff') {
        $_SESSION['username'] = 'staff';  // Set username for staff
        $_SESSION['role'] = 'staff';       // Set role for staff
        echo "<script>alert('Login successful!'); window.location.href = 'staff_dashboard.php';</script>";
        exit();
    }

    // Check for customer login
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = 'customer';
        echo "<script>alert('Login successful!'); window.location.href = 'FitZone.php';</script>";
        exit();
    } else {
        // Invalid login credentials
        echo "<script>alert('Invalid login credentials. Please check your username and password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
       <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body 
        {
            font-family: Times, sans-serif;
            font-size: 24px;
            height: auto;
            display: flex;
            flex-direction: column;
            background: url('my_fitness.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }
        .header 
        {
            display: flex;
            font-size: 36px;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: rgba(0, 123, 255, 0.7);
        }
        h2
        {
            margin: 0;
            font-size: 36px;
        }
        .container 
        {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            margin-top: 20px;
            text size: 24px;
        }
        input, select, button 
        {
            padding: 10px;
            background-color: #fff;
            color: rgb(0,123,255);
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button 
        {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        button:hover 
        {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class = "container">
    <h2>Login</h2>
    <br><br><br>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
