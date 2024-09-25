<?php
use sys4soft\Database;
require_once('header.php');

// Verificar se o 'id' foi enviado via GET e é válido
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: login_index.php');
    exit();
}

require_once('../libraries/config.php');
require_once('../libraries/Database.php');

$id = $_GET['id'];
$database = new Database(MYSQL_CONFIG);
$parames = [':id' => $id];

// Verificar se o contato existe
$results = $database->execute_query("SELECT * FROM utilizadores WHERE id = :id", $parames);
$contacto = $results->results[0] ?? null;

if (!$contacto) {
    // Se o contato não for encontrado, redireciona ou exibe uma mensagem de erro
    header('Location: gerir_utilizadores.php');
    exit();
}

// Verificar se a solicitação de exclusão foi enviada
if (isset($_GET['delete']) && $_GET['delete'] === 'yes') {
    $database->execute_non_query("DELETE FROM utilizadores WHERE id = :id", $parames);
    header('Location: gerir_utilizadores.php');
    exit();
}
?>

<div class="row">
    <div class="col text-center">
        <h3>Deseja eliminar o seguinte contacto?</h3>

        <div class="my-4">
            <div>
                <span class="me-5">Nome: <strong><?= htmlspecialchars($contacto->nome) ?></strong></span>
                <span class="me-5">Username: <strong><?= htmlspecialchars($contacto->username) ?></strong></span>
                <span class="me-5">Email: <strong><?= htmlspecialchars($contacto->email) ?></strong></span>
                <span class="me-5">Telefone: <strong><?= htmlspecialchars($contacto->telefone) ?></strong></span>
            </div>
        </div>

        <a href="gerir_utilizadores.php" class="btn btn-outline-dark yes-no-width">Não</a>
        <a href="eliminar_contacto.php?id=<?= htmlspecialchars($id) ?>&delete=yes" class="btn btn-outline-dark yes-no-width">Sim</a>
    </div>
</div>

<?php
require_once('footer.php');
?>
