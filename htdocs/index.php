<?php

include 'conecta.php';

$erro = "";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email_usuario = trim($_POST['email_usuario'] ?? '');
    $senha_usuario = $_POST['senha_usuario'] ?? '';

    // Consulta o usuário pelo e-mail
    $sql = "SELECT id, email, senha
            FROM usuarios
            WHERE email = :email_usuario";

    $stmt = $pdo->prepare($sql);

    // Associa o e-mail ao placeholder
    $stmt->bindParam(
        ':email_usuario',
        $email_usuario,
        PDO::PARAM_STR
    );

    $stmt->execute();

    $usuario = $stmt->fetch();

    // Verifica se o usuário existe e se a senha está correta
    if ($usuario && password_verify($senha_usuario, $usuario['senha'])) {

        // Renova o identificador da sessão
        session_regenerate_id(true);

        // Armazena os dados do usuário na sessão
        $_SESSION['id_usuario'] = $usuario['id'];
        $_SESSION['email_usuario'] = $usuario['email'];

        header('Location: inicio.php');
        exit();

    } else {

        $erro = "Usuário ou senha incorretos.";

    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container d-flex flex-column justify-content-center align-items-center vh-100">

        <form method="POST" action="" class="w-100" style="max-width: 400px;">

            <div class="d-flex justify-content-center mb-4">

                <img src="https://placehold.co/150x70?text=Meu%20App"
                     alt="Logo"
                     class="img-fluid mb-4">

            </div>

            <h1 class="h3 mb-3 fw-normal text-center">
                Faça login
            </h1>

            <?php if ($erro !== ''): ?>

                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($erro); ?>
                </div>

            <?php endif; ?>

            <div class="form-floating mb-3">

                <input
                    type="email"
                    name="email_usuario"
                    class="form-control"
                    placeholder="E-mail de usuário"
                    required
                >

                <label>E-mail de usuário</label>

            </div>

            <div class="form-floating mb-3">

                <input
                    type="password"
                    name="senha_usuario"
                    class="form-control"
                    placeholder="Senha"
                    required
                >

                <label>Senha</label>

            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">
                Entrar
            </button>

            <p class="mt-3 text-center">
                <a href="cadastro.php">Cadastrar-se</a>
            </p>

        </form>

    </div>

</body>

</html>
