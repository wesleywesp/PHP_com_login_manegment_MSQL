<?php
session_start();
use sys4soft\Database;


require_once('libraries/config.php');
require_once('libraries/Database.php');

// Verificar se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar o token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Token CSRF inválido.');
    }
    $database = new Database(MYSQL_CONFIG);

    // Sanitização e validação das entradas
    $nome = htmlspecialchars(trim($_POST['text_nome']), ENT_QUOTES, 'UTF-8');
    $apelido = htmlspecialchars(trim($_POST['text_apelido']), ENT_QUOTES, 'UTF-8');
    $username = htmlspecialchars(trim($_POST['text_username']), ENT_QUOTES, 'UTF-8');
    $telefone = htmlspecialchars(trim($_POST['text_telefone']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['text_email']), FILTER_VALIDATE_EMAIL);
    $senha = trim($_POST['text_senha']);
    $senha_rept = trim($_POST['text_senha_rept']);

    // Verificar se os campos obrigatórios foram preenchidos
    if (empty($nome) || empty($telefone) || empty($apelido) || empty($username) || empty($email) || empty($senha) || empty($senha_rept)) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif ($senha !== $senha_rept) {
        $erro = "As senhas não coincidem.";
    } elseif (strlen($senha) < 12) {
        $erro = "A senha deve ter no mínimo 12 caracteres.";
    } else {
        // Hash seguro da senha
        $senha_hashed = password_hash($senha, PASSWORD_BCRYPT);

        // Verificar se já existe um contato com o mesmo telefone, email ou username
        $parames = [
            ':telefone' => $telefone,
            ':email' => $email,
            ':username' => $username
        ];

        // Certifique-se de que a variável $database seja uma instância válida de conexão com o banco de dados
        // $database = new DatabaseConnection(); // Exemplo de instanciamento

        $results = $database->execute_query(
            "SELECT id FROM utilizadores WHERE telefone = :telefone OR email = :email OR username = :username",
            $parames
        );

        if ($results->affected_rows != 0) {
            // Existe um contato com o mesmo número, email ou username
            $erro = 'Já existe um contato com essas características';
        } else {
            // Inserir o novo contato no banco de dados
            $parames = [
                ':nome' => $nome,
                ':apelido' => $apelido,
                ':username' => $username,
                ':telefone' => $telefone,
                ':email' => $email,
                ':senha' => $senha_hashed,
            ];

            $database->execute_non_query(
                "INSERT INTO utilizadores (nome, apelido, username, telefone, email, senha,  created_at, updated_at) 
                 VALUES (:nome, :apelido, :username, :telefone, :email, :senha , NOW(), NOW())",
                $parames
            );

            // Sucesso na criação do usuário
            header('Location: login_index.php');
            exit();
        }
    }
}

// Gerar um token CSRF e armazená-lo na sessão
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

    < <div class="container-fluid">
        <?php require_once 'libraries/nav.php'; ?> <!-- Inclui a barra de navegação -->
        </div>
        <div class="container mt-5" >
            <div class="row justify-content-center">

                <div class="col-sm-6">
                    <h2>Novo Utilizador</h2>
                    <form action="adicionar_user.php" method="post" style="padding-top: 10%;">
                        <!-- Token CSRF -->
                        <input type="hidden" name="csrf_token"
                            value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                        <div class="mb-3">
                            <label for="text_nome" id="floatingInput class=" form-label>Nome:</label>
                            <input type="text" name="text_nome" id="text_nome" class="form-control" minlength="3"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="text_apelido" class="form-label">Apelido</label>
                            <input type="text" name="text_apelido" id="text_apelido" class="form-control" minlength="3"
                                maxlength="50" required>
                        </div>
                        <div class="mb-3">
                            <label for="text_username" class="form-label">Username</label>
                            <input type="text" name="text_username" id="text_username" class="form-control"
                                minlength="3" maxlength="50" required>
                        </div>
                        <div class="mb-3">
                            <label for="text_telefone" class="form-label">Telefone</label>
                            <input type="tel" name="text_telefone" id="text_telefone" class="form-control" minlength="9"
                                maxlength="9" required>
                        </div>
                        <div class="mb-3">
                            <label for="text_email" class="form-label">Email</label>
                            <input type="email" name="text_email" id="text_email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="text_senha" class="form-label">Senha</label>
                            <input type="password" name="text_senha" id="text_senha" class="form-control" minlength="5"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="text_senha_rept" class="form-label">Repetir Senha</label>
                            <input type="password" name="text_senha_rept" id="text_senha_rept" class="form-control"
                                minlength="5" required>
                        </div>

                        <div class="text-center">
                            <a href="login_index.php" class="btn btn-outline-danger">Cancelar</a>
                            <input type="submit" value="Guardar" class="btn btn-outline-primary">
                        </div>
                    </form>
                    <!-- Mensagem de erro -->
                    <?php if (!empty($erro)): ?>
                        <div class="mt-3 alert alert-danger p-2 text-center">
                            <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
<!-- footer -->
<div class="container-fluid  fixed-bottom">
    <div class="row">
        <div class="col bg-dark text-white p-2 text-center">
            <p>Novo Utilizador <?= date('Y') ?></p>
            <P>Wesley &copy; Pinto</P>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>