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

$username = $_SESSION['username'];
$userId = $_SESSION['user_id'];
$name = $_SESSION['name'];
$countryId = $_SESSION['country_id'];

// Add playlist logic
$playlistMsg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['new_playlist'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $image = $conn->real_escape_string($_POST['image']);
    $insert = $conn->query("INSERT INTO PLAYLISTS (user_id, title, description, date_created, image)
                            VALUES ($userId, '$title', '$description', CURDATE(), '$image')");
    if ($insert) {
        $playlistMsg = "Playlist created!";
    } else {
        $playlistMsg = "Failed to create playlist.";
    }
}

// Handle search
if (isset($_GET['q']) && trim($_GET['q']) !== '') {
    $search = $conn->real_escape_string(trim($_GET['q']));
    $res = $conn->query("SELECT playlist_id FROM PLAYLISTS WHERE title LIKE '%$search%' LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $id = $res->fetch_assoc()['playlist_id'];
        header("Location: playlistpage.php?id=" . $id);
        exit();
    }
    $res = $conn->query("SELECT song_id FROM SONGS WHERE title LIKE '%$search%' LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $id = $res->fetch_assoc()['song_id'];
        header("Location: currentmusic.php?song_id=" . $id);
        exit();
    }
    $search_error = "No results found for '" . htmlspecialchars($search) . "'";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homepage - Music Player</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .playlist-img, .artist-img, .song-img { width: 100%; height: 150px; object-fit: cover; }
        .form-control { max-width: 250px; }
    </style>
</head>
<body class="bg-light p-4">
<div class="container-fluid">

    <!-- Top navbar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form class="d-flex" method="get" action="homepage.php">
            <input class="form-control me-2" type="search" name="q" placeholder="Search Playlists or Songs">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
        <div class="text-end">
            <a href="homepage.php" class="btn btn-outline-dark">🏠 Homepage</a>
            <h4 class="d-inline-block ms-3">Welcome, <?php echo htmlspecialchars($name); ?> 👋</h4>
        </div>
    </div>
    <?php if (isset($search_error)) echo "<p class='text-danger mt-2'>$search_error</p>"; ?>

    <div class="row">
        <!-- Left: Playlists + Add -->
        <div class="col-md-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5>My Playlists</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#addPlaylist">+</button>
            </div>

            <!-- New playlist form -->
            <div id="addPlaylist" class="collapse">
                <form method="post" class="card card-body mb-3">
                    <input type="hidden" name="new_playlist" value="1">
                    <input name="title" class="form-control mb-2" placeholder="Playlist title" required>
                    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
                    <input name="image" class="form-control mb-2" placeholder="Image URL" value="https://picsum.photos/200/300?random=<?php echo rand(1000,9999); ?>">
                    <button class="btn btn-success btn-sm" type="submit">Create</button>
                </form>
                <?php if ($playlistMsg) echo "<div class='alert alert-info'>$playlistMsg</div>"; ?>
            </div>

            <!-- Existing playlists -->
            <?php
            $result = $conn->query("SELECT * FROM PLAYLISTS WHERE user_id = $userId");
            while ($row = $result->fetch_assoc()) {
                echo '<div class="card mb-2">
                        <img src="' . $row['image'] . '" class="playlist-img card-img-top">
                        <div class="card-body">
                            <h6 class="card-title">' . htmlspecialchars($row['title']) . '</h6>
                            <a href="playlistpage.php?id=' . $row['playlist_id'] . '" class="btn btn-sm btn-outline-dark">Open</a>
                        </div>
                    </div>';
            }
            ?>
        </div>

        <!-- Right: History + Artists -->
        <div class="col-md-9">
            <h5>Last 5 Played Songs</h5>
            <ul class="list-group mb-4">
            <?php
            $sql = "SELECT SONGS.song_id, SONGS.title, SONGS.image, ARTISTS.name AS artist_name
                    FROM PLAY_HISTORY
                    JOIN SONGS ON PLAY_HISTORY.song_id = SONGS.song_id
                    JOIN ALBUMS ON SONGS.album_id = ALBUMS.album_id
                    JOIN ARTISTS ON ALBUMS.artist_id = ARTISTS.artist_id
                    WHERE PLAY_HISTORY.user_id = $userId
                    ORDER BY PLAY_HISTORY.playtime DESC LIMIT 5";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                        <img src="' . $row['image'] . '" width="50" height="50" class="rounded me-2">
                        <a href="currentmusic.php?song_id=' . $row['song_id'] . '" class="text-decoration-none">
                            ' . htmlspecialchars($row['title']) . ' - ' . htmlspecialchars($row['artist_name']) . '
                        </a>
                      </li>';
            }
            ?>
            </ul>

            <h5>Top 5 Artists in Your Country</h5>
            <div class="row">
            <?php
            $sql = "SELECT * FROM ARTISTS WHERE country_id = $countryId ORDER BY listeners DESC LIMIT 5";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="' . $row['image'] . '" class="artist-img card-img-top">
                            <div class="card-body">
                                <h6>' . htmlspecialchars($row['name']) . '</h6>
                                <a href="artistpage.php?id=' . $row['artist_id'] . '" class="btn btn-sm btn-outline-dark">View</a>
                            </div>
                        </div>
                      </div>';
            }
            ?>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS for collapse -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>