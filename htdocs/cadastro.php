<?php

include 'conecta.php';

$erro = "";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email_usuario = trim($_POST['email_usuario'] ?? '');
    $senha_usuario = $_POST['senha_usuario'] ?? '';

    // Validação do e-mail
    if (!filter_var($email_usuario, FILTER_VALIDATE_EMAIL)) {

        $erro = "Digite um e-mail válido.";

    } elseif (strlen($senha_usuario) < 8) {

        $erro = "A senha deve possuir pelo menos 8 caracteres.";

    } else {

        // Transforma a senha em hash
        $senha_hash = password_hash(
            $senha_usuario,
            PASSWORD_DEFAULT
        );

        // Consulta SQL com placeholders
        $sql = "INSERT INTO usuarios (email, senha)
                VALUES (:email_usuario, :senha_usuario)";

        $stmt = $pdo->prepare($sql);

        // Associa os valores aos placeholders
        $stmt->bindParam(
            ':email_usuario',
            $email_usuario,
            PDO::PARAM_STR
        );

        $stmt->bindParam(
            ':senha_usuario',
            $senha_hash,
            PDO::PARAM_STR
        );

        try {

            $stmt->execute();

            header('Location: index.php');
            exit();

        } catch (PDOException $e) {

            if ($e->getCode() == 23000) {

                $erro = "Este e-mail já está cadastrado.";

            } else {

                error_log($e->getMessage());
                $erro = "Não foi possível realizar o cadastro.";

            }

        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro</title>

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
                Cadastre-se
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
                    minlength="8"
                    required
                >

                <label>Senha</label>

            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">
                Cadastrar
            </button>

            <a class="w-100 btn btn-lg btn-secondary mt-3" href="index.php">
                Voltar
            </a>

        </form>

    </div>

</body>

</html>
