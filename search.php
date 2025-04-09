<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "fitzone_fitness");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$searchQuery = '';
$results = [];

// Check if a search query was submitted
if (isset($_GET['query'])) {
    // Sanitize the input to prevent SQL injection
    $searchQuery = $conn->real_escape_string($_GET['query']);

    // SQL query to search across multiple tables
    $sql = "SELECT 'About Us' AS section, content AS result FROM about_us WHERE content LIKE '%$searchQuery%'
            UNION ALL
            SELECT 'Queries' AS section, query_text AS result FROM queries WHERE query_text LIKE '%$searchQuery%' 
            UNION ALL
            SELECT 'Reviews' AS section, review_text AS result FROM reviews WHERE review_text LIKE '%$searchQuery%' 
            UNION ALL
            SELECT 'Vision' AS section, content AS result FROM vision_mission WHERE type='vision' AND content LIKE '%$searchQuery%' 
            UNION ALL
            SELECT 'Mission' AS section, content AS result FROM vision_mission WHERE type='mission' AND content LIKE '%$searchQuery%' 
            UNION ALL
            SELECT 'Blog' AS section, post_title AS result FROM blog WHERE post_title LIKE '%$searchQuery%' 
            UNION ALL
            SELECT 'Contact' AS section, contact_details AS result FROM contact_info WHERE contact_details LIKE '%$searchQuery%' 
            UNION ALL
            SELECT 'Classes' AS section, class_name AS result FROM classes WHERE class_name LIKE '%$searchQuery%'";

    // Execute the query
    try {
        $result = $conn->query($sql);

        // Fetch results if there are any matches
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
        } else {
            $noResultsMessage = "No results found for '$searchQuery'.";
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error in query: " . $e->getMessage();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        /* Style for a neat, readable display */
        .search-container { width: 80%; margin: 0 auto; }
        .search-header { font-size: 1.5em; font-weight: bold; margin-top: 20px; }
        .result-item { margin: 15px 0; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .result-item strong { font-size: 1.2em; color: #333; }
        .result-item p { margin: 5px 0; color: #666; }
        .no-results { font-size: 1.2em; color: #999; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="search-container">
        <div class="search-header">
            Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"
        </div>

        <?php if (!empty($results)): ?>
            <?php foreach ($results as $result): ?>
                <div class="result-item">
                    <strong><?php echo htmlspecialchars($result['section']); ?></strong>: 
                    <p><?php echo htmlspecialchars($result['result']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-results">
                <?php echo isset($noResultsMessage) ? $noResultsMessage : "Please enter a search query."; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
