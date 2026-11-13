<?php

// Inicia ou recupera a sessão
session_start();

// Limpa todos os dados da sessão
$_SESSION = [];

// Destrói a sessão
session_destroy();

// Redireciona para a página de login
header('Location: index.php');

exit();

?>
