<?php

include 'conecta.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {

    header('Location: index.php');
    exit();

}

$erro = "";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nova_senha = $_POST['nova_senha'] ?? '';

    if (strlen($nova_senha) < 8) {

        $erro = "A nova senha deve possuir pelo menos 8 caracteres.";

    } else {

        $email_usuario = $_SESSION['email_usuario'];

        // Gera o hash da nova senha
        $senha_hash = password_hash(
            $nova_senha,
            PASSWORD_DEFAULT
        );

        // Atualiza a senha no banco de dados
        $sql = "UPDATE usuarios
                SET senha = :nova_senha
                WHERE email = :email_usuario";

        $stmt = $pdo->prepare($sql);

        // Associa a nova senha
        $stmt->bindParam(
            ':nova_senha',
            $senha_hash,
            PDO::PARAM_STR
        );

        // Associa o e-mail do usuário logado
        $stmt->bindParam(
            ':email_usuario',
            $email_usuario,
            PDO::PARAM_STR
        );

        try {

            $stmt->execute();

            header('Location: inicio.php');
            exit();

        } catch (PDOException $e) {

            error_log($e->getMessage());

            $erro = "Não foi possível alterar a senha.";

        }

    }

}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Alterar Senha</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container d-flex flex-column justify-content-center align-items-center vh-100">

        <form method="POST" action="" class="w-100" style="max-width: 400px;">

            <div class="d-flex justify-content-center mb-4">

                <img
                    src="https://placehold.co/150x70?text=Meu%20App"
                    alt="Logo"
                    class="img-fluid"
                >

            </div>

            <h1 class="h3 mb-3 fw-normal text-center">
                Alterar Senha
            </h1>

            <?php if ($erro !== ''): ?>

                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($erro); ?>
                </div>

            <?php endif; ?>

            <div class="form-floating mb-3">

                <input
                    type="password"
                    name="nova_senha"
                    class="form-control"
                    placeholder="Nova senha"
                    minlength="8"
                    required
                >

                <label>Nova senha</label>

            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">

                Alterar senha

            </button>

            <a class="w-100 btn btn-lg btn-secondary mt-3" href="inicio.php">

                Cancelar

            </a>

        </form>

    </div>

</body>

</html>
