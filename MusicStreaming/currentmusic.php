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

if (!isset($_GET['song_id'])) {
    echo "No song selected.";
    exit();
}
$songId = intval($_GET['song_id']);

$sql = "SELECT SONGS.*, ARTISTS.name AS artist_name
        FROM SONGS
        JOIN ALBUMS ON SONGS.album_id = ALBUMS.album_id
        JOIN ARTISTS ON ALBUMS.artist_id = ARTISTS.artist_id
        WHERE SONGS.song_id = $songId";
$result = $conn->query($sql);
$song = $result->fetch_assoc();
if (!$song) {
    echo "Song not found.";
    exit();
}

$userId = $_SESSION['user_id'];
$conn->query("INSERT INTO PLAY_HISTORY(user_id, song_id, playtime) VALUES($userId, $songId, NOW())");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Now Playing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div style="position: absolute; top: 15px; right: 20px; z-index: 999;">
  <a href="homepage.php" class="btn btn-outline-dark btn-sm">🏠 Homepage</a>
</div>

<div class="container text-center">
    <h3>Now Playing</h3>
    <img src="<?php echo $song['image']; ?>" class="img-fluid rounded" width="300">
    <h4 class="mt-3"><?php echo htmlspecialchars($song['title']); ?></h4>
    <p><strong>Artist:</strong> <?php echo htmlspecialchars($song['artist_name']); ?></p>
    <p><strong>Duration:</strong> <?php echo $song['duration']; ?></p>
    <p><strong>Genre:</strong> <?php echo $song['genre']; ?></p>
    <p><strong>Release Date:</strong> <?php echo $song['release_date']; ?></p>
</div>
</body>
</html>