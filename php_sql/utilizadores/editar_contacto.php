<?php
use sys4soft\Database;
require_once('header.php');

// Verificar se o 'id' foi enviado via GET
if (empty($_GET['id'])) {
    header('Location: ../gerir_utilizadores.php');
    exit();
}

require_once('../libraries/config.php');
require_once('../libraries/Database.php');

$id = $_GET['id'];
$erro = null;
$database = new Database(MYSQL_CONFIG);

// Pegar os dados do contato
$parames = [
    ':id' => $id,
];
$results = $database->execute_query("SELECT * FROM utilizadores WHERE id = :id", $parames);
$contact = $results->results[0] ?? null; // Corrigido para acessar o primeiro elemento do array de resultados

// Verificar se existe um POST (formulário foi submetido)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['text_nome'];
    $telefone = $_POST['text_telefone'];
    $email = $_POST['text_email'];
    $username = $_POST['text_username'];
    $apelido = $_POST['text_apelido'];
    $funcao = $_POST['text_select'];
    $reset_senha = isset($_POST['check_box']); // Verifica se o checkbox foi marcado

    // Senha padrão para redefinição
    $senha = '$2y$10$2IksHy5oNVPxgCfbyKC/5ubK9AR36Y5GOKIkDHnCrrY8SiNEdanFG';//suaSenha1234

    // Verificar se já existe um contato com o mesmo telefone, email ou username
    $parames = [
        ':id' => $id,
        ':telefone' => $telefone,
        ':email' => $email,
        ':username' => $username,
    ];
    $results = $database->execute_query(
        'SELECT id FROM utilizadores WHERE id <> :id AND (telefone = :telefone OR email = :email OR username = :username)',
        $parames
    );

    if ($results->affected_rows > 0) {
        // Erro, já existe um contato com o mesmo telefone, email ou username
        $erro = 'Já existe outro contato com o mesmo telefone, email ou username.';
    } else {
        // Atualizar o contato atual
        $parames = [
            ':id' => $id,
            ':nome' => $nome,
            ':apelido' => $apelido,
            ':username' => $username,
            ':telefone' => $telefone,
            ':email' => $email,
            ':funcao' => $funcao
        ];

        // Incluir a senha na atualização se o checkbox for marcado
        if ($reset_senha) {
            $parames[':senha'] = $senha;
            $sql = "UPDATE utilizadores 
                    SET nome = :nome, apelido = :apelido, username = :username, telefone = :telefone, senha = :senha, email = :email, funcao = :funcao, updated_at = NOW()
                    WHERE id = :id";
        } else {
            $sql = "UPDATE utilizadores 
                    SET nome = :nome, apelido = :apelido, username = :username, telefone = :telefone, email = :email, funcao = :funcao, updated_at = NOW()
                    WHERE id = :id";
        }

        // Executar a consulta de atualização
        $database->execute_non_query($sql, $parames);

        header('Location: gerir_utilizadores.php');
        exit();
    }
}
?>
<div class="container" style="padding-bottom:50px;">
    <h2>Editar Utilizadores</h2>
<div class="row row align-items-start">
<div class="col-sm-5 col-md-6">
        <div class="card p-4">

            <form action="editar_contacto.php?id=<?= htmlspecialchars($id) ?>" method="post">
                <p class="text-center"><strong>EDITAR UTILIZADORES</strong></p>
                <div class="mb-3">
                    <label for="text_nome" class="form-label">Nome</label>
                    <input type="text" name="text_nome" id="text_nome" class="form-control" minlength="3" maxlength="50" required value="<?= htmlspecialchars($contact->nome) ?>">
                </div>
                <div class="mb-3">
                    <label for="text_apelido" class="form-label">Apelido</label>
                    <input type="text" name="text_apelido" id="text_apelido" class="form-control" minlength="3" maxlength="50" required value="<?= htmlspecialchars($contact->apelido) ?>">
                </div>
                <div class="mb-3">
                    <label for="text_username" class="form-label">Username</label>
                    <input type="text" name="text_username" id="text_username" class="form-control" minlength="3" maxlength="50" required value="<?= htmlspecialchars($contact->username) ?>">
                </div>                

                <div class="mb-3">
                    <label for="text_telefone" class="form-label">Telefone</label>
                    <input type="text" name="text_telefone" id="text_telefone" class="form-control" minlength="3" maxlength="12" required value="<?= htmlspecialchars($contact->telefone) ?>">
                </div>
                </div>
                </div>
                <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0">
                <div class="card p-4">
                <div class="mb-3">
                    <label for="text_email" class="form-label">Email</label>
                    <input type="email" name="text_email" id="text_email" class="form-control" minlength="3" required value="<?= htmlspecialchars($contact->email) ?>">
                </div>
                <div class="mb-3">
                    <label for="text_select" class="form-label">Função</label>
                    <select class="form-select" id="text_select" name="text_select">
                        <option value="user" >user</option>
                        <option value="admin">admin</option>
                    </select>
                </div>
                <div class="container" style="padding-top: 50px;">
                    <div class="row">
                        <div class="col order-last">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check_box" name="check_box" <?= isset($_POST['check_box']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="check_box">
                                    Redefinir senha
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <a href="gerir_utilizadores.php" class="btn btn-outline-dark">Cancelar</a>
                    <input type="submit" value="Atualizar" class="btn btn-outline-primary">
                </div>
            </form>
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

    </div>
</div>
<?php require_once('footer.php'); ?>


