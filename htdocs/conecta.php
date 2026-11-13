<?php

// Inicia a sessão do usuário
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurações do banco de dados
$host = "127.0.0.1";
$porta = "3306";
$banco = "sistema";
$usuario = "root";
$senha = "";

// Conexão utilizando PDO
try {

    $pdo = new PDO(
        "mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );

    // Configura erros do PDO
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Retorna os resultados como array associativo
    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {

    // Registra o erro sem exibir detalhes internos ao usuário
    error_log($e->getMessage());

    exit("Não foi possível conectar ao banco de dados.");

}
