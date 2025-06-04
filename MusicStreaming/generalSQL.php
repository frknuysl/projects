<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "Furkan_Uysal";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>General SQL Queries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        textarea { width: 100%; height: 120px; }
    </style>
</head>
<body class="bg-light p-4">
<div style="position: absolute; top: 15px; right: 20px; z-index: 999;">
  <a href="homepage.php" class="btn btn-outline-dark btn-sm">🏠 Homepage</a>
</div>

<div class="container">
    <h3>Custom SQL Queries</h3>
    <form method="post" class="mb-3">
        <label for="query">Write a SQL SELECT query (max 5 results):</label>
        <textarea name="query" id="query" class="form-control" placeholder="SELECT * FROM USERS LIMIT 5;"></textarea>
        <button type="submit" class="btn btn-primary mt-2">Run Query</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['query'])) {
        $query = $_POST['query'];
        if (stripos($query, 'select') === 0 && stripos($query, ';') === false) {
            $query .= " LIMIT 5";
            $result = $conn->query($query);
            if ($result && $result->num_rows > 0) {
                echo "<table class='table table-bordered'><thead><tr>";
                while ($fieldinfo = $result->fetch_field()) {
                    echo "<th>" . htmlspecialchars($fieldinfo->name) . "</th>";
                }
                echo "</tr></thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $cell) {
                        echo "<td>" . htmlspecialchars($cell) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<div class='alert alert-warning'>No results found or query failed.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Only SELECT queries are allowed.</div>";
        }
    }
    ?>
</div>
</body>
</html>