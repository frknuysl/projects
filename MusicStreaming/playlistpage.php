<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "Furkan_Uysal";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    echo "Playlist not specified.";
    exit();
}
$playlistId = intval($_GET['id']);
$userId = $_SESSION['user_id'];


$playlist = $conn->query("SELECT * FROM PLAYLISTS WHERE playlist_id = $playlistId")->fetch_assoc();
if (!$playlist) {
    echo "Playlist not found.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_song_id'])) {
    $songId = intval($_POST['add_song_id']);
    $check = $conn->query("SELECT * FROM PLAYLIST_SONGS WHERE playlist_id = $playlistId AND song_id = $songId");
    if ($check->num_rows === 0) {
        $conn->query("INSERT INTO PLAYLIST_SONGS (playlist_id, song_id, date_added) VALUES ($playlistId, $songId, CURDATE())");
    }
}


$search_result = null;
if (isset($_GET['song']) && trim($_GET['song']) !== '') {
    $search = $conn->real_escape_string(trim($_GET['song']));
    $search_result = $conn->query("SELECT song_id, title, image FROM SONGS WHERE title LIKE '%$search%' LIMIT 1");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Playlist - <?php echo htmlspecialchars($playlist['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div style="position: absolute; top: 15px; right: 20px; z-index: 999;">
  <a href="homepage.php" class="btn btn-outline-dark btn-sm">🏠 Homepage</a>
</div>

<div class="container">
    <div class="mb-4">
        <h3><?php echo htmlspecialchars($playlist['title']); ?></h3>
        <p><?php echo htmlspecialchars($playlist['description']); ?></p>
        <img src="<?php echo $playlist['image']; ?>" width="200" class="rounded mb-3">
    </div>


    <form class="mb-4" method="get">
        <input type="hidden" name="id" value="<?php echo $playlistId; ?>">
        <div class="input-group">
            <input type="text" class="form-control" name="song" placeholder="Search song to add">
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </div>
    </form>

    <?php if ($search_result && $search_result->num_rows > 0): ?>
        <?php $song = $search_result->fetch_assoc(); ?>
        <div class="alert alert-info">
            <form method="post">
                <input type="hidden" name="add_song_id" value="<?php echo $song['song_id']; ?>">
                <img src="<?php echo $song['image']; ?>" width="50" class="me-2">
                <strong><?php echo htmlspecialchars($song['title']); ?></strong>
                <button type="submit" class="btn btn-sm btn-success ms-3">Add to Playlist</button>
            </form>
        </div>
    <?php elseif (isset($_GET['song'])): ?>
        <div class="alert alert-warning">No song found for "<?php echo htmlspecialchars($_GET['song']); ?>"</div>
    <?php endif; ?>

    <!-- Songs in Playlist -->
    <h5>Songs in this Playlist:</h5>
    <ul class="list-group">
        <?php
        $sql = "SELECT SONGS.song_id, SONGS.title, SONGS.image, ARTISTS.name AS artist_name, COUNTRY.country_name
                FROM PLAYLIST_SONGS
                JOIN SONGS ON PLAYLIST_SONGS.song_id = SONGS.song_id
                JOIN ALBUMS ON SONGS.album_id = ALBUMS.album_id
                JOIN ARTISTS ON ALBUMS.artist_id = ARTISTS.artist_id
                JOIN COUNTRY ON ARTISTS.country_id = COUNTRY.country_id
                WHERE PLAYLIST_SONGS.playlist_id = $playlistId";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '<li class="list-group-item d-flex align-items-center">
                    <img src="' . $row['image'] . '" width="50" height="50" class="me-3">
                    <div>
                        <strong>' . htmlspecialchars($row['title']) . '</strong><br>
                        <small>' . htmlspecialchars($row['artist_name']) . ' (' . htmlspecialchars($row['country_name']) . ')</small>
                    </div>
                  </li>';
        }
        ?>
    </ul>
</div>
</body>
</html>