<?php
session_start();
session_destroy();
header("Location: index.php");
exit();
Leader commit & push:
git add .
git commit -m "Inisialisasi project login"
git push origin main
