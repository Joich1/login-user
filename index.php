<?php
session_start();
include 'config.php';

$message = '';

function registerUser ($username, $password, $conn) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $checkUser   = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($checkUser ) > 0) {
        return "Usuário já existe!";
    }

    $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
    if (mysqli_query($conn, $query)) {
        return "Usuário cadastrado com sucesso!";
    } else {
        return "Erro ao cadastrar usuário!";
    }
}

function loginUser ($username, $password, $conn) {
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        } else {
            return "Senha incorreta!";
        }
    } else {
        return "Usuário não encontrado!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $message = registerUser ($_POST['username'], $_POST['password'], $conn);
    } else {
        if (loginUser ($_POST['username'], $_POST['password'], $conn) === true) {
            header("Location: index.php");
            exit;
        } else {
            $message = loginUser ($_POST['username'], $_POST['password'], $conn);
        }
    }
}

if (isset($_SESSION['user_id'])) {
    echo '
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
    </html>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login ou Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="index.php">
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <?php if ($message && !isset($_POST['register'])): ?> 
            <div style="margin-top: 10px; color: #00d4ff;">
                <?= $message; ?> 
            </div>
        <?php endif; ?>
        
        <h2>Registrar</h2>
        <form method="POST" action="index.php"> 
            <input type="text" name="username" placeholder="Usuário" required> 
            <input type="password" name="password" placeholder="Senha" required> 
            <button type="submit" name="register">Cadastrar</button> 
        </form>
        <?php if ($message && isset($_POST['register'])): ?> 
            <div style="margin-top: 10px; color: #00d4ff;">
                <?= $message; ?> 
            </div>
        <?php endif; ?>
    </div>
</body>
</html>