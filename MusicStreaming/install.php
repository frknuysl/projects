<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(1200);

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "Furkan_Uysal";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$sql_create_db = "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!$conn->query($sql_create_db)) {
    die("Veritabanı oluşturulamadı: " . $conn->error);
}
$conn->close();

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}

$table_queries = [
    "CREATE TABLE IF NOT EXISTS COUNTRY (
        country_id INT AUTO_INCREMENT PRIMARY KEY,
        country_name VARCHAR(100),
        country_code VARCHAR(10)
    )",
    "CREATE TABLE IF NOT EXISTS USERS (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        country_id INT,
        age INT,
        name VARCHAR(100),
        username VARCHAR(100),
        email VARCHAR(100),
        password VARCHAR(255),
        date_joined DATE,
        last_login DATE,
        follower_num INT,
        subscription_type VARCHAR(50),
        top_genre VARCHAR(100),
        num_songs_liked INT,
        most_played_artist VARCHAR(100),
        image VARCHAR(255),
        FOREIGN KEY (country_id) REFERENCES COUNTRY(country_id)
    )",
    "CREATE TABLE IF NOT EXISTS ARTISTS (
        artist_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        genre VARCHAR(100),
        date_joined DATE,
        total_num_music INT,
        total_albums INT,
        listeners INT,
        bio TEXT,
        country_id INT,
        image VARCHAR(255),
        FOREIGN KEY (country_id) REFERENCES COUNTRY(country_id)
    )",
    "CREATE TABLE IF NOT EXISTS ALBUMS (
        album_id INT AUTO_INCREMENT PRIMARY KEY,
        artist_id INT,
        title VARCHAR(100),
        release_date DATE,
        genre VARCHAR(100),
        music_number INT,
        image VARCHAR(255),
        FOREIGN KEY (artist_id) REFERENCES ARTISTS(artist_id)
    )",
    "CREATE TABLE IF NOT EXISTS SONGS (
        song_id INT AUTO_INCREMENT PRIMARY KEY,
        album_id INT,
        title VARCHAR(100),
        duration TIME,
        genre VARCHAR(100),
        release_date DATE,
        `rank` INT,
        image VARCHAR(255),
        FOREIGN KEY (album_id) REFERENCES ALBUMS(album_id)
    )",
    "CREATE TABLE IF NOT EXISTS PLAYLISTS (
        playlist_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        title VARCHAR(100),
        description TEXT,
        date_created DATE,
        image VARCHAR(255),
        FOREIGN KEY (user_id) REFERENCES USERS(user_id)
    )",
    "CREATE TABLE IF NOT EXISTS PLAYLIST_SONGS (
        playlistsong_id INT AUTO_INCREMENT PRIMARY KEY,
        playlist_id INT,
        song_id INT,
        date_added DATE,
        FOREIGN KEY (playlist_id) REFERENCES PLAYLISTS(playlist_id),
        FOREIGN KEY (song_id) REFERENCES SONGS(song_id)
    )",
    "CREATE TABLE IF NOT EXISTS PLAY_HISTORY (
        play_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        song_id INT,
        playtime DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES USERS(user_id),
        FOREIGN KEY (song_id) REFERENCES SONGS(song_id)
    )",
	"CREATE TABLE IF NOT EXISTS FOLLOWS (
    follow_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    artist_id INT,
    follow_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id),
    FOREIGN KEY (artist_id) REFERENCES ARTISTS(artist_id)
)"

];




foreach ($table_queries as $query) {
    if (!$conn->query($query)) {
        echo "Hata: " . $conn->error . "<br>";
    }
}

$conn->close();

echo "<script>alert('Veritabanı ve tablolar başarıyla oluşturuldu. Giriş sayfasına yönlendiriliyorsunuz...');
window.location.href = 'login.html';</script>";
exit();
?>