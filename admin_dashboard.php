<?php
session_start();

// Database configuration
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

// Check if the user is logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "You are not authorized to view this page."; // Message for unauthorized access
    header("Refresh: 2; url=login.php"); // Redirect to login page after 2 seconds
    exit();
}

// Fetch bookings from the database
$sql = "SELECT  username, class_name, appointment_date FROM bookings";
$stmt = $conn->prepare($sql);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch customer queries from the database
$querySql = "SELECT id, name, email, message, date FROM queries"; // Ensure your queries table is correct
$queryStmt = $conn->prepare($querySql);
$queryStmt->execute();
$queries = $queryStmt->fetchAll(PDO::FETCH_ASSOC);

// Start the HTML structure
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    
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
</head>
<body>
 </div>
    <h2>Admin Dashboard</h2>

    <h3>Customer Queries</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($queries)): ?>
                <?php foreach ($queries as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td>
                            <button onclick="document.getElementById('responseForm<?php echo $row['id']; ?>').style.display='block'">Reply</button>
                            <div id="responseForm<?php echo $row['id']; ?>" style="display:none; margin-top:10px;">
                                <form method="POST" action="respond_query.php"> <!-- Point to your response handling script -->
                                    <input type="hidden" name="query_id" value="<?php echo $row['id']; ?>">
                                    <textarea name="response" rows="4" cols="50" placeholder="Type your response here..." required></textarea><br>
                                    <button type="submit">Send Response</button>
                                    <button type="button" onclick="document.getElementById('responseForm<?php echo $row['id']; ?>').style.display='none'">Cancel</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No queries found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Bookings</h2>

    <?php if ($appointments): ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Class Name</th>
                    <th>Appointment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['username']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['class_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_date'] ? date('Y-m-d H:i:s', strtotime($appointment['appointment_date'])) : 'N/A'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No appointments found.</p>
    <?php endif; ?>

<a href="FitZone.php">Back to Home</a>

</body>
</html>
