<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "Furkan_Uysal";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h3>Mevcut veriler temizleniyor...</h3>";
$conn->query("SET FOREIGN_KEY_CHECKS = 0");
$conn->query("TRUNCATE TABLE PLAY_HISTORY");
$conn->query("TRUNCATE TABLE PLAYLIST_SONGS");
$conn->query("TRUNCATE TABLE PLAYLISTS");
$conn->query("TRUNCATE TABLE SONGS");
$conn->query("TRUNCATE TABLE ALBUMS");
$conn->query("TRUNCATE TABLE ARTISTS");
$conn->query("TRUNCATE TABLE USERS");
$conn->query("TRUNCATE TABLE COUNTRY");
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

function insertFromFile($conn, $filename, $table, $columns, $delimiter = ",") {
    $filepath = __DIR__ . "/data/" . $filename;
    if (!file_exists($filepath)) {
        echo "<p>Dosya bulunamadı: $filename</p>";
        return;
    }

    echo "<h3>📄 $filename dosyası işleniyor → $table</h3>";
    $handle = fopen($filepath, "r");
    $line_num = 0;
    while (($line = fgets($handle)) !== false) {
        $line_num++;
        $line = trim($line);
        if ($line === "") continue;

        $values = array_map(function($v) use ($conn) {
            return "'" . $conn->real_escape_string(trim($v)) . "'";
        }, explode($delimiter, $line));

        $sql = "INSERT INTO $table ($columns) VALUES (" . implode(",", $values) . ")";
        if ($conn->query($sql)) {
            echo "<p>Satır $line_num eklendi: $filename → $table</p>";
        } else {
            echo "<p>HATA [Satır $line_num - $filename]: " . $conn->error . "</p>";
        }
    }
    fclose($handle);
}


insertFromFile($conn, "country.txt", "COUNTRY", "country_id, country_name, country_code");
insertFromFile($conn, "users.txt", "USERS", "user_id, country_id, age, name, username, email, password, date_joined, last_login, follower_num, subscription_type, top_genre, num_songs_liked, most_played_artist, image");
insertFromFile($conn, "artists.txt", "ARTISTS", "artist_id, name, genre, date_joined, total_num_music, total_albums, listeners, bio, country_id, image");
insertFromFile($conn, "albums.txt", "ALBUMS", "album_id, artist_id, title, release_date, genre, music_number, image");
insertFromFile($conn, "songs.txt", "SONGS", "song_id, album_id, title, duration, genre, release_date, `rank`, image");
insertFromFile($conn, "playlists.txt", "PLAYLISTS", "playlist_id, user_id, title, description, date_created, image");
insertFromFile($conn, "playlist_songs.txt", "PLAYLIST_SONGS", "playlistsong_id, playlist_id, song_id, date_added");
insertFromFile($conn, "play_history.txt", "PLAY_HISTORY", "play_id, user_id, song_id, playtime");

$conn->close();
echo "<h3>Tüm işlemler tamamlandı.</h3>";
?>