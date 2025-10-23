<?php
session_start();
$err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Username & password sementara
$username = "admin";
$password = "12345";
$inputUser = $_POST['user'];
$inputPass = $_POST['pass'];
if ($inputUser === $username && $inputPass === $password) {
$_SESSION['login'] = $inputUser;
header("Location: home.php");
exit();
} else {
$err = "Username atau password salah!";
}
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Login Project UTS</title>
</head>
<body>
<h1>Halaman Login</h1>
<?php if($err != ""): ?>
<p style="color:red"><?= $err ?></p>
<?php endif; ?>
<form method="POST">
<label>Username:</label>
<input type="text" name="user" required>
<br>
<label>Password:</label>
<input type="password" name="pass" required>
<br>
<button type="submit">Login</button>
</form>
<hr>
<div id="anggota">
<p>Daftar kontribusi anggota:</p>
</div>
</body>
</html>
