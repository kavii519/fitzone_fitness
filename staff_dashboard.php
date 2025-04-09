<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'fitzone_fitness';  // Your database name
$db_username = 'root';  // Your MySQL username
$db_password = '';  // Your MySQL password


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: unauthorized.php");
    exit();
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Fetch customer queries
$query_stmt = $conn->prepare("SELECT * FROM queries");
$query_stmt->execute();
$queries = $query_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Staff Dashboard</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }
        form {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; }
        th, td { 
             border: 1px solid #ccc; 
             padding: 10px; 
             text-align: left; }
        th { 
            background-color: #f2f2f2; }
    </style>
    </style>
</head>
<body>
    <h2>Staff Dashboard</h2>
    <h3>Customer Queries</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Query</th>
            <th>Date</th>
            <th>Response</th>
        </tr>
        <?php foreach ($queries as $query): ?>
            <tr>
                <td><?php echo htmlspecialchars($query['id']); ?></td>
                <td><?php echo htmlspecialchars($query['name']); ?></td>
                <td><?php echo htmlspecialchars($query['message']); ?></td>
                <td><?php echo htmlspecialchars($query['date']); ?></td>
                <td>
                    <form method="POST" action="respond_query.php">
                        <input type="hidden" name="query_id" value="<?php echo htmlspecialchars($query['id']); ?>">
                        <input type="text" name="response" placeholder="Type your response" required>
                        <button type="submit">Respond</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    <a href="FitZone.php">Back to Home</a>
</body>
</html>
