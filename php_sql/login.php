<?php
// Iniciar a sessão de forma segura
session_name('valida');
session_start();

use sys4soft\Database;
include 'libraries/config.php'; // Arquivo que conecta ao banco de dados
require_once('libraries/Database.php');

// Verificar se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login_index.php');
    exit;
}
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token validation failed');
}
// Preparar os dados do formulário e os erros
$inputs = [
    'text_username' => ['value' => '', 'erro' => ''],
    'text_password' => ['value' => '', 'erro' => '']
];

// Validar o campo de username
if (empty($_POST['text_username'])) {
    $inputs['text_username']['erro'] = 'O campo é de preenchimento obrigatório.';
} else {
    $inputs['text_username']['value'] = htmlspecialchars($_POST['text_username'], ENT_QUOTES, 'UTF-8');
    if (strlen($_POST['text_username']) <= 3 || strlen($_POST['text_username']) > 30) {
        $inputs['text_username']['erro'] = 'O Username deve ter entre 3 e 30 caracteres.';
    }
}

// Validar o campo de password
if (empty($_POST['text_password'])) {
    $inputs['text_password']['erro'] = 'O campo é de preenchimento obrigatório.';
} else {
    $inputs['text_password']['value'] = htmlspecialchars($_POST['text_password'], ENT_QUOTES, 'UTF-8');
    if (strlen($_POST['text_password']) < 12) {
        $inputs['text_password']['erro'] = 'A senha deve ter 12 caracteres.';
    }
}

// Verificar se existem erros
if (!empty($inputs['text_username']['erro']) || !empty($inputs['text_password']['erro'])) {
    $_SESSION['inputs'] = $inputs;
    header('Location: login_index.php');
    exit;
}

$database = new Database(MYSQL_CONFIG);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['text_username'];
    $password = $_POST['text_password'];

    // Preparar a consulta segura usando prepared statements
    $query = "SELECT * FROM Utilizadores WHERE username = ?";
    $results = $database->execute_query($query, [$username]);

    if (!empty($results->results)) {
        $user = $results->results[0];

        // Verificar a senha usando password_verify
        if (password_verify($password, $user->senha)) {
            // Armazenar informações na sessão
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['funcao'] = $user->funcao;
            header("Location: dashboard.php");
            exit();
        } else {
            $inputs['text_password']['erro'] = 'Senha incorreta.';
            $_SESSION['inputs'] = $inputs;
            header('Location: login_index.php');
            exit();
        }
    } else {
        $inputs['text_username']['erro'] = 'Usuário não encontrado.';
        $_SESSION['inputs'] = $inputs;
        header('Location: login_index.php');
        exit();
    }
}
?>

