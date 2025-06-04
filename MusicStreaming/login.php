<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "Furkan_Uysal";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM USERS WHERE username = '$user' AND password = '$pass'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id'];
		$_SESSION['name'] = $row['name'];
		$_SESSION['country_id'] = $row['country_id'];
		$_SESSION['username'] = $row['username'];

        header("Location: homepage.php");
        exit();
    } else {
        echo "<script>alert('Geçersiz kullanıcı adı veya şifre'); window.location.href='login.html';</script>";
        exit();
    }
}
?>