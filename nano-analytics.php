<?php
// DB settings
$servername = "localhost";
$username = "tester";
$password = "testing";
$dbname = "nano_analytics";

if (isset($_GET['uuid'])) {
    // New statistics entry
    $uuid_pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';
    $path_pattern = '/.*/i';

    // Get the data from the GET request
    $data_uuid = $_GET['uuid'];
    $data_path = $_GET['path'];

    if ( ! preg_match($uuid_pattern, $data_uuid)) {
        exit("Bad UUID");
    }
    if ( ! preg_match($path_pattern, $data_path)) {
        exit("Bad path");
    }

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        // Prepare the SQL statement to insert the value into the database
        $stmt = $conn->prepare("INSERT INTO statistics (uuid, date, path) VALUES (:uuid, :date, :path)");
        // Execute the SQL statement
        $stmt->execute(['uuid' => $data_uuid, 'date' => date('Y-m-d H:i:s'), 'path' => $data_path]);

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            echo "Data inserted successfully!";
        } else {
            echo "Error inserting data.";
        }
    } catch (PDOException $e) {
        exit($e->getMessage());
    }

    // Close the database connection
    $conn = null;
} else {
    // Show statistics
    try {
        // Create a new PDO instance for database connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        // Prepare the SQL statement to retrieve unique paths and count unique UUIDs for each path
        $stmt = $conn->prepare("
            SELECT path, COUNT(DISTINCT uuid) AS uuid_count
            FROM statistics
            GROUP BY path
        ");
        // Execute the SQL statement
        $stmt->execute();

        // Fetch the result as an associative array
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Iterate over the results and display the path and UUID count
        foreach ($results as $result) {
            $path = $result['path'];
            $uuidCount = $result['uuid_count'];
            echo "Path: $path, UUID Count: $uuidCount" . PHP_EOL;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}
?>
