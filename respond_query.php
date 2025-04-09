<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: unauthorized.php");
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'fitzone_fitness';  // Your database name
$db_username = 'root';  // Your MySQL username
$db_password = '';  // Your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query_id'], $_POST['response'])) {
    $query_id = $_POST['query_id'];
    $response = $_POST['response'];

    // Update the query with the response
    $stmt = $conn->prepare("UPDATE queries SET response = :response WHERE id = :id");
    $stmt->execute(['response' => $response, 'id' => $query_id]);

    echo "<script>alert('Response submitted successfully!'); window.location.href = 'staff_dashboard.php';</script>";
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'staff_dashboard.php';</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query_id'], $_POST['response'])) {
    $query_id = $_POST['query_id'];
    $response = $_POST['response'];

    // Update the query with the response
    $stmt = $conn->prepare("UPDATE queries SET response = :response WHERE id = :id");
    $stmt->execute(['response' => $response, 'id' => $query_id]);
echo "<script>alert('Response submitted successfully!'); window.location.href = 'staff_dashboard.php';</script>";
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'staff_dashboard.php';</script>";
}
?>
