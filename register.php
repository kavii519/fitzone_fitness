<?php
session_start();

$host = 'localhost';
$dbname = 'fitzone_fitness';  // Your database name
$username = 'root';  // Your MySQL username
$password = '';  // Your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if (isset($_POST['register'])) {
    $reg_username = $_POST['reg_username'];
    $reg_password = $_POST['reg_password'];
    $reg_password = $_POST['reg_email'];
    $hashed_password = password_hash($reg_password, PASSWORD_BCRYPT);
    
    // Default role for new users (assuming they are customers)
    $role = 'customer';

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$reg_username]);
    if ($stmt->fetch()) {
        echo "<script>alert('Username already exists.');</script>";
    } else {
        // Insert new user with the customer role
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$reg_username, $hashed_password, $role]);
        echo "<script>alert('Registration successful!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
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
    <h2>Customer Registration</h2>
    <br><br>
    <form method="POST" action="">
        <label for="reg_username">Username:</label>
        <input type="text" id="reg_username" name="reg_username" required>
        <br><br><br>
        <label for="reg_email">Email:</label>
        <input type="email" id="reg_email" name="reg_email" required>
        <br><br><br>
        <label for="reg_password">Password:</label>
        <input type="password" id="reg_password" name="reg_password" required>
        <br><br><br>
        <button type="submit" name="register">Register</button>
    </form>
    </div>
</body>
</html>
