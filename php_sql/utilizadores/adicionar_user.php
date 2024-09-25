<?php
use sys4soft\Database;

require_once('header.php');
require_once('../libraries/config.php');
require_once('../libraries/Database.php');

$erro = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database(MYSQL_CONFIG);

    // Pegar dados do POST
    $nome = $_POST['text_nome'];
    $apelido = $_POST['text_apelido'];
    $username = $_POST['text_username'];
    $telefone = $_POST['text_telefone'];
    $email = $_POST['text_email'];
    $funcao = $_POST['text_select'];
    $senha = $_POST['text_senha'];
    $senha_rept = $_POST['text_senha_rept'];

    // Verificar se as senhas coincidem
    if ($senha != $senha_rept) {
        $erro = 'As senhas não são compatíveis';
    }

    // Verificar se já existe um contato com o mesmo telefone, email ou username
    $parames = [
        ':telefone' => $telefone,
        ':email' => $email,
        ':username' => $username
    ];

    $results = $database->execute_query(
        "SELECT id FROM utilizadores WHERE telefone = :telefone OR email = :email OR username = :username",
        $parames
    );

    if ($results->affected_rows != 0) {
        // Existe um contato com o mesmo número, email ou username
        $erro = 'Já existe um contato com essas características';
    }

    if (is_null($erro)) {
        // Hash da senha
        $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir o novo contato no banco de dados
        $parames = [
            ':nome' => $nome,
            ':apelido' => $apelido,
            ':username' => $username,
            ':telefone' => $telefone,
            ':email' => $email,
            ':funcao' => $funcao,
            ':senha' => $senha_hashed
        ];

        $database->execute_non_query(
            "INSERT INTO utilizadores (nome, apelido, username, telefone, email, funcao, senha, created_at, updated_at) 
             VALUES (:nome, :apelido, :username, :telefone, :email, :funcao, :senha, NOW(), NOW())",
            $parames
        );

        header('Location: gerir_utilizadores.php');
        exit();
    }
}
?>

<div class="row row align-items-start">
    <h2 class="text-center" style="padding-bottom: 10%;">Adicionar Novo Utilizador</h2>
    <div class="col-sm-5 col-md-6">

        <div class="card p-4">

            <form action="adicionar_user.php" method="post">
                <input type="hidden" name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                <p class="text-center"><strong>Novo Utilizador</strong></p>
                <div class="mb-3">
                    <label for="text_nome" class="form-label">Nome</label>
                    <input type="text" name="text_nome" id="text_nome" class="form-control" minlength="3" maxlength="50"
                        required autofocus>
                </div>
                <div class="mb-3">
                    <label for="text_apelido" class="form-label">Apelido</label>
                    <input type="text" name="text_apelido" id="text_apelido" class="form-control" minlength="3"
                        maxlength="50" required>
                </div>
                <div class="mb-3">
                    <label for="text_username" class="form-label">Username</label>
                    <input type="text" name="text_username" id="text_username" class="form-control" minlength="3"
                        maxlength="50" required>
                </div>

                <div class="mb-3">
                    <label for="text_telefone" class="form-label">Telefone</label>
                    <input type="tel" name="text_telefone" id="text_telefone" class="form-control" minlength="9"
                        maxlength="9" required>
                </div>
        </div>
    </div>
    <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0">
        <div class="card p-4">
            <div class="mb-3">
                <label for="text_email" class="form-label">Email</label>
                <input type="email" name="text_email" id="text_email" class="form-control" minlength="3" required>
            </div>
            <div class="mb-3">
                <label for="text_select" class="form-label">Função</label>
                <select class="form-select" id="text_select" name="text_select">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="text_senha" class="form-label">Senha</label>
                <input type="password" name="text_senha" id="text_senha" class="form-control" minlength="5" required>
            </div>
            <div class="mb-3">
                <label for="text_senha_rept" class="form-label">Repetir Senha</label>
                <input type="password" name="text_senha_rept" id="text_senha_rept" class="form-control" minlength="5"
                    required>
            </div>

            <div class="text-center">
                <a href="gerir_utilizadores.php" class="btn btn-outline-dark">Cancelar</a>
                <input type="submit" value="Guardar" class="btn btn-outline-dark">
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Mensagem de erro -->
<?php if (!empty($erro)): ?>
    <div class="mt-3 alert alert-danger p-2 text-center">
        <?= htmlspecialchars($erro) ?>
    </div>
<?php endif; ?>

<?php require_once('footer.php'); ?>