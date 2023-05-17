<?php
// DB settings
$servername = "localhost";
$username = "tester";
$password = "testing";
$dbname = "nano_analytics";
$uuid_pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';
$path_pattern = '/.*/i';

if (isset($_GET['uuid'])) {
    // New statistics entry
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
            exit("OK");
        } else {
            exit("Error inserting data");
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
        $all_statistics = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (isset($_GET['path_stat'])) {
            $data_path = $_GET['path_stat'];
            if ( ! preg_match($path_pattern, $data_path)) {
                exit("Bad path");
            }
            $stmt = $conn->prepare("
                SELECT uuid, date
                FROM statistics
                WHERE path = :path
                ORDER BY date DESC
            ");
            $stmt->execute(['path' => $data_path]);
            $path_statistics = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}
?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>Nano Analytics</title>
        <style>
            :root {
                --body-bg: #fff;
                --body-text: #000;
                --header-bg: #000;
                --header-text: #efefef;
                --tr-nth-bg: #ededed;
            }
            body {
                padding: 0px;
                margin: 0px;
                font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
                font-size: 16px;
                background-color: var(--body-bg);
                color: var(--body-text);
            }
            header {
                margin: 0px;
                background-color: var(--header-bg);
                color:  var(--header-text);
            }
            h1 {
                font-weight: normal;
                padding: 10px 20px 5px 15px;
                margin: 0px;
                font-size: 25px;
            }
            h2 {
                font-weight: normal;
                padding: 10px 15px 0px 15px;
                margin: 0px;
                font-size: 20px;
            }
            table {
                margin: 7px 15px 7px 15px;
                border: 1px solid var(--header-bg);
                border-collapse: collapse;
            }
            th {
                font-weight: normal;
                padding: 4px 15px 0px 15px;
                background-color: var(--header-bg);
                color: var(--header-text);
            }
            tr:nth-child(even) {
                background-color: var(--tr-nth-bg);
            }
            td {
                padding: 4px 5px 0px 5px;
            }
            .left {
                width: 49%;
                float: left;
                margin: 0px;
                padding: 0px;
                #border: 1px solid red;
            }
            .right {
                width: 49%;
                float: right;
                margin: 0px;
                padding: 0px;
                #border: 1px solid blue;
            }
        </style>
    </head>
    <body>
        <header><h1>Nano Analytics</h1></header>
        <section class="left">
            <h2>Current statistics</h2>
            <table width="90%">
                <tr><th>Page</th><th>Users</th></tr>
                <?php
                // Iterate over the results and display the path and UUID count
                foreach ($all_statistics as $stat) {
                    echo "<tr><td>
                          <a href=\"?path_stat=".urlencode($stat['path'])."\">".$stat['path']."</a></td>
                          <td><center>".$stat['uuid_count']."</center></td></tr>";
                }
                ?>
            </table>
        </section>
        <section class="right">
            <?php
            if (isset($path_statistics)) {
                echo "<h2>Views of ".$data_path."</h2>"
            ?>
            <table width="90%">
                <tr><th>UUID</th><th>Date</th></tr>
                <?php
                foreach ($path_statistics as $stat) {
                    echo "<tr><td>".$stat['uuid']."</td><td><center>".$stat['date']."</center></td></tr>";
                }
                ?>
            </table>
            <?php } ?>
        </section>
    <body>
</html>
