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

if (!isset($_GET['id'])) {
    echo "Artist not specified.";
    exit();
}
$artistId = intval($_GET['id']);
$userId = $_SESSION['user_id'];

$artist = $conn->query("SELECT * FROM ARTISTS WHERE artist_id = $artistId")->fetch_assoc();
if (!$artist) {
    echo "Artist not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artist Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .artist-img { width: 100%; height: auto; border-radius: 10px; }
    </style>
</head>
<body class="bg-light p-4">
<div style="position: absolute; top: 15px; right: 20px; z-index: 999;">
  <a href="homepage.php" class="btn btn-outline-dark btn-sm">🏠 Homepage</a>
</div>

<div class="container">
    <div class="row mb-4">

        <div class="col-md-4">
            <img src="<?php echo $artist['image']; ?>" class="artist-img mb-3">
            <h4><?php echo htmlspecialchars($artist['name']); ?></h4>
            <p><strong>Genre:</strong> <?php echo htmlspecialchars($artist['genre']); ?></p>
            <p><strong>Bio:</strong> <?php echo htmlspecialchars($artist['bio']); ?></p>


            <form method="post">
                <input type="hidden" name="follow_id" value="<?php echo $artistId; ?>">
                <button type="submit" class="btn btn-primary btn-sm">Follow</button>
            </form>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['follow_id'])) {
                $check = $conn->query("SELECT * FROM FOLLOWS WHERE user_id = $userId AND artist_id = $artistId");
                if ($check->num_rows === 0) {
                    $conn->query("INSERT INTO FOLLOWS(user_id, artist_id) VALUES($userId, $artistId)");
                    echo "<div class='alert alert-success mt-2'>Now following this artist!</div>";
                } else {
                    echo "<div class='alert alert-info mt-2'>You already follow this artist.</div>";
                }
            }
            ?>
        </div>

        <div class="col-md-8">
            <h5>Last 5 Albums</h5>
            <div class="row mb-4">
                <?php
                $albums = $conn->query("SELECT * FROM ALBUMS WHERE artist_id = $artistId ORDER BY release_date DESC LIMIT 5");
                while ($album = $albums->fetch_assoc()) {
                    echo '<div class="col-md-6 mb-3">
                            <div class="card">
                                <img src="' . $album['image'] . '" class="card-img-top" height="150">
                                <div class="card-body">
                                    <h6>' . htmlspecialchars($album['title']) . '</h6>
                                    <p><small>' . $album['release_date'] . '</small></p>
                                </div>
                            </div>
                          </div>';
                }
                ?>
            </div>

            <h5>Top 5 Most Listened Songs</h5>
            <ul class="list-group">
                <?php
                $songs = $conn->query("
                    SELECT SONGS.song_id, SONGS.title, SONGS.image, SONGS.rank
                    FROM SONGS
                    JOIN ALBUMS ON SONGS.album_id = ALBUMS.album_id
                    WHERE ALBUMS.artist_id = $artistId
                    ORDER BY SONGS.rank DESC LIMIT 5
                ");
                while ($song = $songs->fetch_assoc()) {
                    echo '<li class="list-group-item d-flex align-items-center">
                            <img src="' . $song['image'] . '" width="50" height="50" class="me-3">
                            <a href="currentmusic.php?song_id=' . $song['song_id'] . '">' . htmlspecialchars($song['title']) . '</a>
                          </li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>