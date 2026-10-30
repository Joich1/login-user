<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Conteúdo Restrito</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Bem-vindo ao Conteúdo Restrito</h2>
        <p>Esta é uma área protegida para usuários logados.</p>
        <a href="logout.php" class="btn-logout">Sair</a>
    </div>
</body>
</html>