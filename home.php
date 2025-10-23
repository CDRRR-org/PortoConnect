<?php
session_start();
if(!isset($_SESSION['login'])){
header("Location: index.php");
exit();
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Home</title>
</head>
<body>
<h1>Selamat datang, <?= $_SESSION['login'] ?>!</h1>
<a href="logout.php">Logout</a>
</body>
</html>
